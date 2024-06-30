<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\DivisiTerkait;
use App\Models\Foto;
use App\Models\Laporan;
use App\Models\Pelabuhan;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Models\PengerjaanPelaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;


class LaporanController extends Controller
{

    public function index()
    {
        try {
            $dataLaporan = Laporan::orderBy('id', 'DESC')->get();
            return view('admin.laporan.index', compact('dataLaporan'));
        } catch (\Throwable $th) {
            Log::error($th);
            $th->getMessage();
            return view('admin.error.view');
        }
    }


    public function create()
    {
        
        try {
            $dataPelabuhan  = Pelabuhan::all();
            $dataDivisi     = Divisi::all();
            return view('admin.laporan.create',compact('dataPelabuhan','dataDivisi'));
        } catch (\Throwable $th) {
            Log::error($th);
            $th->getMessage();
            return view('admin.error.view');
        }
    }


    public function store(Request $request)
    {

        $request->validate([
            'judul'         => 'required',
            'deskripsi'     => 'required',
            'lokasi'        => 'required',
            'divisi'        => 'required|array|min:1',
            'divisi.*'      => 'string',
            'foto'          => 'required',
            'foto.*'        => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:5120',
            'file'          => 'nullable',
            'pelabuhan'     => 'required',
            'divisi'        => 'nullable|array'
        ],[
            'judul.required'        => 'Form judul harus diisi',
            'deskripsi.required'    => 'Form deskripsi harus diisi',
            'pelabuhan.required'    => 'Pilih salah satu Pelabuhan',
            'lokasi.required'       => 'Form lokasi harus diisi',
            'foto.required'         => 'Harap Lampirkan bukti foto',
            'divisi.required'       => 'Pilih divisi yang dituju',
            'divisi.array'          => 'Pilih divisi yang dituju',
            'divisi.min'            => 'Pilih divisi yang dituju',
            ]
        );

        try{            
            $prefix = date('ym');
            $ticketLaporan = IdGenerator::generate(['table' => 'laporans', 'field' => 'id', 'length' => 8, 'prefix' => $prefix, 'reset_on_prefix_change' => true]);   

            $storeLaporan               = new Laporan;
            $storeLaporan->id           = $ticketLaporan;
            $storeLaporan->user_id      = auth()->user()->id;
            $storeLaporan->judul        = $request->judul;
            $storeLaporan->deskripsi    = $request->deskripsi;
            $storeLaporan->pelabuhan    = $request->pelabuhan;
            $storeLaporan->lokasi       = $request->lokasi;
            $storeLaporan->status       = $request->status;

            if($request->hasFile('file')){
                $fileUpload = $request->file('file');
                $fileName = time() .'_'. $storeLaporan->id . '_' . $fileUpload->getClientOriginalName();
                $fileUpload->move(public_path('file'), $fileName);
                $storeLaporan->file = $fileName;
            }

            $storeLaporan->save();

            $checkBoxDivisi = $request->input('divisi',[]);
            foreach($checkBoxDivisi as $divisi){
                $divisiTerkait = new DivisiTerkait;
                $divisiTerkait->laporan_id  = $storeLaporan->id;
                $divisiTerkait->nama_divisi = $divisi; 
                $divisiTerkait->save();
            }

            if ($request->hasFile("foto")) {
                $files = $request->file("foto");
                foreach ($files as $file) {
                    $imageName      = time() .'_'. $storeLaporan->id . '_' . $file->getClientOriginalName();
                    $uploadFoto     = new Foto([
                        'laporan_id'    => $storeLaporan->id,
                        'foto'          => $imageName
                    ]);
                    $file->move(public_path('gambar'), $imageName);
                    $uploadFoto->save();
                }
            }
            

            try {
                $firstPhoto = Foto::query()->where('laporan_id',$storeLaporan->id)->first();
                $urlPhoto = public_path('gambar/'.$firstPhoto->foto);
                
                $groupChat = -4243954575;
                $url = action([LaporanController::class,'show'],$storeLaporan->id);
                $message = "*Laporan No $storeLaporan->id*\n \nJudul Laporan = $request->judul \nLokasi = $request->lokasi \nPelabuhan = $request->pelabuhan \nPelapor = ".auth()->user()->name.' '.auth()->user()->last_name."\n$url"."\n \n *Laporan telah dibuat*";
                Telegram::sendMessage([
                    'chat_id'   =>  $groupChat,
                    'text'      => $message,
                ]);
                Telegram::sendPhoto([
                    'chat_id'   => $groupChat,
                    'photo'     => new InputFile($urlPhoto),
                    'caption'    => "No Laporan $storeLaporan->id Dibuat"
                ]);

                Alert::toast('Laporan Terkirim !','success');
                return redirect('laporan/');

            } catch (\Throwable $th) {
                Log::error($th);
                $th->getMessage();
                Alert::toast('Tidak dapat mengirim Telegram, Periksa koneksi internet','warning');
                return redirect('laporan/');
            }

        } catch (\Throwable $th) {
            Log::error($th);
            $th->getMessage();
            return view('admin.error.view');
        }
    }

    public function edit(string $id)
    {
        try {
            $dataDivisi     = Divisi::all();
            $dataLaporan    = Laporan::find($id);
            $dataPelabuhan  = Pelabuhan::all();
            return view('admin.laporan.edit', compact('dataLaporan','dataDivisi','dataPelabuhan'));    
        } catch (\Throwable $th) {
            Log::error($th);
            $th->getMessage();
            return view('admin.error.view');
        }   
    }

    public function download($id){
        $dataLaporan = Laporan::find($id);
        $filePath =  public_path('file/'. $dataLaporan->file);

        return response()->download($filePath);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul'         => 'required',
            'deskripsi'     => 'required',
            'lokasi'        => 'required',
            'divisi'        => 'required|array|min:1',
            'divisi.*'      => 'string',
            'foto'          => 'required|image|mimes:jped,jpg,png,svg|max:5126',
            'foto.*'        => 'required|image|mimes:jpeg,jpg,png,svg|max:5126',
            'file'          => 'nullable',
            'pelabuhan'     => 'required',
            'divisi'        => 'nullable|array'
        ],[
            'judul.required'        => 'Field judul harus diisi',
            'deskripsi.required'    => 'Field deskripsi harus diisi',
            'lokasi.required'       => 'Field lokasi harus diisi',
            'pelabuhan.required'    => 'Pilih salah satu Pelabuhan',
            'divisi.required'       => 'Pilih divisi yang dituju',
            'divisi.array'          => 'Pilih divisi yang dituju',
            'divisi.min'            => 'Pilih divisi yang dituju',
            ]
        );

        try {
    
            $dataLaporan = Laporan::findOrFail($id);
    
            if (File::exists('file/' . $dataLaporan->file)) {
                File::delete('file/' . $dataLaporan->file);
    
                $fileUpload = $request->file('file');
                $fileName = time() .'_'. $dataLaporan->id . '_' . $fileUpload->getClientOriginalName();
                $fileUpload->move(public_path('file'), $fileName);
    
                $dataLaporan->file          = $fileName;
                $dataLaporan->user_id       = $dataLaporan->user_id;
                $dataLaporan->judul         = $request->judul;
                $dataLaporan->lokasi        = $request->lokasi;
                $dataLaporan->deskripsi     = $request->deskripsi;
                $dataLaporan->update();
            }
    
            foreach ($dataLaporan->foto as $foto) {
                if (File::exists('gambar/' . $foto->foto)) {
                    File::delete('gambar/' . $foto->foto);
                }
                $foto->delete();
            }
    
            if ($request->hasFile("foto")) {
                $files = $request->file("foto");
                foreach ($files as $file) {
                    $imageName      = time() .'_'. $dataLaporan->id . '_' . $file->getClientOriginalName();
                    $uploadFoto     = new Foto([
                        'laporan_id'    => $dataLaporan->id,
                        'foto'          => $imageName
                    ]);
                    $file->move(public_path('gambar'), $imageName);
                    $uploadFoto->save();
                }
            }

            try {
                $firstPhoto = Foto::query()->where('laporan_id',$id)->first();
                $urlPhoto = public_path('gambar/'.$firstPhoto->foto);
                
                $groupChat = -4243954575;
                $url = action([LaporanController::class,'show'],$id);
                $message = "*Laporan No $id*\n \nJudul Laporan = $dataLaporan->judul \nLokasi = $dataLaporan->lokasi \nPelabuhan = $dataLaporan->pelabuhan \nPelapor = ".auth()->user()->name.' '.auth()->user()->last_name."\n$url"."\n \n *Laporan $id telah diubah*";
                Telegram::sendMessage([
                    'chat_id'   =>  $groupChat,
                    'text'      => $message,
                ]);
                Telegram::sendPhoto([
                    'chat_id'   => $groupChat,
                    'photo'     => new InputFile($urlPhoto),
                    'caption'    => "No Laporan $id Diubah"
                ]);

                Alert::toast('Laporan Terkirim !','success');
                return redirect('laporan/');

            } catch (\Throwable $th) {
                Log::error($th);
                $th->getMessage();
                Alert::toast('Tidak dapat mengirim Telegram, Periksa koneksi internet','warning');
                return redirect('laporan/');
            }
    
            $dataLaporan->update();
            Alert::toast('Data Diupdate !','success');
            return redirect('/laporan');

        } catch (\Throwable $th) {
            Log::error($th);
            $th->getMessage();
            return view('admin.error.view');
        }
    }


    public function destroy($id)
    {
        try {
            $dataLaporan = Laporan::findOrFail($id);

            if (File::exists("file/" . $dataLaporan->file)) {
                File::delete("file/" . $dataLaporan->file);
            }

            foreach ($dataLaporan->foto as $foto) {
                if (File::exists('gambar/' . $foto->foto)) {
                    File::delete('gambar/' . $foto->foto);
                }
            }

            $dataLaporan->delete();
            return redirect('/laporan');

        } catch (\Throwable $th) {
            Log::error($th);
            $th->getMessage();
            return view('admin.error.view');
        }
    }


    public function error()
    {
        return view('admin.error.view');
    }

    public function show($id)
    {   
        try {
            $dataLaporan = Laporan::findOrFail($id);
            $dataPengerjaanLaporan = $dataLaporan->pengerjaan_pelaporan;
            $fotoLaporan = $dataLaporan->foto;
            return view('admin.laporan.view', compact('dataLaporan','fotoLaporan','dataPengerjaanLaporan'));    
        } catch (\Throwable $th) {
            Log::error($th);
            $th->getMessage();
            return view('admin.error.view');
        }
    }


    public function setuju(Request $request ,$id)
    {   
        try {
            $dataLaporan = Laporan::findOrFail($id);
            $dataLaporan->status = $request->status;
            $dataLaporan->update();

            try {
                $groupChat = -4243954575;
                $url = action([LaporanController::class,'show'],$dataLaporan->id);
                $message = "*Laporan No $dataLaporan->id* \n \nJudul Laporan = $dataLaporan->judul \nLokasi = $dataLaporan->lokasi \nPelabuhan = $dataLaporan->pelabuhan \nPelapor = ". $dataLaporan->user->name.' '.$dataLaporan->user->last_name."\n$url"."\n \n *Laporan sudah diterima*";
                Telegram::sendMessage([
                    'chat_id'   =>  $groupChat,
                    'text'      => $message
                ]);

                Alert::toast('Laporan Disetujui !','success');
                return redirect('/laporan');
            } catch (\Throwable $th) {
                Log::error($th);
                $th->getMessage();
                Alert::toast('Tidak dapat mengirim Telegram, Periksa koneksi internet','warning');
                return redirect('laporan/');
            }
            

        } catch (\Throwable $th) {
            Log::error($th);
            $th->getMessage();
            return view('admin.error.view');
        }
    }

    public function group($id)
    {
        try {
            $dataPelabuhan = Pelabuhan::all();
            $dataDivisi = Divisi::all();
            $dataLaporan = Laporan::find($id);
            $fotoLaporan = $dataLaporan->foto;
            $divisiTerkait = $dataLaporan->divisiTerkait->pluck('nama_divisi')->all();

            return view('admin.laporan.group',compact('dataPelabuhan','dataDivisi','dataLaporan','divisiTerkait','fotoLaporan'));
        } catch (\Throwable $th) {
            Log::error($th);
            $th->getMessage();
            return view('admin.error.view');
        }
    }

    public function groupping(Request $request, $id){
        try {
            $dataLaporan = Laporan::findOrFail($id);
            $dataLaporan->save();
            DivisiTerkait::query()->where('laporan_id',$id)->delete();

            $checkBoxDivisi = $request->input('divisi',[]);
            foreach($checkBoxDivisi as $divisi){
                $divisiTerkait = new DivisiTerkait;
                $divisiTerkait->laporan_id  = $dataLaporan->id;
                $divisiTerkait->nama_divisi = $divisi; 
                $divisiTerkait->save();
            }
            return redirect('/laporan');
        } catch (\Throwable $th) {
            Log::error($th);
            $th->getMessage();
            return view('admin.error.view');
        }
    }

    public function finsihed($id, Request $request) {
        if ($request->hasFile('image')) {
            $images = $request->file("image");
            foreach ($images as $image) {
                $imageName = time() .'_'. $id . '_' . $image->getClientOriginalName();
            
                $uploadFoto     = new PengerjaanPelaporan([
                    'laporan_id'    => $id,
                    'foto'          => $imageName
                ]);
                $image->move(public_path('laporan_selesai'), $imageName);
                $uploadFoto->save();
            }

            $laporan = Laporan::query()->findOrFail($id);
            $laporan->status = 3;
            $laporan->save();
            
            try {
                $firstPhoto = PengerjaanPelaporan::query()->where('laporan_id',$id)->first();
                $urlPhoto = public_path('laporan_selesai/'.$firstPhoto->foto);

                $url = action([LaporanController::class,'show'], $id);
                $groupChat = -4243954575;
                $message = "*Laporan No $laporan->id* \n \nJudul Laporan = $laporan->judul\nLokasi = $laporan->lokasi \nPelabuhan = $request->pelabuhan \nPelapor = ".$laporan->user->name.' '.$laporan->user->last_name."\n $url"."\n \n *Sudah selesai dikerjakan*";
                Telegram::sendMessage([
                    'chat_id'   => $groupChat,
                    'text'      => $message  
                ]);
                Telegram::sendPhoto([
                    'chat_id'   => $groupChat,
                    'photo'     => new InputFile($urlPhoto),
                    'caption'    => "No Laporan $id Sudah Dikerjakan"
                ]);

                Alert::toast('Laporan Berhasil diselesaikan !','success');
                return redirect('/laporan/'.$id.'/detail');
            } catch (\Throwable $th) {
                Log::error($th);
                $th->getMessage();
                Alert::toast('Tidak dapat mengirim Telegram, Periksa koneksi internet','warning');
                return redirect('laporan/');
            }

            
        }

        Alert::toast('Anda belum memasukkan bukti pengerjaan !','danger');
        return redirect('/laporan/'.$id.'/detail');
    }
}

@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Halaman Laporan') }}</h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container-fluid">

        <!-- Form -->
        <div class="card shadow mb-4">

            <div class="card-header py-3">
                <div class="row">
                    <div class="col text-left">
                        <a href="{{ route('laporan') }}" class="mr-4 btn-sm btn-light"><i class="fa-solid fa-arrow-left"></i></a>
                    </div>
                    <div class="col text-right">
                        <h5 class="font-weight-bold text-primary">Laporan No <b>{{$dataLaporan->id}}</b> oleh {{$dataLaporan->user->name}} {{$dataLaporan->user->last_name}}</h5>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('laporan.agree', $dataLaporan->id) }}" autocomplete="off"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf

                    <input type="hidden" name="status" value=2>

                    <div class="pl-lg-4">
                        <div class="row">


                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="judul">Judul Laporan</label>
                                    <input type="text" id="judul" class="form-control" name="judul"
                                        value="{{ old('judul', $dataLaporan->judul) }}" disabled>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="ringkasan">Deskripsi Laporan</label>
                                    <textarea type="text" id="deskripsi" class="form-control" name="deskripsi" disabled>{{ old('deskripsi', $dataLaporan->deskripsi) }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="ringkasan">Lokasi Pelabuhan</label>
                                    <input type="text" id="pelabuhan" class="form-control" name="pelabuhan"
                                        value="{{ old('pelabuhan', $dataLaporan->pelabuhan) }}" disabled>
                                </div>
                            </div>

                            <div class="col-lg-12 mb-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="lokasi">Lokasi</label>
                                    <textarea type="text" id="lokasi" class="form-control" name="lokasi" disabled>{{ old('lokasi', $dataLaporan->lokasi) }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <b>Bukti Foto Laporan</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="container">
                                            <div id="fotoLaporan" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    @if ($fotoLaporan)
                                                        @foreach ($fotoLaporan as $item)
                                                            <div class="carousel-item @if ($loop->first) active @endif">
                                                                <img src="{{asset('gambar/'.$item->foto)}}" class="d-block w-100" style="width:640px; height:320px;">
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-target="#fotoLaporan" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-target="#fotoLaporan" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <b>Bukti Laporan Pengerjaan</b>
                                    </div>
                                    <div class="card-body">

                                        @if ($dataLaporan->status == 3)
  
                                            <div class="container">
                                                <div id="fotoPengerjaan" class="carousel slide" data-ride="carousel">
                                                    <div class="carousel-inner">
                                                        @if ($dataPengerjaanLaporan)
                                                            @foreach ($dataPengerjaanLaporan as $item)
                                                                <div class="carousel-item @if ($loop->first) active @endif">
                                                                    <img src="{{asset('laporan_selesai/'.$item->foto)}}" class="d-block w-100" style="width:640px; height:320px;">
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <button class="carousel-control-prev" type="button" data-target="#fotoPengerjaan" data-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Previous</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button" data-target="#fotoPengerjaan" data-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Next</span>
                                                    </button>
                                                </div>
                                            </div>                             
                                        @else                                  
                                            <div class="container">
                                                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                    <div class="carousel-inner">
                                                        <div class="carousel-item active">
                                                            <img src="{{asset('default_image/belum-dikerjakan.png')}}" class="d-block w-100" style="width:640px; height:320px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="file">Dokumen Kelengkapan</label>
                                </div>
                            </div>

                            <div class="col-lg-12 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-start">
                                            @if ($dataLaporan->file == null)
                                                <span>Belum ada dokumen harap hubungi admin</span>
                                                <button disabled dis class="btn btn-primary btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </span>
                                                    <span class="text">Tidak ada Dokumen</span>
                                                </button>
                                            @else
                                            <span>Nama File &nbsp; : &nbsp; {{$dataLaporan->file}}</span>
                                                <a href="{{route('file_download',$dataLaporan->id)}}" class="btn btn-primary btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </span>
                                                    <span class="text">Unduh Dokumen</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col text-left">
                                <a href="{{ route('laporan') }}" class="btn btn-light">Kembali</a>
                            </div>
                            @if (auth()->user()->level == 4 | auth()->user()->level == 3)
                                @if ($dataLaporan->status == 1)
                                    <div class="col text-right">
                                        <button type="submit" class="btn btn-primary">Setujui Laporan</a>
                                    </div>
                                @endif

                                @if ($dataLaporan->status == 2)
                                    <div class="col text-right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal">Selesai dikerjakan</button>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('laporan.finish', $dataLaporan->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="image" class="col-form-label">Bukti Foto Pengerjaan:</label>
                            <input type="file" class="form-control" id="image" name="image[]" multiple></input>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Selesaikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

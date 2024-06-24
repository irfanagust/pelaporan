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
                <div class="d-flex justify-content-start">
                    <a href="{{ route('laporan') }}" class="mr-2 btn-sm btn-light"><i class="fa-solid fa-arrow-left"></i></a>
                    <h5 class="font-weight-bold text-primary">Laporan No <b>{{$dataLaporan->id}}</b> oleh {{$dataLaporan->user->name}} {{$dataLaporan->user->last_name}}</h5>
                </div>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('laporan.groupping' , $dataLaporan->id) }}" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    @method('PUT')
                    @csrf
                    
                    <input type="hidden" name="status" value={{$dataLaporan->status}}>

                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="judul">Judul Laporan</label>
                                    <input type="text" id="judul" class="form-control" name="judul" disabled value="{{$dataLaporan->judul}}">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="deskripsi">Deskripsi Laporan</label>
                                    <textarea type="text" id="deskripsi" class="form-control" name="deskripsi" disabled>{{$dataLaporan->deskripsi}}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="ringkasan">Lokasi Pelabuhan</label>
                                    <input type="text" id="pelabuhan" class="form-control" name="pelabuhan"
                                        value="{{ old('pelabuhan', $dataLaporan->pelabuhan) }}" disabled>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="lokasi">Detail Lokasi</label>
                                    <textarea disabled type="text" id="lokasi" class="form-control" name="lokasi">{{$dataLaporan->lokasi}}</textarea>
                                </div>
                            </div>

                            @if ($fotoLaporan->isEmpty())
                                <div class="col-lg-12 mt-4 mb-4">
                                    <div class="form-group text-center">
                                        <div class="container">
                                            <img class="object-fit-contain border rounded mb-4" src="{{asset('default_image/no_image.jpg')}}" alt="Responsive image" style="width: 40%">
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-12 mb-4 mt-4">
                                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="width: 40%">
                                        <div class="carousel-inner">
                                            @if ($fotoLaporan)
                                                @foreach ($fotoLaporan as $item)
                                                    <div class="carousel-item @if ($loop->first) active @endif">
                                                        <img src="{{asset('gambar/'.$item->foto)}}" class="d-block w-100" style="width: 40%">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="divisi">Divisi Terkait</label>
                                    @foreach ($dataDivisi as $item)
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" name="divisi[{{$item->nama_divisi}}]" value="{{$item->nama_divisi}}"
                                            @if (in_array($item->nama_divisi, $divisiTerkait)) checked @endif
                                            >
                                            <label class="form-check-label" for="exampleCheck1">{{$item['nama_divisi']}}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Button -->
                    <div class="pl-lg-4">
                        <div class="row">

                            <div class="col text-left">
                                <a href="{{route('laporan')}}" class="btn btn-light">Kembali</a>
                            </div>

                            <div class="col text-right">
                                <button type="submit" class="btn btn-primary">Sunting Divisi</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>



    </div>

@endsection

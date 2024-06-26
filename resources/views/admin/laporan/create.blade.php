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
                <h6 class="m-0 font-weight-bold text-primary">Pengajuan Laporan</h6>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('laporan.store') }}" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                    <input type="hidden" name="status" value={{1}}>

                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="judul">Judul Laporan <label for="foto" style="color: red">*</label></label>
                                    <input type="text" id="judul" class="form-control" name="judul" value="{{old('judul')}}">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="deskripsi">Deskripsi Laporan <label for="foto" style="color: red">*</label></label>
                                    <textarea type="text" id="deskripsi" class="form-control" name="deskripsi">{{old('deskripsi')}}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="pelabuhan">Lokasi Pelabuhan <label for="foto" style="color: red">*</label></label>
                                    <select class="form-control" name="pelabuhan" id="pelabuhan">
                                        <option value=""> - Pilih Pelabuhan - </option>
                                        @foreach ($dataPelabuhan as $item)
                                            <option value="{{$item->pelabuhan}}" {{ old('pelabuhan') == $item->pelabuhan ? 'selected' : '' }}>
                                                <b>
                                                    {{$item->pelabuhan}}
                                                </b>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="lokasi">Lokasi <label for="foto" style="color: red">*</label></label>
                                    <textarea type="text" id="lokasi" class="form-control" name="lokasi">{{old('lokasi')}}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="divisi">Divisi Terkait</label>
                                    @foreach ($dataDivisi as $item)
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" name="divisi[]" value="{{$item['nama_divisi']}}" id="divisi[]">
                                            <label class="form-check-label" for="exampleCheck1">{{$item['nama_divisi']}}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="foto">Bukti Foto laporan <label for="foto" style="color: red">*</label></label>
                                    <input type="file" id="foto" class="form-control" name="foto[]" multiple>
                                </div>
                            </div>

                            <div class="col-lg-12 mb-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="file">Dokumen Kelengkapan <label for="file" class="text-gray-500">{{'(jika ada)'}}</label></label> 
                                    <input type="file" id="file" class="form-control" name="file">
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
                                <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>



    </div>

@endsection

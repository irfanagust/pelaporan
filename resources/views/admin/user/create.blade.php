@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Halaman User') }}</h1>

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
                <h6 class="m-0 font-weight-bold text-primary">Tambahkan User</h6>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('user.store') }}" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- <input type="hidden" name="_method" value="POST"> --}}

                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="name">Nama Depan</label>
                                    <input type="text" id="name" class="form-control" name="name">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="last_name">Nama Belakang</label>
                                    <input type="text" id="last_name" class="form-control" name="last_name">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="telp">No Whatsapp</label>
                                    <input type="text" id="telp" class="form-control" name="telp">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="file">Level</label>
                                    <select class="form-control" name="level" id="level">
                                        @foreach ($listRole as $item)
                                            <option value="{{$item->id}}">{{$item->level}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="username">Username</label>
                                    <input type="text" id="username" class="form-control" name="username">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Button -->
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col text-right">
                                <button type="submit" class="btn btn-primary">Buat User</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>



    </div>

@endsection

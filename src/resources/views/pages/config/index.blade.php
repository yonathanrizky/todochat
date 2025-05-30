@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pengaturan Aplikasi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Pengaturan Aplikasi</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Pengaturan Aplikasi</h2>

                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card profile-widget">
                            <div class="profile-widget-header">
                                <img alt="image" src="{{ asset('img') . '/' . $data->logo }}"
                                    class="rounded-circle profile-widget-picture">
                            </div>
                            <div class="profile-widget-description">
                                <form method="POST" action="{{ route('config-app.store') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Judul Aplikasi</label>
                                            <input type="text"
                                                class="form-control @error('appname') is-invalid @enderror"
                                                value="{{ old('appname') ? old('appname') : $data->appname }}"
                                                name="appname">
                                            @error('appname')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Ubah Logo</label>
                                            <input name="file" class="form-control" type="file" />
                                        </div>

                                        <div class="form-group">
                                            <label>SMTP Host</label>
                                            <input type="text" class="form-control @error('host') is-invalid @enderror"
                                                value="{{ old('host') ? old('host') : $data->host }}" name="host">
                                            @error('host')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>SMTP Port</label>
                                            <input type="text" class="form-control @error('port') is-invalid @enderror"
                                                value="{{ old('port') ? old('port') : $data->port }}" name="port">
                                            @error('port')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>SMTP Username</label>
                                            <input type="text"
                                                class="form-control @error('username') is-invalid @enderror"
                                                value="{{ old('username') ? old('username') : $data->username }}"
                                                name="username">
                                            @error('username')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>SMTP Password</label>
                                            <input type="text"
                                                class="form-control @error('password') is-invalid @enderror"
                                                value="{{ old('password') ? old('password') : $data->password }}"
                                                name="password">
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>SMTP From Addres</label>
                                            <input type="text"
                                                class="form-control @error('from_address') is-invalid @enderror"
                                                value="{{ old('from_address') ? old('from_address') : $data->from_address }}"
                                                name="from_address">
                                            @error('from_address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>SMTP From Name</label>
                                            <input type="text"
                                                class="form-control @error('from_name') is-invalid @enderror"
                                                value="{{ old('from_name') ? old('from_name') : $data->from_name }}"
                                                name="from_name">
                                            @error('from_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Pesan</label>
                                            <div class="@error('email') is-invalid @enderror">
                                                <textarea class="summernote-simple" name="email">{{ old('email') ? old('email') : $data->email }}</textarea>
                                            </div>
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush

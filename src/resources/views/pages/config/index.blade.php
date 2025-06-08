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
                                            <label>Open AI Key</label>
                                            <input type="text"
                                                class="form-control @error('openai_api_key') is-invalid @enderror"
                                                value="{{ old('openai_api_key') ? old('openai_api_key') : $data->openai_api_key }}"
                                                name="openai_api_key">
                                            @error('openai_api_key')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Open AI Model</label>
                                            <input type="text"
                                                class="form-control @error('open_ai_model') is-invalid @enderror"
                                                value="{{ old('open_ai_model') ? old('open_ai_model') : $data->open_ai_model }}"
                                                name="open_ai_model">
                                            @error('open_ai_model')
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

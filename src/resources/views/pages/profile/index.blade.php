@extends('layouts.app')

@section('title', 'Profil')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <style type="text/css">
        .signature-pad {
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            height: 260px;
        }
    </style>
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Profil</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Profil</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">{{ $pegawai->fullname }}</h2>

                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card profile-widget">
                            <div class="profile-widget-header">
                                <img alt="image" src="{{ asset('img/avatar/') . '/' . $pegawai->avatar }}"
                                    class="rounded-circle profile-widget-picture">
                                <div class="profile-widget-items">
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">NIK</div>
                                        <div class="profile-widget-item-value">{{ $pegawai->nik }}</div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Department</div>
                                        <div class="profile-widget-item-value">{{ $pegawai->department }}</div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Divisi</div>
                                        <div class="profile-widget-item-value">{{ $pegawai->divisi }}</div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Jabatan</div>
                                        <div class="profile-widget-item-value">{{ $pegawai->position }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-widget-description">
                                <form method="POST" action="{{ route('profile.update', $pegawai->id) }}"
                                    enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    {{ method_field('put') }}
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') ? old('email') : $pegawai->email }}" name="email">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>No HP</label>
                                            <input type="number" class="form-control @error('phone') is-invalid @enderror"
                                                value="{{ old('phone') ? old('phone') : $pegawai->phone }}" name="phone">
                                            @error('phone')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password">
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Ubah Foto</label>
                                            <input name="file" class="form-control" type="file" />
                                        </div>
                                        @if (Auth::user()->pegawai_code == '1' || Auth::user()->pegawai_code == '2')
                                            <div class="form-group">
                                                <label>Tanda Tangan</label>
                                                <input type="hidden" name="signature" id="signature">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="wrapper">
                                                            <div class="signature-pad">
                                                                <img alt="image"
                                                                    src="{{ asset('img/signature/') . '/' . $pegawai->signature }}"
                                                                    class="img-responsive img-thumbnail"
                                                                    style="min-height:200px; max-height:260px;  width:100%;">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-6 text-right">
                                                        <div class="wrapper">
                                                            <canvas id="signature-pad" class="signature-pad"></canvas>
                                                        </div>
                                                        <br>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            id="clear"><i class="fa fa-eraser"></i> Clear</button>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif
                                    </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary" id="save-profile">Simpan</button>
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
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script src="{{ asset('js/signature-pad.js') }}"></script>
    <!-- Page Specific JS File -->
@endpush

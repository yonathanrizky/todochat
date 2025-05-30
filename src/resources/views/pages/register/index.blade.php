@extends('layouts.auth')

@section('title', 'Login')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Login</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                value="{{ old('fullname') }}" name="fullname">
                            @error('fullname')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <label>Alamat Lengkap</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address') }}" name="address">
                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" name="email">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>No HP</label>
                        <input type="number" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}" name="phone">
                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                            placeholder="Password" value="{{ old('password') }}">
                        @error('password')
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
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush

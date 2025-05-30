@extends('layouts.app')

@section('title', 'Produk')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Produk</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Parameter</a></div>
                    <div class="breadcrumb-item">Produk</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Nama Produk</label>
                                        <input type="text"
                                            class="form-control @error('product_name') is-invalid @enderror"
                                            value="{{ old('product_name') }}" name="product_name">
                                        @error('product_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Harga Produk</label>
                                        <input type="text" class="form-control @error('price') is-invalid @enderror"
                                            value="{{ old('price') }}" name="price" id="angka"
                                            onkeyup="formatNumber(this)">
                                        @error('price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <input type="text"
                                            class="form-control @error('description') is-invalid @enderror"
                                            value="{{ old('description') }}" name="description">
                                        @error('description')
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
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script>
        function formatNumber(input) {
            let number = input.value.replace(/[^0-9]/g, ''); // Hapus karakter non-angka
            if (number.length > 0) {
                number = number.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambahkan titik setiap 3 digit
                input.value = number;
            } else {
                input.value = ''; // Jika tidak ada angka, kosongkan
            }
        }
    </script>
    <!-- Page Specific JS File -->
@endpush

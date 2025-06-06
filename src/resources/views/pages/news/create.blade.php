@extends('layouts.app')

@section('title', 'News')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>News</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Parameter</a></div>
                    <div class="breadcrumb-item">News</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <form method="POST" action="{{ route('news.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Judul</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            value="{{ old('title') }}" name="title">
                                        @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Keterangan</label>
                                        <div class="@error('content') is-invalid @enderror">
                                            <textarea class="summernote" id="summernote" name="content" id="content">
                                                {{ old('content') }}
                                            </textarea>
                                        </div>
                                        @error('content')
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
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script>
        $('#summernote').summernote({
            height: 100,
            toolbar: [],
            dialogsInBody: true
        });
        $('.note-image-url').first().remove();
    </script>
    <!-- Page Specific JS File -->
@endpush

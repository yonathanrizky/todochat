@extends('layouts_customer.app')

@section('title', 'Chat')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Chat</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Chat</a></div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <form method="POST" action="{{ route('search') }}">
                                @csrf
                                <div class="card-body">

                                    <div class="form-group">
                                        <label class="col-form-label">Pesan</label>
                                        <div class="@error('description') is-invalid @enderror">
                                            <textarea class="summernote" id="summernote" name="description" id="description">
                                                {{ old('description') }}
                                            </textarea>
                                        </div>
                                        @error('description')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary">Kirim</button>
                                    </div>
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

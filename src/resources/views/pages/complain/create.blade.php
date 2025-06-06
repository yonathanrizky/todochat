@extends('layouts_customer.app')

@section('title', 'Keluhan')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Keluhan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Keluhan</a></div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">

                            <form method="POST" action="{{ route('complain.store') }}">
                                @csrf
                                <div class="card-body">

                                    <div class="form-group">
                                        <label class="col-form-label">Pesan</label>
                                        <div class="@error('description') is-invalid @enderror">
                                            <textarea class="summernote" id="description" name="description">
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
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script>
        $('#description').summernote({
            height: 100,
            toolbar: [],
            dialogsInBody: true,
        });
        $('.note-image-url').first().remove();
    </script>
    <!-- Page Specific JS File -->
@endpush

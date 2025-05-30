@extends('layouts.app')

@section('title', 'Data Customer')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Customer</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Parameter</a></div>
                    <div class="breadcrumb-item">Data Customer</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>&nbsp;</h4>
                                <div class="card-header-action">
                                    <form>
                                        <div class="input-group">
                                            <a href="{{ route('customer.create') }}" class="btn btn-primary">Tambah</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                {{ $dataTable->table(['class' => 'table table-border'], true) }}
                            </div>
                            <div class="card-footer">

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
    {{ $dataTable->scripts() }}

    <!-- Page Specific JS File -->
@endpush

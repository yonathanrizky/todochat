@extends('layouts.app')

@section('title', 'Keluhan')
@push('style')
    <!-- CSS Libraries -->

    <style>
        body {
            font-family: sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        #chat {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }

        .bubble {
            max-width: 70%;
            margin-bottom: 10px;
            padding: 10px 14px;
            border-radius: 20px;
            position: relative;
            line-height: 1.4;
            white-space: pre-wrap;
        }

        .user {
            align-self: flex-end;
            background-color: #dcfce7;
        }

        .bot {
            align-self: flex-start;
            background-color: #e0e7ff;
        }

        form {
            display: flex;
            padding: 1rem;
            border-top: 1px solid #ccc;
            background: white;
        }

        input[type="text"] {
            flex: 1;
            padding: 12px;
            border-radius: 20px;
            border: 1px solid #ccc;
        }

        button {
            padding: 12px 20px;
            margin-left: 10px;
            border: none;
            background-color: #2563eb;
            color: white;
            border-radius: 20px;
        }
    </style>
@endpush
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
                            <div class="form-group">
                                <label class="col-form-label"> &nbsp; No Tiket : {{ $complain->ticket_num }}</label>
                            </div>
                            <label style="margin-left: 1%">{!! $complain->description !!}</label>
                        </div>

                        <div class="card">
                            <h4 class="section-header">History Chat</h4>
                            <div id="chat">
                                @foreach ($chats as $item)
                                    @if (!$item->bot)
                                        <div class="bubble user">{{ $item->message }}</div>
                                    @else
                                        <div class="bubble bot">{{ $item->message }}</div>
                                    @endif
                                @endforeach
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

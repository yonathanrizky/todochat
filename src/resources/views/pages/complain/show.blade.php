@extends('layouts_customer.app')
@section('title', 'Keluhan')
@push('style')
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
        }

        /* ===== Info Card ===== */
        .complain-card {
            background: #fff;
            border-radius: 14px;
            padding: 24px 28px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            margin-bottom: 20px;
        }
        .complain-card .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #eef0f3;
        }
        .ticket-header .ticket-num {
            font-size: 13px;
            color: #8a94a6;
            letter-spacing: .3px;
        }
        .ticket-header .ticket-num b {
            color: #1f2937;
            font-size: 15px;
        }
        .status-badge {
            background: #e0e7ff;
            color: #4338ca;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
        }
        .info-row {
            display: flex;
            gap: 14px;
            padding: 10px 0;
        }
        .info-row + .info-row {
            border-top: 1px dashed #eef0f3;
        }
        .info-row .info-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #f3f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #2563eb;
            font-size: 15px;
        }
        .info-row .info-text {
            display: flex;
            flex-direction: column;
        }
        .info-row .info-label {
            font-size: 12px;
            color: #8a94a6;
            margin-bottom: 2px;
        }
        .info-row .info-value {
            font-size: 14.5px;
            color: #1f2937;
            font-weight: 500;
        }
        .complain-desc {
            margin-top: 16px;
            background: #f8f9fb;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 14px;
            color: #374151;
            line-height: 1.6;
        }
        .complain-desc-label {
            font-size: 12px;
            color: #8a94a6;
            margin-bottom: 6px;
            display: block;
        }

        /* ===== Chat Card ===== */
        .chat-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .chat-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #eef0f3;
            font-size: 15px;
            font-weight: 600;
            color: #1f2937;
        }
        #chat {
            height: 460px;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            background: #eef1f6;
        }
        .msg-row {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            margin-bottom: 16px;
        }
        .msg-row.user { justify-content: flex-end; }
        .msg-row.bot { justify-content: flex-start; }

        .avatar {
            width: 32px;
            height: 32px;
            min-width: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            line-height: 1;
            user-select: none;
        }
        .avatar.bot { background: #6366f1; }
        .avatar.user { background: #22c55e; }

        .msg-col {
            display: flex;
            flex-direction: column;
            max-width: 60%;
        }
        .msg-row.user .msg-col { align-items: flex-end; }
        .msg-row.bot .msg-col { align-items: flex-start; }

        .bubble {
            padding: 10px 16px;
            line-height: 1.5;
            white-space: pre-wrap;
            font-size: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            word-break: break-word;
        }
        .bubble.user {
            background-color: #22c55e;
            color: #fff;
            border-radius: 18px 18px 4px 18px;
        }
        .bubble.bot {
            background-color: #fff;
            color: #1f2937;
            border-radius: 18px 18px 18px 4px;
        }
        .msg-time {
            font-size: 11px;
            color: #9aa1ac;
            margin-top: 4px;
            padding: 0 4px;
        }

        /* ===== Chat Input (kalau ada form kirim pesan) ===== */
        .chat-form {
            display: flex;
            padding: 14px 16px;
            border-top: 1px solid #eef0f3;
            background: #fff;
        }
        .chat-form input[type="text"] {
            flex: 1;
            padding: 12px 16px;
            border-radius: 20px;
            border: 1px solid #dfe3e8;
            font-size: 14px;
            outline: none;
        }
        .chat-form input[type="text"]:focus {
            border-color: #2563eb;
        }
        .chat-form button {
            padding: 12px 22px;
            margin-left: 10px;
            border: none;
            background-color: #2563eb;
            color: white;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
        }
        .chat-form button:hover {
            background-color: #1d4ed8;
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

                        <div class="complain-card">
                            <div class="ticket-header">
                                <div class="ticket-num">
                                    No. Tiket<br><b>{{ $complain->ticket_num }}</b>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-icon"><i class="fas fa-user"></i></div>
                                <div class="info-text">
                                    <span class="info-label">Nama</span>
                                    <span class="info-value">{{ $complain->fullname }}</span>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="info-text">
                                    <span class="info-label">Kelurahan</span>
                                    <span class="info-value">{{ $complain->kelurahan }}</span>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-icon"><i class="fas fa-home"></i></div>
                                <div class="info-text">
                                    <span class="info-label">Alamat</span>
                                    <span class="info-value">{{ $complain->address }}</span>
                                </div>
                            </div>

                            <div class="complain-desc">
                                <span class="complain-desc-label">Deskripsi Keluhan</span>
                                {!! $complain->description !!}
                            </div>
                        </div>

                        <div class="chat-card">
                            <div class="chat-card-header">History Chat</div>
                            <div id="chat">
                                @foreach ($chats as $item)
                                    @if (!$item->bot)
                                        <div class="msg-row user">
                                            <div class="msg-col">
                                                <div class="bubble user">{{ $item->message }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="msg-row bot">
                                            <div class="msg-col">
                                                <div class="bubble bot">{!! nl2br(e($item->message)) !!}</div>
                                            </div>
                                        </div>
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
    <script>
        // Auto-scroll ke chat paling bawah tiap load halaman
        const chatBox = document.getElementById('chat');
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>
@endpush

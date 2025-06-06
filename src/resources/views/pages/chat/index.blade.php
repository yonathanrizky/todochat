@extends('layouts_customer.app')

@section('title', 'Chat')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">

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
                <h1>Chat</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Chat</a></div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">

                            <div id="chat">

                            </div>

                            <div id="loading" style="display:none; text-align: center; padding: 1rem;">
                                <div class="spinner-border text-info" role="status">
                                </div>
                            </div>

                            <form id="chat-form">
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
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script>
        $('#description').summernote({
            height: 100,
            toolbar: [],
            dialogsInBody: true,
        });
        $('.note-image-url').first().remove();

        const chat = document.getElementById('chat');
        const form = document.getElementById('chat-form');
        const input = document.getElementById('description');

        function scrollToBottom() {
            chat.scrollTop = chat.scrollHeight;
        }

        scrollToBottom();

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const message = input.value.trim().replaceAll('<p>', '').replaceAll('</p>', '');
            if (!message) return;

            const userBubble = document.createElement('div');
            userBubble.className = 'bubble user';
            userBubble.textContent = message;
            chat.appendChild(userBubble);
            scrollToBottom();

            $('#description').summernote("reset");
            document.getElementById('loading').style.display = 'block';

            fetch('{{ route('chat.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(res => res.json())
                .then(data => {
                    const botBubble = document.createElement('div');
                    botBubble.className = 'bubble bot';
                    botBubble.textContent = data.bot;
                    chat.appendChild(botBubble);
                    scrollToBottom();


                    setTimeout(function() {
                        const botBubble = document.createElement('div');
                        botBubble.className = 'bubble bot';
                        botBubble.textContent =
                            'Jika jawaban ini tidak membantu, klik button berikut \n\n';
                        chat.appendChild(botBubble);

                        const anchor = document.createElement('a');
                        anchor.href = '{{ route('complain.create') }}';
                        anchor.className = 'btn btn-primary';
                        anchor.innerText = 'Kirim Keluhan';
                        botBubble.appendChild(anchor);
                        scrollToBottom();
                    }, 5000);
                })
                .catch(err => {
                    console.error(err);
                })
                .finally(() => {
                    document.getElementById('loading').style.display = 'none';
                });
        });
    </script>
    <!-- Page Specific JS File -->
@endpush

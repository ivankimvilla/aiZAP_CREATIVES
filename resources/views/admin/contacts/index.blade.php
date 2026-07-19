@extends('admin.layout')

@section('title', 'Messages')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/messages.css') }}" />
    <main class="msg-page">
        <section class="msg-hero">
            <h1 class="hero-title">Messages</h1>
            <p class="hero-sub">Review and reply to incoming contact messages from the dashboard.</p>
        </section>

        @if (session('success'))
            <div class="alert alert-success">
                <span class="alert-icon">&#10003;</span>
                {{ session('success') }}
            </div>
        @endif

        <section class="msg-list">
            @forelse ($messages as $message)
                <div class="msg-card">
                    <div class="msg-card-top">
                        <div class="msg-identity">
                            <div class="msg-avatar">{{ strtoupper(substr($message->name, 0, 1)) }}</div>
                            <div>
                                <p class="msg-name">{{ $message->name }}</p>
                                <a href="mailto:{{ $message->email }}" class="msg-email">{{ $message->email }}</a>
                            </div>
                        </div>
                        <div class="msg-meta">
                            <span class="msg-status status-{{ \Illuminate\Support\Str::slug($message->status) }}">
                                {{ ucfirst($message->status) }}
                            </span>
                            @if ($message->created_at)
                                <span class="msg-date">{{ $message->created_at->format('M j, Y g:ia') }}</span>
                            @endif
                        </div>
                    </div>

                    <p class="msg-body">{{ $message->message }}</p>

                    @if ($message->reply_message)
                        <div class="msg-reply-existing">
                            <span class="msg-reply-label">Your reply</span>
                            <p>{{ $message->reply_message }}</p>
                        </div>
                    @endif

                    <div class="msg-actions-row">
                        <form method="post" action="{{ route('admin.contacts.reply', $message) }}" class="msg-reply-form">
                            @csrf
                            <div class="msg-reply-input-row">
                                <label for="recipient_email_{{ $message->id }}">Recipient</label>
                                <input id="recipient_email_{{ $message->id }}" type="email" name="recipient_email" value="{{ $message->email }}" readonly>
                            </div>
                            <div class="msg-reply-input-row">
                                <label for="reply_subject_{{ $message->id }}">Subject</label>
                                <input id="reply_subject_{{ $message->id }}" type="text" name="reply_subject" value="Re: Your message to aiZAP Creatives" placeholder="Email subject" required>
                            </div>
                            <textarea name="reply_message" rows="3" placeholder="Type a reply...">{{ old('reply_message', $message->reply_message) }}</textarea>
                            <button type="submit" class="btn-primary">Send reply</button>
                        </form>

                        <form method="post" action="{{ route('admin.contacts.destroy', $message) }}" class="msg-delete-form" onsubmit="return confirm('Delete this message? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Delete message">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                    <path d="M10 11v6"></path>
                                    <path d="M14 11v6"></path>
                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="msg-empty">
                    <p>No messages yet.</p>
                    <span>Incoming contact messages will show up here.</span>
                </div>
            @endforelse
        </section>
    </main>

@endsection
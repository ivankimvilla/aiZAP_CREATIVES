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
                <div class="msg-card collapsed"
                    data-card-id="{{ $message->id }}"
                    data-card-type="message"
                    data-card-seen="{{ $message->seen ? 'true' : 'false' }}"
                    data-mark-read-url="{{ route('admin.contacts.mark_read_single', $message) }}">
                    <div class="msg-card-top">
                        <div class="msg-identity">
                            <div class="msg-avatar">{{ strtoupper(substr($message->name, 0, 1)) }}</div>
                            <div class="msg-contact-info">
                                <div class="msg-name-row">
                                    <p class="msg-name">{{ $message->name }}</p>
                                    @if (!$message->seen)
                                        <span class="msg-new"></span>
                                    @endif
                                </div>
                                <div class="msg-recipient-subject-inline">
                                    <input type="email" name="recipient_email" form="reply_form_{{ $message->id }}"
                                        value="{{ $message->email }}" readonly
                                        class="msg-inline-field msg-inline-recipient">
                                    <input type="hidden" name="reply_subject" form="reply_form_{{ $message->id }}"
                                        value="Re: Thanks for contacting aiZAP Creatives">
                                </div>
                                <p class="msg-email">{{ $message->email }}</p>
                                @if(!empty($message->phone))
                                    <p class="msg-phone">{{ $message->phone }}</p>
                                @endif
                                <p class="msg-snippet">
                                    @if ($message->replies()->exists())
                                        <strong>Replied:</strong> {{ \Illuminate\Support\Str::limit($message->replies()->orderByDesc('created_at')->first()->message, 72) }}
                                    @else
                                        {{ \Illuminate\Support\Str::limit($message->message, 72) }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="msg-meta">
                            @if ($message->created_at)
                                <span class="msg-summary-time">{{ $message->created_at->diffForHumans() }}</span>
                            @endif
                            <form method="post" action="{{ route('admin.contacts.destroy', $message) }}" class="msg-delete-form" onclick="event.stopPropagation()" onsubmit="return confirm('Delete this message? This cannot be undone.');">
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

                    <div class="msg-conversation">
                        <form method="post" action="{{ route('admin.contacts.reply', $message) }}" class="msg-reply-form" id="reply_form_{{ $message->id }}">
                            @csrf

                            <div class="chat-history">
                                <div class="chat-message user">
                                    <div class="chat-meta">
                                        <span>{{ $message->name }}</span>
                                        <span>·</span>
                                        <span>{{ $message->created_at->format('M j, Y · g:i A') }}</span>
                                    </div>
                                    <div class="chat-row">
                                        <div class="chat-avatar">{{ strtoupper(substr($message->name, 0, 1)) }}</div>
                                        <div class="chat-bubble chat-bubble-user">{{ $message->message }}</div>
                                    </div>
                                </div>

                                @foreach ($message->replies()->orderBy('created_at')->get() as $reply)
                                    <div class="chat-message admin">
                                        <div class="chat-meta">
                                            <span>{{ $reply->author ?? 'Admin' }}</span>
                                            <span>·</span>
                                            <span>{{ $reply->created_at->format('M j, Y · g:i A') }}</span>
                                        </div>
                                        <div class="chat-row">
                                            <div class="chat-bubble chat-bubble-admin">{{ $reply->message }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="msg-actions-row">
                                <textarea name="reply_message" rows="3" placeholder="Type a reply..."></textarea>
                                <button type="submit" class="btn-primary">Send reply</button>
                            </div>
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
    <script type="module">
        import adminReply from '/js/admin/reply.js';
        import adminCardToggle from '/js/admin/card-toggle.js';
        try { adminCardToggle(); } catch (e) { console.error('adminCardToggle init failed', e); }
        try { adminReply(); } catch (e) { console.error('adminReply init failed', e); }
    </script>

@endsection
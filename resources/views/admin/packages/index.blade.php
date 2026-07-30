@extends('admin.layout')

@section('title', 'Packages')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/packages.css') }}" />
<link rel="stylesheet" href="{{ asset('css/admin/bulk-actions.css') }}" />
    <main class="msg-page">
        <section class="msg-hero">
            <h1 class="hero-title">Packages</h1>
            <p class="hero-sub">View and manage users who selected a pricing package.</p>
        </section>

        <section class="bulk-toolbar" data-bulk-item-selector=".msg-card" data-empty-state-selector=".msg-empty">
            <label class="bulk-select">
                <input type="checkbox" class="bulk-select-all" aria-label="Select all package requests" />
                Select all
            </label>
            <div class="bulk-actions">
                <span class="bulk-selected-count">0 selected</span>
                <button type="button" class="btn-delete-selected" data-delete-url="{{ route('admin.packages.bulk_destroy', [], false) }}" disabled>Delete selected</button>
            </div>
        </section>

        @if (session('success'))
            <div class="alert alert-success">
                <span class="alert-icon">&#10003;</span>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <span class="alert-icon">!</span>
                {{ session('error') }}
            </div>
        @endif

        <section class="msg-list">
            @forelse ($packageRequests as $request)
                <div class="msg-card collapsed"
                     data-card-id="{{ $request->id }}"
                     data-card-type="package"
                     data-card-seen="{{ $request->seen ? 'true' : 'false' }}"
                     data-mark-read-url="{{ route('admin.packages.mark_read_single', $request) }}">
                    <div class="msg-card-top">
                        <div class="msg-card-select">
                            <input type="checkbox" class="bulk-item-checkbox" data-item-id="{{ $request->id }}" aria-label="Select package request" />
                        </div>
                        <div class="msg-identity">
                            <div class="msg-avatar">{{ strtoupper(substr($request->name, 0, 1)) }}</div>
                            <div class="msg-contact-info">
                                <div class="msg-name-row">
                                    <p class="msg-name">{{ $request->name }}</p>
                                    @if (!$request->seen)
                                        <span class="msg-new"></span>
                                    @endif
                                </div>
                                <p class="msg-email">{{ $request->email }}</p>
                                @if(!empty($request->company))
                                    <p class="msg-company"><strong class="msg-field-label">Company:</strong> {{ $request->company }}</p>
                                @endif
                                @if(!empty($request->phone))
                                    <p class="msg-phone">{{ $request->phone }}</p>
                                @endif
                                <p class="msg-snippet">
                                    @if ($request->replies()->exists())
                                        <strong>Replied:</strong> {{ \Illuminate\Support\Str::limit($request->replies()->orderByDesc('created_at')->first()->message, 72) }}
                                    @else
                                        Package: {{ $request->package }} · {{ \Illuminate\Support\Str::limit($request->message, 72) }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="msg-meta">
                            @if ($request->created_at)
                                <span class="msg-summary-time">{{ $request->created_at->diffForHumans() }}</span>
                            @endif
                            <form method="post" action="{{ route('admin.packages.destroy', $request) }}" class="msg-delete-form" onclick="event.stopPropagation()" onsubmit="return confirm('Delete this package request? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="Delete package request">
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
                        <div class="chat-history">
                            <div class="chat-message user">
                                <div class="chat-meta">
                                    <span>{{ $request->name }}</span>
                                    <span>·</span>
                                    <span>{{ $request->created_at->format('M j, Y · g:i A') }}</span>
                                </div>
                                <div class="chat-row">
                                    <div class="chat-avatar">{{ strtoupper(substr($request->name, 0, 1)) }}</div>
                                    <div class="chat-bubble chat-bubble-user">
                                        <p><strong>Selected Package:</strong> {{ $request->package }}</p>
                                        @if(isset($plans[$request->package]))
                                            <p class="package-subtitle">{{ $plans[$request->package]['subtitle'] }}</p>
                                            <ul class="package-features">
                                                @foreach($plans[$request->package]['items'] as $feature)
                                                    <li>{{ $feature }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <p>{{ $request->message }}</p>
                                    </div>
                                </div>
                            </div>

                            @foreach ($request->replies()->orderBy('created_at')->get() as $reply)
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
                            <form method="post" action="{{ route('admin.packages.reply', $request) }}" class="msg-reply-form">
                                @csrf
                                <textarea name="reply_message" rows="3" placeholder="Type a package reply..."></textarea>
                                <button type="submit" class="btn-primary">Send reply</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="msg-empty">
                    <p>No package requests yet.</p>
                    <span>Users who select a package will show up here.</span>
                </div>
            @endforelse
        </section>
    </main>
    <script type="module">
        import adminReply from '/js/admin/reply.js';
        import adminCardToggle from '/js/admin/card-toggle.js';
        import adminBulkDelete from '/js/admin/bulk-delete.js';
        try { adminCardToggle(); } catch (e) { console.error('adminCardToggle init failed', e); }
        try { adminReply(); } catch (e) { console.error('adminReply init failed', e); }
        try { adminBulkDelete(); } catch (e) { console.error('adminBulkDelete init failed', e); }
    </script>

@endsection
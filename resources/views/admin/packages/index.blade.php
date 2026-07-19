@extends('admin.layout')

@section('title', 'Packages')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/packages.css') }}" />
    <main class="msg-page">
        <section class="msg-hero">
            <h1 class="hero-title">Packages</h1>
            <p class="hero-sub">View and manage users who selected a pricing package.</p>
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

        <div class="packages-toolbar">
            <p class="packages-count">Unread package requests: <strong>{{ $unseenCount }}</strong></p>
            @if ($unseenCount > 0)
                <form method="post" action="{{ route('admin.packages.mark_read') }}">
                    @csrf
                    <button type="submit" class="btn">Mark all read</button>
                </form>
            @endif
        </div>

        <section class="msg-list packages-grid">
            @forelse ($packageRequests as $request)
                <div class="msg-card">
                    <div class="msg-card-top">
                        <div class="msg-identity">
                            <div class="msg-avatar">{{ strtoupper(substr($request->name, 0, 1)) }}</div>
                            <div>
                                <p class="msg-name">{{ $request->name }} @if(!$request->seen) <span class="msg-new">New</span> @endif</p>
                                <a href="mailto:{{ $request->email }}" class="msg-email">{{ $request->email }}</a>
                                @if(!empty($request->phone))
                                    <p class="msg-phone">{{ $request->phone }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="msg-meta">
                            <span class="msg-status status-{{ \Illuminate\Support\Str::slug($request->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                            <span class="msg-status status-{{ \Illuminate\Support\Str::slug($request->email_status ?? 'not_sent') }}">
                                {{ ucfirst(str_replace('_', ' ', $request->email_status ?? 'not_sent')) }}
                            </span>
                            @if ($request->created_at)
                                <span class="msg-date">{{ $request->created_at->format('M j, Y g:ia') }}</span>
                            @endif
                            @if ($request->replied_at)
                                <span class="msg-date">Replied {{ $request->replied_at->format('M j, Y g:ia') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="msg-body">
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

                    @if ($request->reply_message)
                        <div class="msg-reply-existing">
                            <span class="msg-reply-label">reply</span>
                            <p>{{ $request->reply_message }}</p>
                        </div>
                    @endif

                    <div class="msg-actions-row">
                        <form method="post" action="{{ route('admin.packages.reply', $request) }}" class="msg-reply-form">
                            @csrf
                            <textarea name="reply_message" rows="3" placeholder="Type a package reply...">{{ old('reply_message', $request->reply_message) }}</textarea>
                            <button type="submit" class="btn-primary">Send reply</button>
                        </form>

                        <form method="post" action="{{ route('admin.packages.destroy', $request) }}" class="msg-delete-form" onsubmit="return confirm('Delete this package request? This cannot be undone.');">
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
            @empty
                <div class="msg-empty">
                    <p>No package requests yet.</p>
                    <span>Users who select a package will show up here.</span>
                </div>
            @endforelse
        </section>
    </main>
@endsection
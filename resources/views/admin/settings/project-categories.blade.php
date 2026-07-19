@extends('admin.layout')

@section('title', 'Project Categories')

@section('content')
    <main class="page-main">
        <section class="page-hero">
            <h1 class="hero-title">Visible Project Categories</h1>
            <p class="hero-sub">Choose which project categories should show videos on the public site.</p>
        </section>

        <section class="process-section">
            <div class="page-copy">
                @if(session('success'))
                    <div class="alert success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.settings.project_categories.update') }}" method="post">
                    @csrf
                    @foreach($categories as $key => $label)
                        <label class="checkbox-inline">
                            <input type="checkbox" name="categories[]" value="{{ $key }}" {{ in_array($key, $selected ?? []) ? 'checked' : '' }} /> {{ $label }}
                        </label>
                    @endforeach

                    <div style="margin-top:18px">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection

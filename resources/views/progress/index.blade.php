@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/progress.css') }}" rel="stylesheet">
@endsection

@section('content')
<div id="fitlife-container" role="application" aria-label="{{ __('progress.title') }}">
    <main>
        <button id="mobile-toggle" aria-controls="sidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <header>
            <div class="header-left">
                <h1>{{ __('progress.title') }}</h1>
                <p class="muted">{{ __('progress.subtitle') }}</p>
            </div>
        </header>

        <section aria-labelledby="photo-form-heading">
            <h3 id="photo-form-heading">{{ __('progress.add_new_photo') }}</h3>
            <div class="photo-card">
                <form action="{{ route('progress.store') }}" method="POST" enctype="multipart/form-data" class="photo-form">
                    @csrf
                    <div class="form-group">
                        <label for="photo">{{ __('progress.photo') }}</label>
                        <input type="file" id="photo" name="photo" required>
                    </div>
                    <div class="form-group">
                        <label for="description">{{ __('progress.description') }}</label>
                        <input type="text" id="description" name="description" placeholder="{{ __('progress.enter_description') }}">
                    </div>
                    <div class="form-group form-group-btn">
                        <label>&nbsp;</label>
                        <button type="submit" class="calculate-btn">{{ __('progress.add_photo') }}</button>
                    </div>
                </form>
            </div>
        </section>

        <section aria-labelledby="photos-heading">
            <h3 id="photos-heading">{{ __('progress.your_progress_photos') }}</h3>
            <div class="gallery-grid">
                @forelse($progressPhotos as $idx => $progress)
                    <div class="photo-item" data-idx="{{ $idx }}" data-img="{{ asset('storage/' . $progress->photo) }}"
                        data-desc="{{ $progress->description ?? '' }}" data-date="{{ $progress->created_at->format('M d, Y') }}">
                        <img src="{{ asset('storage/' . $progress->photo) }}" alt="Progress photo" loading="lazy" class="photo-img">
                        <div class="photo-description">{{ $progress->description ?? __('progress.no_description') }}</div>
                        <div class="photo-date">{{ __('progress.uploaded') }}: {{ $progress->created_at->format('M d, Y H:i') }}</div>
                        <form action="{{ route('progress.update', $progress->id) }}" method="POST" class="photo-form">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label for="description-{{ $progress->id }}">{{ __('progress.update_description') }}</label>
                                <input type="text" id="description-{{ $progress->id }}" name="description"
                                    value="{{ $progress->description }}" placeholder="{{ __('progress.enter_description') }}">
                            </div>
                            <button type="submit" class="update-btn">{{ __('progress.update') }}</button>
                        </form>
                        <form action="{{ route('progress.destroy', $progress->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">{{ __('progress.delete') }}</button>
                        </form>
                    </div>
                @empty
                    <p class="no-data">{{ __('progress.no_photos_yet') }}</p>
                @endforelse
            </div>
        </section>

        <div id="lightbox" role="dialog" aria-hidden="true">
            <div class="lightbox-content">
                <button class="lightbox-close" aria-label="{{ __('progress.close') }}">×</button>
                <img id="lightbox-img" src="" alt="Enlarged Progress Photo">
                <div class="lightbox-info">
                    <p id="lightbox-desc"></p>
                    <p id="lightbox-date"></p>
                </div>
                <button class="lightbox-nav prev" aria-label="{{ __('progress.previous') }}">←</button>
                <button class="lightbox-nav next" aria-label="{{ __('progress.next') }}">→</button>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/progress.js') }}"></script>
@endsection
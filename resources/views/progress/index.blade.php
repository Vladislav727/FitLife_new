@extends('layouts.app')

@section('hide-mobile-nav', '1')
@section('flush-mobile-content', '1')

@section('styles')
<style>
    @media (max-width: 768px) {
    }
</style>
@endsection

@section('content')
<div id="fitlife-container" class="progress-page" role="application" aria-label="{{ __('progress.title') }}">
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

        <div class="progress-top-grid">

            <div class="progress-stats-card">
                <div class="progress-stats-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="M21 15l-5-5L5 21"/>
                    </svg>
                </div>
                <div class="progress-stats-body">
                    <span class="progress-stats-value">{{ $progressPhotos->count() }}</span>
                    <span class="progress-stats-label">{{ __('progress.total_photos') }}</span>
                </div>
                @if($progressPhotos->count() > 0)
                <div class="progress-stats-date">
                    {{ __('progress.uploaded') }}: {{ $progressPhotos->first()->created_at->format('M d, Y') }}
                </div>
                @endif
            </div>

            <section class="progress-upload-card" aria-labelledby="photo-form-heading">
                <h3 id="photo-form-heading">{{ __('progress.add_new_photo') }}</h3>
                <form action="{{ route('progress.store') }}" method="POST" enctype="multipart/form-data" class="progress-upload-form">
                    @csrf
                    <label class="progress-file-drop" for="photo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                        </svg>
                        <span class="progress-file-drop-text">{{ __('progress.choose_file') }}</span>
                        <span class="progress-file-drop-hint" id="file-name-display">JPG, PNG — max 2MB</span>
                        <input type="file" id="photo" name="photo" accept="image/*" required>
                    </label>
                    <div class="progress-upload-bottom">
                        <div class="form-group">
                            <label for="description">{{ __('progress.description') }}</label>
                            <input type="text" id="description" name="description" placeholder="{{ __('progress.enter_description') }}">
                        </div>
                        <button type="submit" class="calculate-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12h14"/></svg>
                            {{ __('progress.add_photo') }}
                        </button>
                    </div>
                </form>
            </section>
        </div>

        <section aria-labelledby="photos-heading">
            <h3 id="photos-heading">{{ __('progress.your_progress_photos') }}</h3>
            <div class="gallery-grid">
                @forelse($progressPhotos as $idx => $progress)
                    <div class="photo-item" data-idx="{{ $idx }}" data-img="{{ asset('storage/' . $progress->photo) }}"
                        data-desc="{{ $progress->description ?? '' }}" data-date="{{ $progress->created_at->format('M d, Y') }}">
                        <div class="photo-img-wrap">
                            <img src="{{ asset('storage/' . $progress->photo) }}" alt="Progress photo" loading="lazy" class="photo-img">
                            <div class="photo-overlay">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="photo-info">
                            <p class="photo-description">{{ $progress->description ?? __('progress.no_description') }}</p>
                            <span class="photo-date">{{ $progress->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="photo-actions">
                            <form action="{{ route('progress.update', $progress->id) }}" method="POST" class="photo-edit-form">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="description" value="{{ $progress->description }}" placeholder="{{ __('progress.enter_description') }}">
                                <button type="submit" class="photo-action-btn photo-action-btn--update" title="{{ __('progress.update') }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                            <form action="{{ route('progress.destroy', $progress->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="photo-action-btn photo-action-btn--delete" title="{{ __('progress.delete') }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="progress-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <path d="M21 15l-5-5L5 21"/>
                        </svg>
                        <p>{{ __('progress.no_photos_yet') }}</p>
                    </div>
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
    <script>
        document.getElementById('photo')?.addEventListener('change', function() {
            const display = document.getElementById('file-name-display');
            if (this.files.length > 0) display.textContent = this.files[0].name;
        });
    </script>
@endsection

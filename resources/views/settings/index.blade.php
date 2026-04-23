@extends('layouts.app')

@section('title', __('settings.title') . ' - FitLife')

@section('hide-mobile-nav', '1')

@section('content')
<div class="settings-page">

    <a href="{{ route('profile.edit') }}" class="back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        {{ __('settings.back_to_profile') }}
    </a>

    <div class="settings-header">
        <h1>{{ __('settings.title') }}</h1>
        <p>{{ __('settings.subtitle') }}</p>
    </div>

    @if(session('success'))
        <div class="settings-alert success">
            <div class="alert-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="settings-layout">

        <aside class="settings-sidebar">
            <div class="sidebar-card">
                <div class="sidebar-section">
                    <div class="section-label">{{ __('settings.general') }}</div>
                    <nav class="sidebar-nav">
                        <a href="{{ route('profile.edit') }}" class="sidebar-link">
                            <div class="link-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <span>{{ __('settings.account') }}</span>
                        </a>
                        <a href="#language" class="sidebar-link active" data-tab="language" onclick="switchSettingsTab('language', this)">
                            <div class="link-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="2" y1="12" x2="22" y2="12"/>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                                </svg>
                            </div>
                            <span>{{ __('settings.language') }}</span>
                        </a>
                        <a href="#appearance" class="sidebar-link" data-tab="appearance" onclick="switchSettingsTab('appearance', this)">
                            <div class="link-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="5"/>
                                    <line x1="12" y1="1" x2="12" y2="3"/>
                                    <line x1="12" y1="21" x2="12" y2="23"/>
                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                    <line x1="1" y1="12" x2="3" y2="12"/>
                                    <line x1="21" y1="12" x2="23" y2="12"/>
                                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                                </svg>
                            </div>
                            <span>{{ __('settings.appearance') }}</span>
                        </a>
                    </nav>
                </div>

                <div class="sidebar-divider"></div>

                <div class="sidebar-section">
                    <div class="section-label">{{ __('settings.security') }}</div>
                    <nav class="sidebar-nav">
                        <a href="{{ route('profile.edit') }}" class="sidebar-link">
                            <div class="link-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </div>
                            <span>{{ __('settings.password') }}</span>
                        </a>
                    </nav>
                </div>
            </div>
        </aside>

        <main class="settings-content">

            <div class="content-card settings-tab" id="tab-language">
                <div class="card-header">
                    <div class="header-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="2" y1="12" x2="22" y2="12"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <div class="header-text">
                        <h2>{{ __('settings.language') }}</h2>
                        <p>{{ __('settings.select_language') }}</p>
                    </div>
                </div>

                <form action="{{ route('settings.language') }}" method="POST" class="language-form">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label class="form-label">{{ __('settings.language') }}</label>
                        <div class="select-wrapper">
                            <select name="language" id="language" class="form-select">
                                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>
                                    🇬🇧 {{ __('settings.english') }}
                                </option>
                                <option value="ru" {{ app()->getLocale() === 'ru' ? 'selected' : '' }}>
                                    🇷🇺 {{ __('settings.russian') }}
                                </option>
                                <option value="lv" {{ app()->getLocale() === 'lv' ? 'selected' : '' }}>
                                    🇱🇻 {{ __('settings.latvian') }}
                                </option>
                            </select>
                            <div class="select-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                            {{ __('settings.save') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="content-card settings-tab" id="tab-appearance" style="display: none;">
                <div class="card-header">
                    <div class="header-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/>
                            <line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1" y1="12" x2="3" y2="12"/>
                            <line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>
                    </div>
                    <div class="header-text">
                        <h2>{{ __('settings.appearance') }}</h2>
                        <p>{{ __('settings.appearance_desc') }}</p>
                    </div>
                </div>

                <div class="appearance-form">
                    <label class="form-label">{{ __('settings.select_theme') }}</label>
                    <div class="theme-cards">
                        <button type="button" class="theme-card" id="theme-dark" onclick="setTheme('dark')">
                            <div class="theme-preview theme-preview-dark">
                                <div class="preview-header"></div>
                                <div class="preview-body">
                                    <div class="preview-sidebar"></div>
                                    <div class="preview-content">
                                        <div class="preview-line"></div>
                                        <div class="preview-line short"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="theme-card-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                                </svg>
                                {{ __('settings.theme_dark') }}
                            </div>
                        </button>
                        <button type="button" class="theme-card" id="theme-light" onclick="setTheme('light')">
                            <div class="theme-preview theme-preview-light">
                                <div class="preview-header"></div>
                                <div class="preview-body">
                                    <div class="preview-sidebar"></div>
                                    <div class="preview-content">
                                        <div class="preview-line"></div>
                                        <div class="preview-line short"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="theme-card-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="5"/>
                                    <line x1="12" y1="1" x2="12" y2="3"/>
                                    <line x1="12" y1="21" x2="12" y2="23"/>
                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                    <line x1="1" y1="12" x2="3" y2="12"/>
                                    <line x1="21" y1="12" x2="23" y2="12"/>
                                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                                </svg>
                                {{ __('settings.theme_light') }}
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/settings.js') }}"></script>
@endsection

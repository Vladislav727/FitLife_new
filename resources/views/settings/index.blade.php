@extends('layouts.app')

@section('title', __('settings.title') . ' - FitLife')

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

<style>
/* Settings Page - e-Shop Design System */
.settings-page {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0;
}

/* Back Link */
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted, #71717a);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 24px;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.back-link:hover {
    color: var(--text-primary, #f4f4f5);
    background: rgba(255, 255, 255, 0.05);
}

.back-link svg {
    transition: transform 0.2s ease;
}

.back-link:hover svg {
    transform: translateX(-3px);
}

/* Header */
.settings-header {
    margin-bottom: 32px;
}

.settings-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, #f4f4f5 0%, #a1a1aa 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.settings-header p {
    color: var(--text-secondary, #a1a1aa);
    margin: 0;
    font-size: 15px;
}

/* Alert */
.settings-alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    animation: slideDown 0.3s ease;
}

.settings-alert.success {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(34, 197, 94, 0.15);
    flex-shrink: 0;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Layout */
.settings-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
}

/* Sidebar */
.settings-sidebar {
    position: sticky;
    top: 24px;
    height: fit-content;
}

.sidebar-card {
    background: linear-gradient(165deg, rgba(255, 255, 255, 0.03) 0%, transparent 50%), #111114;
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 16px;
    padding: 16px;
}

.sidebar-section {
    padding: 8px 0;
}

.section-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--text-muted, #71717a);
    padding: 8px 12px;
    margin-bottom: 4px;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    color: var(--text-secondary, #a1a1aa);
    text-decoration: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
    position: relative;
}

.sidebar-link:hover {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary, #f4f4f5);
}

.sidebar-link.active {
    background: rgba(34, 197, 94, 0.1);
    color: var(--text-primary, #f4f4f5);
}

.sidebar-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 24px;
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    border-radius: 0 4px 4px 0;
}

.link-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.05);
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.sidebar-link:hover .link-icon,
.sidebar-link.active .link-icon {
    background: rgba(34, 197, 94, 0.15);
}

.sidebar-link.active .link-icon svg {
    stroke: #22c55e;
}

.sidebar-divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.06);
    margin: 8px 0;
}

/* Content */
.settings-content {
    min-width: 0;
}

.content-card {
    background: linear-gradient(165deg, rgba(255, 255, 255, 0.03) 0%, transparent 50%), #111114;
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 16px;
    overflow: hidden;
}

.card-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 24px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}

.header-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 8px 24px rgba(34, 197, 94, 0.25);
}

.header-icon svg {
    stroke: #000;
}

.header-text h2 {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 4px 0;
    color: var(--text-primary, #f4f4f5);
}

.header-text p {
    font-size: 14px;
    color: var(--text-muted, #71717a);
    margin: 0;
}

/* Form */
.language-form {
    padding: 24px;
}

.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-secondary, #a1a1aa);
    margin-bottom: 8px;
}

.select-wrapper {
    position: relative;
}

.form-select {
    width: 100%;
    padding: 14px 48px 14px 16px;
    background: rgba(10, 10, 12, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: var(--text-primary, #f4f4f5);
    font-size: 15px;
    font-family: inherit;
    appearance: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.form-select:hover {
    border-color: rgba(255, 255, 255, 0.15);
}

.form-select:focus {
    outline: none;
    border-color: #22c55e;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
}

.form-select option {
    background: #111114;
    color: var(--text-primary, #f4f4f5);
    padding: 12px;
}

.select-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted, #71717a);
    pointer-events: none;
}

.form-actions {
    display: flex;
    justify-content: flex-start;
}

.btn-save {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #000;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 8px 24px rgba(34, 197, 94, 0.25);
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(34, 197, 94, 0.35);
}

.btn-save:active {
    transform: translateY(0);
}

/* Responsive */
@media (max-width: 900px) {
    .settings-layout {
        grid-template-columns: 1fr;
    }

    .settings-sidebar {
        position: static;
    }

    .sidebar-card {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding: 12px;
    }

    .sidebar-section {
        flex-shrink: 0;
        padding: 0;
    }

    .section-label {
        display: none;
    }

    .sidebar-nav {
        flex-direction: row;
        gap: 8px;
    }

    .sidebar-link {
        padding: 10px 16px;
        white-space: nowrap;
    }

    .sidebar-link.active::before {
        display: none;
    }

    .sidebar-link.active {
        border-bottom: 2px solid #22c55e;
        border-radius: 10px 10px 0 0;
    }

    .link-icon {
        width: 32px;
        height: 32px;
    }

    .sidebar-divider {
        width: 1px;
        height: auto;
        margin: 0 8px;
    }
}

@media (max-width: 640px) {
    .settings-page {
        padding: 0;
    }

    .settings-header h1 {
        font-size: 1.5rem;
    }

    .card-header {
        flex-direction: column;
        gap: 12px;
    }

    .header-icon {
        width: 40px;
        height: 40px;
    }

    .content-card {
        border-radius: 12px;
    }

    .language-form {
        padding: 20px;
    }

    .theme-cards {
        grid-template-columns: 1fr;
    }
}

/* Appearance Form */
.appearance-form {
    padding: 24px;
}

.theme-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-top: 12px;
}

.theme-card {
    background: rgba(255, 255, 255, 0.03);
    border: 2px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    gap: 14px;
    text-align: center;
}

.theme-card:hover {
    border-color: rgba(34, 197, 94, 0.3);
    background: rgba(255, 255, 255, 0.05);
}

.theme-card.selected {
    border-color: #22c55e;
    background: rgba(34, 197, 94, 0.08);
    box-shadow: 0 0 24px rgba(34, 197, 94, 0.15);
}

.theme-card.selected .theme-card-label {
    color: #22c55e;
    font-weight: 600;
}

.theme-preview {
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 16 / 10;
    border: 1px solid rgba(255, 255, 255, 0.06);
}

.theme-preview-dark {
    background: #0a0a0a;
}

.theme-preview-dark .preview-header {
    height: 14%;
    background: #141414;
    border-bottom: 1px solid rgba(34, 197, 94, 0.15);
}

.theme-preview-dark .preview-body {
    display: flex;
    height: 86%;
}

.theme-preview-dark .preview-sidebar {
    width: 25%;
    background: #111114;
    border-right: 1px solid rgba(255, 255, 255, 0.06);
}

.theme-preview-dark .preview-content {
    flex: 1;
    padding: 10%;
    display: flex;
    flex-direction: column;
    gap: 8%;
}

.theme-preview-dark .preview-line {
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

.theme-preview-dark .preview-line.short {
    width: 60%;
}

.theme-preview-light {
    background: #f4f6f8;
}

.theme-preview-light .preview-header {
    height: 14%;
    background: #ffffff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}

.theme-preview-light .preview-body {
    display: flex;
    height: 86%;
}

.theme-preview-light .preview-sidebar {
    width: 25%;
    background: #ffffff;
    border-right: 1px solid rgba(0, 0, 0, 0.06);
}

.theme-preview-light .preview-content {
    flex: 1;
    padding: 10%;
    display: flex;
    flex-direction: column;
    gap: 8%;
}

.theme-preview-light .preview-line {
    height: 8px;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 4px;
}

.theme-preview-light .preview-line.short {
    width: 60%;
}

.theme-card-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-secondary, #a1a1aa);
    transition: color 0.2s ease;
}

/* Light Theme Overrides */
[data-theme="light"] .sidebar-card {
    background: linear-gradient(165deg, rgba(0, 0, 0, 0.01) 0%, transparent 50%), #ffffff;
    border-color: rgba(0, 0, 0, 0.08);
}

[data-theme="light"] .sidebar-link:hover {
    background: rgba(0, 0, 0, 0.04);
}

[data-theme="light"] .sidebar-link.active {
    background: rgba(34, 197, 94, 0.08);
}

[data-theme="light"] .sidebar-divider {
    background: rgba(0, 0, 0, 0.06);
}

[data-theme="light"] .content-card {
    background: linear-gradient(165deg, rgba(0, 0, 0, 0.01) 0%, transparent 50%), #ffffff;
    border-color: rgba(0, 0, 0, 0.08);
}

[data-theme="light"] .card-header {
    border-bottom-color: rgba(0, 0, 0, 0.06);
}

[data-theme="light"] .form-select {
    background: rgba(0, 0, 0, 0.02);
    border-color: rgba(0, 0, 0, 0.12);
    color: var(--text-primary);
}

[data-theme="light"] .form-select option {
    background: #ffffff;
    color: var(--text-primary);
}

[data-theme="light"] .settings-header h1 {
    background: linear-gradient(135deg, #111827 0%, #6b7280 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

[data-theme="light"] .theme-card {
    background: rgba(0, 0, 0, 0.02);
    border-color: rgba(0, 0, 0, 0.1);
}

[data-theme="light"] .theme-card:hover {
    background: rgba(0, 0, 0, 0.04);
    border-color: rgba(34, 197, 94, 0.3);
}

[data-theme="light"] .theme-card.selected {
    background: rgba(34, 197, 94, 0.06);
}

[data-theme="light"] .theme-preview {
    border-color: rgba(0, 0, 0, 0.08);
}

[data-theme="light"] .back-link:hover {
    background: rgba(0, 0, 0, 0.04);
}

[data-theme="light"] .settings-alert.success {
    background: rgba(34, 197, 94, 0.08);
    border-color: rgba(34, 197, 94, 0.15);
}
</style>

<script>
function switchSettingsTab(tab, el) {
    document.querySelectorAll('.settings-tab').forEach(function(p) { p.style.display = 'none'; });
    document.getElementById('tab-' + tab).style.display = '';
    document.querySelectorAll('.sidebar-link[data-tab]').forEach(function(l) { l.classList.remove('active'); });
    if (el) el.classList.add('active');
}

function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('fitlife-theme', theme);
    updateThemeCards(theme);
}

function updateThemeCards(theme) {
    document.querySelectorAll('.theme-card').forEach(function(c) { c.classList.remove('selected'); });
    var card = document.getElementById('theme-' + theme);
    if (card) card.classList.add('selected');
}

document.addEventListener('DOMContentLoaded', function() {
    var current = localStorage.getItem('fitlife-theme') || 'dark';
    updateThemeCards(current);
});
</script>
@endsection

@extends('layouts.app')

@section('title', __('profile.edit_profile') . ' - FitLife')

@section('hide-mobile-nav', '1')

@section('content')
<div class="profile-edit-page" role="application" aria-label="FitLife Profile Settings">

    <a href="{{ route('profile.show', $user->username) }}" class="pe-back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        {{ __('profile.back_to_profile') }}
    </a>

    <div class="pe-header">
        <div class="pe-header__icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </div>
        <div class="pe-header__text">
            <h1>{{ __('profile.edit_profile') }}</h1>
            <p>{{ __('profile.manage_your_profile') }}</p>
        </div>
    </div>

    @if (session('status'))
        <div class="pe-alert pe-alert--success" role="alert">
            <div class="pe-alert__icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="pe-alert pe-alert--error" role="alert">
            <div class="pe-alert__icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
            </div>
            <span>{{ __('profile.fix_errors') }}</span>
        </div>
    @endif

    <div class="pe-layout">

        <aside class="pe-sidebar">
            <div class="pe-sidebar__card">

                <div class="pe-sidebar__user">
                    <img class="pe-sidebar__avatar"
                         src="{{ $user->avatar ? asset('storage/' . $user->avatar) . '?t=' . time() : asset('storage/default-avatar/default-avatar.avif') }}"
                         alt="{{ $user->name }}">
                    <div class="pe-sidebar__info">
                        <span class="pe-sidebar__name">{{ $user->name }}</span>
                        <span class="pe-sidebar__username">{{ '@' . $user->username }}</span>
                    </div>
                </div>

                <div class="pe-sidebar__divider"></div>

                <nav class="pe-sidebar__nav">
                    <a href="#section-avatar" class="pe-sidebar__link active" data-section="section-avatar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <span>{{ __('profile.profile_picture') }}</span>
                    </a>
                    <a href="#section-info" class="pe-sidebar__link" data-section="section-info">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>{{ __('profile.basic_information') }}</span>
                    </a>
                    <a href="#section-password" class="pe-sidebar__link" data-section="section-password">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <span>{{ __('profile.password_security') }}</span>
                    </a>
                    <a href="#section-danger" class="pe-sidebar__link pe-sidebar__link--danger" data-section="section-danger">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <span>{{ __('profile.danger_zone') }}</span>
                    </a>
                </nav>
            </div>
        </aside>

        <main class="pe-content">

            <section id="section-avatar" class="pe-card" aria-labelledby="avatar-heading">
                <div class="pe-card__header">
                    <div class="pe-card__header-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                    <div>
                        <h2 id="avatar-heading">{{ __('profile.profile_picture') }}</h2>
                        <p>{{ __('profile.customize_appearance') }}</p>
                    </div>
                </div>

                <div class="pe-banner-preview">
                    <div class="pe-banner-preview__bg" style="background-image: url('{{ $user->banner ? asset("storage/{$user->banner}") . "?t=" . time() : asset('storage/banner/default-banner.jpg') }}')">
                        <div class="pe-banner-preview__overlay"></div>
                        <div class="pe-banner-preview__avatar-wrap">
                            <img class="pe-banner-preview__avatar"
                                 src="{{ $user->avatar ? asset("storage/{$user->avatar}") . "?t=" . time() : asset('storage/default-avatar/default-avatar.avif') }}"
                                 alt="{{ __('profile.avatar') }}">
                            <label for="avatar" class="pe-banner-preview__avatar-btn" title="{{ __('profile.change_avatar') }}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                    <circle cx="12" cy="13" r="4"/>
                                </svg>
                            </label>
                        </div>
                        <label for="banner" class="pe-banner-preview__change-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                            {{ __('profile.change_banner') }}
                        </label>
                    </div>
                </div>

                <div class="pe-card__hint">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    {{ __('profile.image_hint') }}
                </div>
            </section>

            <section id="section-info" class="pe-card" aria-labelledby="info-heading">
                <div class="pe-card__header">
                    <div class="pe-card__header-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 id="info-heading">{{ __('profile.basic_information') }}</h2>
                        <p>{{ __('profile.basic_info_desc') }}</p>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="pe-form">
                    @csrf
                    @method('PATCH')

                    <input type="file" id="banner" name="banner" accept="image/*" style="display: none;">
                    <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;">

                    <div class="pe-form__grid">

                        <div class="pe-form__group">
                            <label for="name" class="pe-form__label">
                                {{ __('profile.name') }} <span class="pe-form__required">*</span>
                            </label>
                            <div class="pe-form__input-wrap">
                                <svg class="pe-form__input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <input class="pe-form__input" type="text" id="name" name="name"
                                       value="{{ old('name', $user->name) }}" required
                                       aria-required="true" aria-describedby="name-error"
                                       placeholder="{{ __('profile.enter_name') }}">
                            </div>
                            @error('name')
                                <p id="name-error" class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pe-form__group">
                            <label for="username" class="pe-form__label">
                                {{ __('profile.username') }} <span class="pe-form__required">*</span>
                            </label>
                            <div class="pe-form__input-wrap">
                                <span class="pe-form__input-prefix">@</span>
                                <input class="pe-form__input pe-form__input--prefixed" type="text" id="username" name="username"
                                       value="{{ old('username', $user->username) }}" required
                                       aria-required="true" aria-describedby="username-error"
                                       placeholder="username">
                            </div>
                            @error('username')
                                <p id="username-error" class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pe-form__group">
                            <label for="email" class="pe-form__label">
                                {{ __('profile.email') }} <span class="pe-form__required">*</span>
                            </label>
                            <div class="pe-form__input-wrap">
                                <svg class="pe-form__input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <input class="pe-form__input" type="email" id="email" name="email"
                                       value="{{ old('email', $user->email) }}" required
                                       aria-required="true" aria-describedby="email-error"
                                       placeholder="email@example.com">
                            </div>
                            @error('email')
                                <p id="email-error" class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pe-form__group pe-form__group--full">
                            <label for="full_name" class="pe-form__label">Full Name</label>
                            <div class="pe-form__input-wrap">
                                <svg class="pe-form__input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <input class="pe-form__input" type="text" id="full_name" name="full_name"
                                       value="{{ old('full_name', $user->full_name) }}"
                                       placeholder="Your full name">
                            </div>
                            @error('full_name')
                                <p class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pe-form__group">
                            <label for="age" class="pe-form__label">Age</label>
                            <div class="pe-form__input-wrap">
                                <svg class="pe-form__input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                <input class="pe-form__input" type="number" id="age" name="age"
                                       value="{{ old('age', $user->age) }}"
                                       min="1" max="120" placeholder="Years">
                            </div>
                            @error('age')
                                <p class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pe-form__group">
                            <label for="gender" class="pe-form__label">Gender</label>
                            <div class="pe-form__input-wrap pe-form__input-wrap--select">
                                <svg class="pe-form__input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path d="M12 12v8M8 16h8"/>
                                </svg>
                                <select class="pe-form__input pe-form__select" id="gender" name="gender">
                                    <option value="">Select gender</option>
                                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            @error('gender')
                                <p class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pe-form__group">
                            <label for="height" class="pe-form__label">Height (cm)</label>
                            <div class="pe-form__input-wrap">
                                <svg class="pe-form__input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                                </svg>
                                <input class="pe-form__input" type="number" id="height" name="height"
                                       value="{{ old('height', $user->height) }}"
                                       step="0.1" min="50" max="300" placeholder="cm">
                            </div>
                            @error('height')
                                <p class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pe-form__group">
                            <label for="weight" class="pe-form__label">Weight (kg)</label>
                            <div class="pe-form__input-wrap">
                                <svg class="pe-form__input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                    <line x1="3" y1="6" x2="21" y2="6"/>
                                    <path d="M16 10a4 4 0 0 1-8 0"/>
                                </svg>
                                <input class="pe-form__input" type="number" id="weight" name="weight"
                                       value="{{ old('weight', $user->weight) }}"
                                       step="0.1" min="20" max="500" placeholder="kg">
                            </div>
                            @error('weight')
                                <p class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pe-form__group pe-form__group--full">
                            <label for="bio" class="pe-form__label">{{ __('profile.bio') }}</label>
                            <textarea class="pe-form__textarea" id="bio" name="bio" rows="4"
                                      placeholder="{{ __('profile.tell_us_about_yourself') }}">{{ old('bio', $user->bio ?? '') }}</textarea>
                            @error('bio')
                                <p class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pe-form__actions">
                        <a href="{{ route('profile.show', $user->username) }}" class="pe-btn pe-btn--secondary">
                            {{ __('profile.cancel') }}
                        </a>
                        <button type="submit" class="pe-btn pe-btn--primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            {{ __('profile.save_changes') }}
                        </button>
                    </div>
                </form>
            </section>

            <section id="section-password" class="pe-card" aria-labelledby="password-heading">
                <div class="pe-card__header">
                    <div class="pe-card__header-icon pe-card__header-icon--accent">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 id="password-heading">{{ __('profile.password_security') }}</h2>
                        <p>{{ __('profile.password_desc') }}</p>
                    </div>
                </div>

                <form action="{{ route('password.update') }}" method="POST" class="pe-form">
                    @csrf
                    @method('PUT')

                    <div class="pe-form__stack">

                        <div class="pe-form__group">
                            <label for="current_password" class="pe-form__label">
                                {{ __('profile.current_password') }} <span class="pe-form__required">*</span>
                            </label>
                            <div class="pe-form__input-wrap">
                                <svg class="pe-form__input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <input class="pe-form__input" type="password" id="current_password" name="current_password"
                                       required aria-required="true" aria-describedby="current-password-error"
                                       placeholder="{{ __('profile.enter_current_password') }}">
                            </div>
                            @error('current_password')
                                <p id="current-password-error" class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pe-form__group">
                            <label for="password" class="pe-form__label">
                                {{ __('profile.new_password') }} <span class="pe-form__required">*</span>
                            </label>
                            <div class="pe-form__input-wrap">
                                <svg class="pe-form__input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                                    <path d="M12 6v6l4 2"/>
                                </svg>
                                <input class="pe-form__input" type="password" id="password" name="password"
                                       required aria-required="true" aria-describedby="password-error"
                                       placeholder="{{ __('profile.enter_new_password') }}">
                            </div>
                            @error('password')
                                <p id="password-error" class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pe-form__group">
                            <label for="password_confirmation" class="pe-form__label">
                                {{ __('profile.confirm_password') }} <span class="pe-form__required">*</span>
                            </label>
                            <div class="pe-form__input-wrap">
                                <svg class="pe-form__input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 6L9 17l-5-5"/>
                                </svg>
                                <input class="pe-form__input" type="password" id="password_confirmation" name="password_confirmation"
                                       required aria-required="true" aria-describedby="password-confirm-error"
                                       placeholder="{{ __('profile.confirm_new_password') }}">
                            </div>
                            @error('password_confirmation')
                                <p id="password-confirm-error" class="pe-form__error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pe-form__actions">
                        <button type="submit" class="pe-btn pe-btn--primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            {{ __('profile.update_password') }}
                        </button>
                    </div>
                </form>
            </section>

            <section id="section-danger" class="pe-card pe-card--danger" aria-labelledby="danger-heading">
                <div class="pe-card__header">
                    <div class="pe-card__header-icon pe-card__header-icon--danger">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div>
                        <h2 id="danger-heading">{{ __('profile.danger_zone') }}</h2>
                        <p>{{ __('profile.danger_zone_desc') }}</p>
                    </div>
                </div>

                <div class="pe-danger-box">
                    <div class="pe-danger-box__info">
                        <h3>{{ __('profile.delete_account_title') }}</h3>
                        <p>
                            {{ __('profile.delete_account_warning') }}
                            <strong>{{ __('profile.delete_account_irreversible') }}</strong>
                        </p>
                    </div>

                    <form action="{{ route('profile.destroy') }}" method="POST"
                          data-confirm="{{ __('profile.delete_confirmation') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="pe-btn pe-btn--danger">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/>
                                <line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                            </svg>
                            {{ __('profile.delete_account_forever') }}
                        </button>
                    </form>
                </div>
            </section>

        </main>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/profile.js') }}"></script>
@endsection

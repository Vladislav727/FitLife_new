@extends('layouts.app')

@section('content')
<div id="fitlife-container" class="biography-page" role="application" aria-label="FitLife Biography Settings">
    <main>
        <button id="mobile-toggle" aria-controls="sidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <header>
            <div class="header-left">
                <h1><span>{{ __('profile.fitlife_biography') }}</span></h1>
                <p class="muted">{{ __('profile.update_personal_info') }}</p>
            </div>
        </header>

        @if(session('success'))
            <div class="success-msg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @php $bio = Auth::user()->biography ?? new \App\Models\Biography(); @endphp

        <section aria-labelledby="biography-settings-heading">
            <div class="biography-card">
                <form action="{{ route('biography.update') }}" method="POST" class="biography-form">
                    @csrf
                    @method('PATCH')

                    <div class="bio-section">
                        <div class="bio-section-header">
                            <div class="bio-section-icon bio-section-icon--personal">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
                            </div>
                            <div>
                                <h3 id="biography-settings-heading">{{ __('profile.personal_info') }}</h3>
                                <p class="bio-section-desc">{{ __('profile.basic_info_desc') }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="full_name">{{ __('profile.full_name') }}</label>
                            <input type="text" id="full_name" name="full_name"
                                value="{{ old('full_name', $bio->full_name ?? Auth::user()->name) }}"
                                placeholder="{{ __('profile.enter_name') }}">
                        </div>

                        <div class="bio-row">
                            <div class="form-group">
                                <label for="age">{{ __('profile.age') }}</label>
                                <input type="number" id="age" name="age" min="1" max="150"
                                    value="{{ old('age', $bio->age ?? '') }}" placeholder="—">
                            </div>
                            <div class="form-group">
                                <label for="gender">{{ __('profile.gender') }}</label>
                                <select id="gender" name="gender">
                                    <option value="" {{ old('gender', $bio->gender ?? '') == '' ? 'selected' : '' }}>{{ __('profile.select_gender') }}</option>
                                    <option value="male" {{ old('gender', $bio->gender ?? '') == 'male' ? 'selected' : '' }}>{{ __('profile.male') }}</option>
                                    <option value="female" {{ old('gender', $bio->gender ?? '') == 'female' ? 'selected' : '' }}>{{ __('profile.female') }}</option>
                                    <option value="other" {{ old('gender', $bio->gender ?? '') == 'other' ? 'selected' : '' }}>{{ __('profile.other') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bio-section">
                        <div class="bio-section-header">
                            <div class="bio-section-icon bio-section-icon--physical">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                            </div>
                            <div>
                                <h3>{{ __('profile.physical_data') }}</h3>
                                <p class="bio-section-desc">{{ __('profile.update_personal_info') }}</p>
                            </div>
                        </div>

                        <div class="bio-row">
                            <div class="form-group">
                                <label for="height">{{ __('profile.height_cm') }}</label>
                                <div class="input-with-suffix">
                                    <input type="number" step="0.01" id="height" name="height"
                                        value="{{ old('height', $bio->height ?? '') }}" placeholder="—">
                                    <span class="input-suffix">{{ __('profile.cm') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="weight">{{ __('profile.weight_kg') }}</label>
                                <div class="input-with-suffix">
                                    <input type="number" step="0.01" id="weight" name="weight"
                                        value="{{ old('weight', $bio->weight ?? '') }}" placeholder="—">
                                    <span class="input-suffix">{{ __('profile.kg') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="save-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                            <path d="M17 21v-8H7v8" />
                            <path d="M7 3v5h8" />
                        </svg>
                        {{ __('profile.save_biography') }}
                    </button>
                </form>
            </div>
        </section>
    </main>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/biography.js') }}"></script>
@endsection

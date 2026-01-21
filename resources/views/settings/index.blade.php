@extends('layouts.app')

@section('title', __('settings.title') . ' - FitLife')

@section('content')
<div class="settings-container">
    <div class="settings-header">
        <h1>{{ __('settings.title') }}</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="settings-section">
        <h2>{{ __('settings.language') }}</h2>
        <p class="section-description">{{ __('settings.select_language') }}</p>

        <form action="{{ route('settings.language') }}" method="POST" class="language-form">
            @csrf
            @method('PATCH')

            <div class="language-options">
                <label class="language-option {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                    <input type="radio" name="language" value="en" {{ app()->getLocale() === 'en' ? 'checked' : '' }}>
                    <div class="language-card">
                        <span class="flag">🇬🇧</span>
                        <span class="language-name">{{ __('settings.english') }}</span>
                    </div>
                </label>

                <label class="language-option {{ app()->getLocale() === 'ru' ? 'active' : '' }}">
                    <input type="radio" name="language" value="ru" {{ app()->getLocale() === 'ru' ? 'checked' : '' }}>
                    <div class="language-card">
                        <span class="flag">🇷🇺</span>
                        <span class="language-name">{{ __('settings.russian') }}</span>
                    </div>
                </label>

                <label class="language-option {{ app()->getLocale() === 'lv' ? 'active' : '' }}">
                    <input type="radio" name="language" value="lv" {{ app()->getLocale() === 'lv' ? 'checked' : '' }}>
                    <div class="language-card">
                        <span class="flag">🇱🇻</span>
                        <span class="language-name">{{ __('settings.latvian') }}</span>
                    </div>
                </label>
            </div>

            <button type="submit" class="btn-save">
                {{ __('settings.save') }}
            </button>
        </form>
    </div>
</div>

<style>
.settings-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.settings-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary, #fff);
    margin-bottom: 2rem;
}

.settings-section {
    background: var(--card-bg, #1a1a2e);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
}

.settings-section h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary, #fff);
    margin-bottom: 0.5rem;
}

.section-description {
    color: var(--text-secondary, #888);
    margin-bottom: 1.5rem;
}

.language-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.language-options {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.language-option {
    cursor: pointer;
}

.language-option input[type="radio"] {
    display: none;
}

.language-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1.5rem 2rem;
    background: var(--bg-secondary, #252542);
    border: 2px solid transparent;
    border-radius: 12px;
    transition: all 0.2s ease;
    min-width: 120px;
}

.language-option:hover .language-card {
    border-color: var(--accent-color, #6366f1);
    transform: translateY(-2px);
}

.language-option.active .language-card,
.language-option input:checked + .language-card {
    border-color: var(--accent-color, #6366f1);
    background: rgba(99, 102, 241, 0.1);
}

.flag {
    font-size: 2.5rem;
}

.language-name {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-primary, #fff);
}

.btn-save {
    align-self: flex-start;
    padding: 0.75rem 2rem;
    background: var(--accent-color, #6366f1);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-save:hover {
    background: var(--accent-hover, #5558e3);
    transform: translateY(-1px);
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-success {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #22c55e;
}

@media (max-width: 640px) {
    .settings-container {
        padding: 1rem;
    }

    .language-options {
        flex-direction: column;
    }

    .language-card {
        flex-direction: row;
        justify-content: flex-start;
        width: 100%;
        padding: 1rem 1.5rem;
    }

    .flag {
        font-size: 1.5rem;
    }
}
</style>

<script>
document.querySelectorAll('.language-option input').forEach(input => {
    input.addEventListener('change', function() {
        document.querySelectorAll('.language-option').forEach(opt => opt.classList.remove('active'));
        this.closest('.language-option').classList.add('active');
    });
});
</script>
@endsection

@extends('layouts.app')
@section('title', __('messages.create_group'))

@section('content')
<div class="msg-page">
    <div class="msg-header">
        <a href="{{ route('groups.index') }}" class="chat-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M19 12H5m0 0l7 7m-7-7l7-7"/></svg>
        </a>
        <h1 class="msg-title">{{ __('messages.create_group') }}</h1>
    </div>

    <form action="{{ route('groups.store') }}" method="POST" class="msg-form" enctype="multipart/form-data">
        @csrf
        <div class="msg-form__group">
            <label class="msg-form__label">{{ __('messages.group_avatar') }}</label>
            <div class="msg-form__avatar-upload">
                <div class="msg-form__avatar-preview" id="avatarPreview">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="32" height="32"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </div>
                <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none" onchange="previewAvatar(this)">
                <button type="button" class="msg-btn-invite" onclick="document.getElementById('avatarInput').click()">{{ __('messages.choose_photo') }}</button>
            </div>
            @error('avatar') <span class="msg-form__error">{{ $message }}</span> @enderror
        </div>

        <div class="msg-form__group">
            <label class="msg-form__label">{{ __('messages.group_name') }}</label>
            <input type="text" name="name" value="{{ old('name') }}" required maxlength="100" class="msg-form__input" placeholder="{{ __('messages.group_name_placeholder') }}">
            @error('name') <span class="msg-form__error">{{ $message }}</span> @enderror
        </div>

        <div class="msg-form__group">
            <label class="msg-form__label">{{ __('messages.group_description') }}</label>
            <textarea name="description" maxlength="500" rows="3" class="msg-form__input" placeholder="{{ __('messages.group_description_placeholder') }}">{{ old('description') }}</textarea>
            @error('description') <span class="msg-form__error">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="msg-form__submit">{{ __('messages.create_group') }}</button>
    </form>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            preview.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

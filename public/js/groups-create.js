document.addEventListener('DOMContentLoaded', () => {
    const avatarInput = document.getElementById('avatarInput');
    const avatarTrigger = document.getElementById('avatarTrigger');
    const avatarPreview = document.getElementById('avatarPreview');

    avatarTrigger?.addEventListener('click', () => {
        avatarInput?.click();
    });

    avatarInput?.addEventListener('change', () => {
        if (!avatarInput.files || !avatarInput.files[0] || !avatarPreview) {
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            avatarPreview.innerHTML = '<img src="' + event.target.result + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%">';
        };
        reader.readAsDataURL(avatarInput.files[0]);
    });
});
document.addEventListener("DOMContentLoaded", () => {

    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.querySelector('.pe-banner-preview__avatar');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                avatarPreview.src = URL.createObjectURL(file);
            }
        });
    }

    const bannerInput = document.getElementById('banner');
    const bannerBg = document.querySelector('.pe-banner-preview__bg');

    if (bannerInput && bannerBg) {
        bannerInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    bannerBg.style.backgroundImage = `url(${ev.target.result})`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

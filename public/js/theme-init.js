(function () {
    var theme = localStorage.getItem('fitlife-theme') || 'dark';
    document.documentElement.setAttribute('data-theme', theme);
})();
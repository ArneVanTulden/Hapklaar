import Alpine from 'alpinejs';
window.Alpine = Alpine;
// Livewire registers its Alpine plugins and calls Alpine.start() via @livewireScripts

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js');
    });
}

import './bootstrap';

const updateScrollPosition = (elementId) => {
    const el = document.getElementById(elementId);
    if (el) {
        setTimeout(() => {
            el.scrollTop = el.scrollHeight;
        }, 100);

        el.scroll({
            top: el.scrollHeight,
            behavior: 'smooth'
        });
    }
};

updateScrollPosition('messages');

Livewire.on('messageSent', () => {
    updateScrollPosition('messages');
});

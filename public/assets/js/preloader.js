document.addEventListener('DOMContentLoaded', function() {
    const preloader = document.getElementById('preloader');
    const progressBar = document.querySelector('.progress-bar');
    
    // Simulate progress (you can remove this if using real loading events)
    let progress = 0;
    const interval = setInterval(() => {
        progress += Math.random() * 10;
        if (progress >= 100) {
            progress = 100;
            clearInterval(interval);
            setTimeout(() => {
                preloader.classList.add('hidden');
            }, 500);
        }
        progressBar.style.width = `${progress}%`;
    }, 100);
    
    // Show preloader before page unload
    window.addEventListener('beforeunload', function() {
        preloader.classList.remove('hidden');
        progressBar.style.width = '0%';
    });
});
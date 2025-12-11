<style>
    /* Preloader Modern */
    .preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        transition: opacity 0.5s ease-out, visibility 0.5s;
    }
    
    .preloader.hidden {
        opacity: 0;
        visibility: hidden;
    }
    
    .preloader-inner {
        text-align: center;
        max-width: 300px;
        position: relative;
    }
    
    .preloader-logo {
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .preloader-logo img {
        height: 80px;
        animation: logoPulse 1.5s ease-in-out infinite;
        filter: drop-shadow(0 5px 15px rgba(0,0,0,0.1));
    }
    
    .preloader-logo:after {
        content: '';
        position: absolute;
        width: 100%;
        height: 10px;
        bottom: -15px;
        left: 0;
        background: radial-gradient(ellipse at center, rgba(0,0,0,0.15) 0%, transparent 80%);
        border-radius: 50%;
        filter: blur(2px);
        animation: shadowScale 1.5s ease-in-out infinite;
    }
    
    .progress-container {
        width: 100%;
        height: 6px;
        background: rgba(42, 157, 143, 0.2);
        border-radius: 10px;
        overflow: hidden;
        margin: 1.5rem 0;
        position: relative;
    }
    
    .progress-bar {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        border-radius: 10px;
        transition: width 0.8s cubic-bezier(0.65, 0, 0.35, 1);
        box-shadow: 0 0 10px rgba(42, 157, 143, 0.5);
    }
    
    .preloader-text {
        font-size: 14px;
        color: var(--secondary);
        font-weight: 500;
        margin-top: 1rem;
        position: relative;
        display: inline-block;
    }
    
    .preloader-text:after {
        content: '...';
        animation: dots 1.5s steps(4, end) infinite;
    }
    
    .particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
    }
    
    .particle {
        position: absolute;
        background: var(--primary);
        border-radius: 50%;
        opacity: 0.6;
        animation: float 15s infinite linear;
    }
    
    @keyframes logoPulse {
        0% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.05) rotate(2deg); }
        100% { transform: scale(1) rotate(0deg); }
    }
    
    @keyframes shadowScale {
        0% { transform: scale(0.9); opacity: 0.7; }
        50% { transform: scale(1.1); opacity: 0.9; }
        100% { transform: scale(0.9); opacity: 0.7; }
    }
    
    @keyframes loading {
        0% { width: 0%; }
        50% { width: 70%; }
        100% { width: 100%; }
    }
    
    @keyframes dots {
        0%, 20% { content: '.'; }
        40% { content: '..'; }
        60%, 100% { content: '...'; }
    }
    
    @keyframes float {
        0% {
            transform: translateY(0) translateX(0) rotate(0deg);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            transform: translateY(-100vh) translateX(100vw) rotate(360deg);
            opacity: 0;
        }
    }
</style>

<!-- Preloader Modern -->
<div id="preloader" class="preloader">
    <div class="particles" id="particles"></div>
    <div class="preloader-inner">
        <div class="preloader-logo">
            <img src="{{ asset('assets/img/logo-smp.png') }}" alt="SMP Baabussalaam">
        </div>
        <div class="progress-container">
            <div class="progress-bar" id="progress-bar"></div>
        </div>
        <div class="preloader-text">Memuat</div>
    </div>
</div>

@push('scripts')
<script>
    // Preloader Modern
    document.addEventListener('DOMContentLoaded', function() {
        const preloader = document.getElementById('preloader');
        const progressBar = document.getElementById('progress-bar');
        const particlesContainer = document.getElementById('particles');
        
        // Simulasi progress loading
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                
                // Sembunyikan preloader setelah loading selesai
                setTimeout(() => {
                    preloader.classList.add('hidden');
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 500);
                }, 300);
            }
            progressBar.style.width = progress + '%';
        }, 300);
        
        // Buat partikel background
        createParticles();
        
        function createParticles() {
            const colors = ['#2a9d8f', '#264653', '#e9c46a', '#f4a261', '#e76f51'];
            const count = 20;
            
            for (let i = 0; i < count; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Ukuran acak
                const size = Math.random() * 15 + 5;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Warna acak
                const color = colors[Math.floor(Math.random() * colors.length)];
                particle.style.background = color;
                
                // Posisi acak
                const posX = Math.random() * 100;
                const posY = Math.random() * 100;
                particle.style.left = `${posX}%`;
                particle.style.top = `${posY}%`;
                
                // Animasi dengan durasi acak
                const duration = Math.random() * 20 + 10;
                particle.style.animationDuration = `${duration}s`;
                particle.style.animationDelay = `${Math.random() * 5}s`;
                
                particlesContainer.appendChild(particle);
            }
        }
    });
</script>
@endpush

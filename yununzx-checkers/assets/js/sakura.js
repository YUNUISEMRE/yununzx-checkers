document.addEventListener('DOMContentLoaded', function() {
    // Sakura yaprakları
    const sakuraCount = 25;
    for (let i = 0; i < sakuraCount; i++) {
        const petal = document.createElement('div');
        petal.className = 'sakura-petal';
        petal.style.left = Math.random() * 100 + '%';
        petal.style.width = (10 + Math.random() * 20) + 'px';
        petal.style.height = (10 + Math.random() * 20) + 'px';
        petal.style.animationDuration = (8 + Math.random() * 14) + 's';
        petal.style.animationDelay = (Math.random() * 15) + 's';
        petal.style.opacity = 0.15 + Math.random() * 0.25;
        document.querySelector('.sakura-bg').appendChild(petal);
    }

    // Tema değiştir
    const themeBtn = document.getElementById('themeToggle');
    if (themeBtn) {
        themeBtn.addEventListener('click', function() {
            document.body.classList.toggle('light');
            localStorage.setItem('theme', document.body.classList.contains('light') ? 'light' : 'dark');
        });
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light') document.body.classList.add('light');
    }

    // Hamburger
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    if (hamburger && sidebar) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            sidebar.classList.toggle('open');
        });
    }

    // Mobile nav
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('open');
        });
    }

    // Sidebar dışına tıklayınca kapat
    document.addEventListener('click', function(e) {
        if (sidebar && sidebar.classList.contains('open')) {
            if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
                sidebar.classList.remove('open');
                hamburger.classList.remove('active');
            }
        }
    });
});

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 16px 24px;
        border-radius: 16px;
        background: ${type === 'success' ? '#ffb7c5' : '#ef4444'};
        color: #1a0f1a;
        font-weight: 700;
        z-index: 9999;
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: 'Quicksand', sans-serif;
    `;
    toast.textContent = '🌸 ' + message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
        setTimeout(() => toast.remove(), 500);
    }, 3500);
}

// SlideUp/SlideDown animasyonları için style ekle
const styleSheet = document.createElement('style');
styleSheet.textContent = `
    @keyframes slideUp {
        from { transform: translateY(40px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes slideDown {
        from { transform: translateY(0); opacity: 1; }
        to { transform: translateY(40px); opacity: 0; }
    }
`;
document.head.appendChild(styleSheet);
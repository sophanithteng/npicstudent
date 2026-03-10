document.addEventListener('DOMContentLoaded', () => {
    // 1. Theme Toggle Elements
    const toggleBtn = document.getElementById('theme-toggle');
    const htmlTag = document.documentElement;

    // 2. Load Content if on Dashboard
    const container = document.getElementById('loader-root');
    if (container) {
        loadRealContent();
    }

    // 3. Theme Toggle Logic
    if (toggleBtn) {
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        htmlTag.setAttribute('data-bs-theme', savedTheme);
        if (savedTheme === 'dark') document.body.classList.add('dark-mode');

        toggleBtn.addEventListener('click', () => {
            const newTheme = htmlTag.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';

            htmlTag.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            document.body.classList.toggle('dark-mode', newTheme === 'dark');
        });
    }
});

// Keep functions outside the event listener
function initSkeletonLoader(container, count = 5) {
    if (!container) return;
    container.innerHTML = '';
    for (let i = 0; i < count; i++) {
        const card = document.createElement('div');
        card.className = 'skeleton-card';
        ['sk-image', 'sk-title', 'sk-text', 'sk-text-short'].forEach(type => {
            const el = document.createElement('div');
            el.classList.add('skeleton-pulse', type);
            card.appendChild(el);
        });
        container.appendChild(card);
    }
}

async function loadRealContent() {
    const container = document.getElementById('loader-root');
    if (!container) return;

    initSkeletonLoader(container, 5);
    await new Promise(r => setTimeout(r, 2000));

    container.style.opacity = '0';
    await new Promise(r => setTimeout(r, 400));

    container.innerHTML = '';
    for (let i = 1; i <= 5; i++) {
        const card = document.createElement('div');
        card.className = 'skeleton-card';
        card.innerHTML = `
            <img src="https://picsum.photos/seed/${i + 20}/400/300" alt="Product">
            <h3>Product Item ${i}</h3>
            <p>High quality product for your POS inventory.</p>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:10px;">
                <span style="font-weight:bold; color:#28a745;">$${(i * 12.5).toFixed(2)}</span>
                <button class="btn btn-sm btn-primary">Add</button>
            </div>`;
        container.appendChild(card);
    }
    container.style.opacity = '1';
}
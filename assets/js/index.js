document.addEventListener('DOMContentLoaded', () => {
    // 1. Check if we are on the dashboard
    const carouselRoot = document.getElementById('carousel-root');
    const loaderRoot = document.getElementById('loader-root');

    if (carouselRoot || loaderRoot) {
        loadDashboardContent();
    }

    // 2. Theme Toggle (Safe check)
    const toggleBtn = document.getElementById('theme-toggle');
    if (toggleBtn) {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);

        toggleBtn.addEventListener('click', () => {
            const newTheme = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            document.body.classList.toggle('dark-mode', newTheme === 'dark');
        });
    }
});

async function loadDashboardContent() {
    const carouselContainer = document.getElementById('carousel-root');
    const productContainer = document.getElementById('loader-root');

    // 1. Show Skeletons
    if (carouselContainer) {
        carouselContainer.innerHTML = `
        <div class="sk-carousel" style="height: 500px;"> 
            <div class="skeleton-pulse sk-carousel-title" style="background: #444; height: 35px; width: 40%; margin-top: 350px; margin-left: 40px;"></div>
            <div class="skeleton-pulse sk-carousel-text" style="background: #333; height: 20px; width: 60%; margin-left: 40px; margin-top: 15px;"></div>
        </div>`;
    }

    if (productContainer) {
        productContainer.innerHTML = '';
        for (let i = 0; i < 6; i++) {
            productContainer.innerHTML += `
                <div class="skeleton-card">
                    <div class="skeleton-pulse sk-image"></div>
                    <div class="skeleton-pulse sk-title"></div>
                    <div class="skeleton-pulse sk-text"></div>
                </div>`;
        }
    }

    await new Promise(r => setTimeout(r, 2000));

    // 2. Inject Content
    if (carouselContainer) {
        carouselContainer.innerHTML = `
        <div class="carousel-container shadow-lg">
            <div class="carousel-track">
                <div class="slide">
                    <img src="https://picsum.photos/id/20/1000/350" alt="Chemistry">
                    <div class="slide-content"><h2>Chemistry Lab</h2><p>Managing inventory.</p></div>
                </div>
                <div class="slide">
                    <img src="https://picsum.photos/id/48/1000/350" alt="POS">
                    <div class="slide-content"><h2>POS Analytics</h2><p>Sales tracking.</p></div>
                </div>
            </div>
            <button class="nav-btn prev">❮</button>
            <button class="nav-btn next">❯</button>
        </div>`;

        if (typeof initCarouselLogic === 'function') {
            initCarouselLogic();
        }
    }

    if (productContainer) {
        const categories = [
            { img: "assets/images/electrical_icon.png", text: "វិស្វកម្មអគ្គិសនី", link: "?page=electrical" },
            { img: "assets/images/computer_science_icon.png", text: "វិទ្យាសាស្ត្រកុំព្យូទ័រ", link: "?page=cs", highlight: true },
            { img: "assets/images/mechanical_icon.png", text: "វិស្វកម្មមេកានិក", link: "?page=mechanical" },
            { img: "assets/images/optics_icon.png", text: "វិទ្យាសាស្ត្រអុបទិក", link: "?page=optics" },
            { img: "assets/images/civil_icon.png", text: "វិស្វកម្មសំណង់...", link: "?page=civil" },
            { img: "assets/images/electronics_icon.png", text: "វិស្វកម្មអេឡិចត្រូនិក...", link: "?page=electronics" },
            { img: "assets/images/automotive_icon.png", text: "វិស្វកម្មមេកានិករថយន្ត", link: "?page=automotive" },
            { img: "assets/images/tourism_icon.png", text: "ទេសចរណ៍...", link: "?page=tourism" }
        ];

        productContainer.innerHTML = `
        <div class="card-grid">
            ${categories.map(cat => `
                <a href="${cat.link}" class="card-link">
                    <div class="card">
                        <img src="${cat.img}" alt="${cat.text}">
                        <p class="${cat.highlight ? 'highlight' : ''}">${cat.text}</p>
                    </div>
                </a>
            `).join('')}
        </div>
    `;
    }
}
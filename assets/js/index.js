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

    // 1. Show Skeletons (Theme-aware via CSS classes)
    if (carouselContainer) {
        carouselContainer.innerHTML = `
        <div class="sk-carousel"> 
            <div class="skeleton-pulse sk-carousel-title"></div>
            <div class="skeleton-pulse sk-carousel-text"></div>
        </div>`;
    }

    if (productContainer) {
        // Updated to 8 to match your 8 final cards for a smoother transition
        productContainer.innerHTML = `
            <div class="card-grid">
                ${Array(8).fill(0).map(() => `
                    <div class="skeleton-card">
                        <div class="skeleton-pulse sk-image"></div>
                        <div class="skeleton-pulse sk-title"></div>
                    </div>
                `).join('')}
            </div>`;
    }

    // Wait for 2 seconds (Simulation)
    await new Promise(r => setTimeout(r, 2000));

    // 2. Inject Real Content
    if (carouselContainer) {
        carouselContainer.innerHTML = `
        <div class="carousel-container shadow-lg">
            <div class="carousel-track">
                <div class="slide">
                    <img src="assets/images/picture1.jpg" alt="Chemistry">
                    <div class="slide-content"><h2>Chemistry Lab</h2><p>Managing inventory.</p></div>
                </div>
                <div class="slide">
                    <img src="assets/images/picture2.jpg" alt="POS">
                    <div class="slide-content"><h2>POS Analytics</h2><p>Sales tracking.</p></div>
                </div>
                    <div class="slide">
                    <img src="assets/images/picture3.jpg" alt="POS">
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
            { img: "assets/images/civil_icon.png", text: "វិស្វកម្មសំណង់ស៊ីវិលនិងស្ថាបត្យកម្ម", link: "?page=civil" },
            { img: "assets/images/electronics_icon.png", text: "វិស្វកម្មអេឡិចត្រូនិកនិងទូរគមនាគមន៍", link: "?page=electronics" },
            { img: "assets/images/automotive_icon.png", text: "វិស្វកម្មមេកានិករថយន្ត", link: "?page=automotive" },
            { img: "assets/images/tourism_icon.png", text: "ទេសចរណ៍និងបដិសណ្ឋារកិច្ច", link: "?page=tourism" }
        ];

        productContainer.innerHTML = `
        <div class="section-header mb-4">
            <h3 class="fw-bold">ជំនាញសិក្សានៅសាកលវិទ្យាល័យ</h3>
            <p class="text-muted">University Majors & Subjects</p>
        </div>

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
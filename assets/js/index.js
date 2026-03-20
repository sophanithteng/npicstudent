document.addEventListener('DOMContentLoaded', () => {
    // Get the current page from URL
    const params = new URLSearchParams(window.location.search);
    const page = params.get("page");

    // 1. ROUTER LOGIC: Only load what is needed
    if (page === "dashboard" || !page) {
        const carouselRoot = document.getElementById('carousel-root');
        const loaderRoot = document.getElementById('loader-root');
        if (carouselRoot || loaderRoot) {
            loadDashboardContent();
        }
    }
    else if (page === "professor") {
        // Call function from professor.js (Make sure you renamed it there!)
        if (typeof loadProfessorContent === 'function') {
            loadProfessorContent();
        }
    }
    else if (page === "professor/electrical" || page === "electrical") {
        // Call function from electrical.js (Make sure you renamed it there!)
        if (typeof loadElectricalContent === 'function') {
            loadElectricalContent();
        }
    }

    // 2. Theme Toggle (Common logic for all pages)
    const toggleBtn = document.getElementById('theme-toggle');
    if (toggleBtn) {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);

        toggleBtn.addEventListener('click', () => {
            const newTheme = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            // Sync old class-based dark mode if your CSS uses it
            document.body.classList.toggle('dark-mode', newTheme === 'dark');
        });
    }
});

// Main Dashboard Function
async function loadDashboardContent() {
    const carouselContainer = document.getElementById('carousel-root');
    const productContainer = document.getElementById('loader-root');

    // Show Skeletons
    if (carouselContainer) {
        carouselContainer.innerHTML = `
        <div class="sk-carousel"> 
            <div class="skeleton-pulse sk-carousel-title"></div>
            <div class="skeleton-pulse sk-carousel-text"></div>
        </div>`;
    }

    if (productContainer) {
        productContainer.innerHTML = `<div class="card-grid">
            ${Array(8).fill(0).map(() => `
                <div class="skeleton-card">
                    <div class="skeleton-pulse sk-image"></div>
                    <div class="skeleton-pulse sk-title"></div>
                </div>`).join('')}
        </div>`;
    }

    await new Promise(r => setTimeout(r, 1000)); // Reduced to 1s for better feel

    // Inject Dashboard Content
    if (carouselContainer) {
        carouselContainer.innerHTML = `
        <div class="carousel-container shadow-lg">
            <div class="carousel-track">
                <div class="slide"><img src="assets/images/picture1.jpg"><div class="slide-content"><h2>Chemistry Lab</h2></div></div>
                <div class="slide"><img src="assets/images/picture2.jpg"><div class="slide-content"><h2>POS Analytics</h2></div></div>
            </div>
            <button class="nav-btn prev">❮</button>
            <button class="nav-btn next">❯</button>
        </div>`;

        if (typeof initCarouselLogic === 'function') { initCarouselLogic(); }
    }

    if (productContainer) {
        const categories = [
            { img: "assets/images/electrical_icon.png", text: "វិស្វកម្មអគ្គិសនី", link: "?page=professor/electrical" },
            { img: "assets/images/computer_science_icon.png", text: "វិទ្យាសាស្ត្រកុំព្យូទ័រ", link: "?page=cs", highlight: true },
            { img: "assets/images/mechanical_icon.png", text: "វិស្វកម្មមេកានិក", link: "?page=mechanical" },
            { img: "assets/images/optics_icon.png", text: "វិទ្យាសាស្ត្រអុបទិក", link: "?page=optics" },
            { img: "assets/images/civil_icon.png", text: "វិស្វកម្មសំណង់ស៊ីវិលនិងស្ថាបត្យកម្ម", link: "?page=civil" },
            { img: "assets/images/electronics_icon.png", text: "វិស្វកម្មអេឡិចត្រូនិកនិងទូរគមនាគមន៍", link: "?page=electronics" },
            { img: "assets/images/automotive_icon.png", text: "វិស្វកម្មមេកានិករថយន្ត", link: "?page=automotive" },
            { img: "assets/images/tourism_icon.png", text: "ទេសចរណ៍និងបដិសណ្ឋារកិច្ច", link: "?page=tourism" }
        ];


        productContainer.innerHTML = `
        <div class="section-header mb-4 text-center">
            <h3 class="fw-bold khmer-muol">ជំនាញសិក្សានៅសាកលវិទ្យាល័យ</h3>
            <p class="text-muted">University Majors & Subjects</p>
        </div>
        <div class="card-grid">
            ${categories.map(cat => `
                <a href="${cat.link}" class="card-link text-decoration-none">
                    <div class="card p-3 shadow-sm border-0 text-center h-100">
                        <img src="${cat.img}" alt="${cat.text}" style="width:60px; height:60px; margin: 0 auto;">
                        <p class="mt-2 mb-0 fw-bold khmer-muol ${cat.highlight ? 'text-primary' : 'text-dark'}">
                            ${cat.text}
                        </p>
                    </div>
                </a>`).join('')}
        </div>`;
    }
}
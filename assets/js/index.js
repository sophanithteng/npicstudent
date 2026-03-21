document.addEventListener('DOMContentLoaded', () => {

    const params = new URLSearchParams(window.location.search);
    const page = params.get("page");

    if (page === "dashboard" || !page) {
        const carouselRoot = document.getElementById('carousel-root');
        const loaderRoot = document.getElementById('loader-root');
        if (carouselRoot || loaderRoot) {
            loadDashboardContent();
        }
    }
    else if (page === "professor") {
        if (typeof loadProfessorContent === 'function') {
            loadProfessorContent();
        }
    }
    else if (page && page.startsWith("professor/")) {
        const slug = page.split("/")[1];

        const dep = departments.find(d => d.slug === slug);

        if (dep && typeof loadDepartmentContent === 'function') {
            loadDepartmentContent(dep.slug, dep.kh, dep.en, dep.id);
        }
    }


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

    await new Promise(r => setTimeout(r, 2000)); // Reduced to 1s for better feel

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
            {
                img: "assets/images/electrical_icon.png",
                text: "វិស្វកម្មអគ្គិសនី",
                link: "?page=professor/electrical"
            },
            {
                img: "assets/images/computer_science_icon.png",
                text: "វិទ្យាសាស្ត្រកុំព្យូទ័រ",
                link: "?page=professor/computer-science",
                highlight: true
            },
            {
                img: "assets/images/mechanical_icon.png",
                text: "វិស្វកម្មមេកានិក",
                link: "?page=professor/mechanical"
            },
            {
                img: "assets/images/optics_icon.png",
                text: "វិទ្យាសាស្ត្រអុបទិក",
                link: "?page=professor/optical"
            },
            {
                img: "assets/images/civil_icon.png",
                text: "វិស្វកម្មសំណង់ស៊ីវិលនិងស្ថាបត្យកម្ម",
                link: "?page=professor/civil-architecture"
            },
            {
                img: "assets/images/electronics_icon.png",
                text: "វិស្វកម្មអេឡិចត្រូនិកនិងទូរគមនាគមន៍",
                link: "?page=professor/electronics-telecom"
            },
            {
                img: "assets/images/automotive_icon.png",
                text: "វិស្វកម្មមេកានិករថយន្ត",
                link: "?page=professor/automotive"
            },
            {
                img: "assets/images/tourism_icon.png",
                text: "ទេសចរណ៍និងបដិសណ្ឋារកិច្ច",
                link: "?page=professor/tourism-hospitality"
            }
        ];


        productContainer.innerHTML = `
        <div class="section-header mb-4 text-center">
            <h3 class="fw-bold khmer-muol">ជំនាញសិក្សានៅសាកលវិទ្យាល័យ</h3>
            <p class="text-muted">University Majors & Subjects</p>
        </div>
        <div class="card-grid mb-5">
            ${categories.map(cat => `
                <a href="${cat.link}" class="card-link text-decoration-none">
                    <div class="card p-3 shadow-sm border-0 text-center h-100">
                        <img src="${cat.img}" alt="${cat.text}" style="width:60px; height:60px; margin: 0 auto;">
                        <p class="mt-2 mb-0 fw-bold khmer-muol ${cat.highlight ? 'text-primary' : 'text-dark'}">
                            ${cat.text}
                        </p>
                    </div>
                </a>`).join('')}
        </div>

        <hr class="my-5">
        <div class="study-hours-section p-4 rounded shadow-sm bg-body-tertiary">
            <div class="row align-items-center">
                <div class="col-md-3 text-center border-end">
                    <div class="display-4 text-warning mb-2">
                        <i class="bi bi-clock-history"></i> </div>
                    <h3 class="khmer-muol">ម៉ោងសិក្សា</h3>
                </div>
                <div class="col-md-9">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <h6 class="fw-bold">ថ្នាក់បរិញ្ញាបត្របច្ចេកវិទ្យា និងបច្ចេកទេសជាន់ខ្ពស់</h6>
                            <p class="small text-muted mb-0">ច័ន្ទ-សុក្រ ៖ ៨:០០-១២:០០ និង ១៣:០០-១៦:០០</p>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="fw-bold">ថ្នាក់បរិញ្ញាបត្រ និងបន្តបរិញ្ញាបត្របច្ចេកវិទ្យា (វេនយប់)</h6>
                            <p class="small text-muted mb-0">ច័ន្ទ-សុក្រ ៖ ៥:៣០ ល្ងាច – ៨:៣០ យប់</p>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="fw-bold">មហាវិទ្យាល័យវិទ្យាសាស្ត្រកុំព្យូទ័រ & មហាវិទ្យាល័យទេសចរណ៍</h6>
                            <p class="small text-muted mb-0">ច័ន្ទ-សុក្រ ៖ ៨:០០-១២:០០</p>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="fw-bold">ថ្នាក់បន្តបរិញ្ញាបត្របច្ចេកវិទ្យា ឬបន្តវិស្វករ (ចុងសប្តាហ៍)</h6>
                            <p class="small text-muted mb-0">សៅរ៍-អាទិត្យ ៖ ៨:០០-១២:០០ និង ១៣:០០-១៧:០០</p>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="fw-bold">បរិញ្ញាបត្រជាន់ខ្ពស់បច្ចេកវិទ្យា</h6>
                            <p class="small text-muted mb-0">សៅរ៍-អាទិត្យ ៖ ៨:០០-១២:០០ និង ១៣:០០-១៧:០០</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `; ``

        productContainer.innerHTML += `
        <hr class="my-5">
        <div class="scholarship-section mb-5">
            <div class="text-center mb-4">
                <h3 class="khmer-muol text-primary">អាហារូបករណ៍សម្រាប់និស្សិត</h3>
                <div style="width: 100px; height: 3px; background: orange; margin: 10px auto;"></div>
            </div>

            <div class="row g-4 align-items-center">
                <div class="col-lg-4">
                    <img src="assets/images/scholarship_event.jpg" class="img-fluid rounded shadow" alt="Scholarship Ceremony">
                </div>

                <div class="col-lg-8">
                    <div class="row g-3">
                        ${[
                { title: "អាហារូបករណ៍និស្សិតនារី", desc: "អាហារូបករណ៍ថ្លៃសិក្សា ៣០% (រយៈពេល ៤ឆ្នាំ)", icon: "bi-envelope" },
                { title: "អាហារូបករណ៍និស្សិតនិទ្ទេស A", desc: "អាហារូបករណ៍ថ្លៃសិក្សា ១០០% (រយៈពេល ៤ឆ្នាំ)", icon: "bi-envelope-paper" },
                { title: "អាហារូបករណ៍និស្សិតលេខ១ប្រចាំថ្នាក់", desc: "អាហារូបករណ៍ថ្លៃសិក្សាប្រចាំឆ្នាំ ១០០%", icon: "bi-bag-heart" },
                { title: "អាហារូបករណ៍និស្សិតនិទ្ទេស B", desc: "អាហារូបករណ៍ថ្លៃសិក្សា ៥០% (រយៈពេល ១ឆ្នាំ)", icon: "bi-plus-circle" },
                { title: "អាហារូបករណ៍និស្សិតលេខ២ប្រចាំថ្នាក់", desc: "អាហារូបករណ៍ថ្លៃសិក្សាប្រចាំឆ្នាំ ៣០%", icon: "bi-heart" },
                { title: "អាហារូបករណ៍និស្សិតនិទ្ទេស C", desc: "អាហារូបករណ៍ថ្លៃសិក្សា ៣០% (រយៈពេល ១ឆ្នាំ)", icon: "bi-gear" }
            ].map(item => `
                            <div class="col-md-6">
                                <div class="d-flex align-items-start p-2">
                                    <div class="icon-circle bg-warning text-white me-3 d-flex align-items-center justify-content-center" style="min-width: 45px; height: 45px; border-radius: 50%;">
                                        <i class="${item.icon}"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark-emphasis">${item.title}</h6>
                                        <p class="small text-muted mb-0">${item.desc}</p>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        </div>
        `;

        productContainer.innerHTML += `
        <div class="stats-counter-section py-5 my-4">
            <div class="row text-center g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-icon-bg mb-3">
                            <img src="assets/images/university_icon.png" alt="Established" style="width: 50px;">
                        </div>
                        <h2 class="fw-bold text-warning display-5">2005</h2>
                        <p class="text-muted small">បង្កើតឡើងតាំងពីឆ្នាំ</p>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-icon-bg mb-3">
                            <img src="assets/images/student_icon.png" alt="Students" style="width: 50px;">
                        </div>
                        <h2 class="fw-bold text-warning display-5">7,037</h2>
                        <p class="text-muted small">ចំនួននិស្សិតឆ្នាំសិក្សា ២០២៥-២០២៦</p>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-icon-bg mb-3">
                            <img src="assets/images/grad_icon.png" alt="Graduation" style="width: 50px;">
                        </div>
                        <h2 class="fw-bold text-warning display-5">85<span style="font-size: 1.5rem;">%</span></h2>
                        <p class="text-muted small">អត្រានិស្សិតបញ្ចប់ការសិក្សា</p>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-icon-bg mb-3">
                            <img src="assets/images/partner_icon.png" alt="Partners" style="width: 50px;">
                        </div>
                        <h2 class="fw-bold text-warning display-5">258</h2>
                        <p class="text-muted small">ដៃគូសហប្រតិបត្តិការ</p>
                    </div>
                </div>
            </div>
        </div>
        `;
    }
}
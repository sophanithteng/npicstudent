async function loadProfessorContent() {
    const productContainer = document.getElementById('professor-root');
    if (!productContainer) return;

    // 1. Show Skeleton Loaders

    productContainer.innerHTML = `<div class="card-grid">
            ${Array(8).fill(0).map(() => `
                <div class="skeleton-card">
                    <div class="skeleton-pulse sk-image"></div>
                    <div class="skeleton-pulse sk-title"></div>
                </div>`).join('')}
        </div>`;


    // Simulate network delay (1 second)
    await new Promise(r => setTimeout(r, 2000));

    const categories = [
        { img: "assets/images/electrical_icon.png", text: "វិស្វកម្មអគ្គិសនី", link: "professor/electrical" },
        { img: "assets/images/computer_science_icon.png", text: "វិទ្យាសាស្ត្រកុំព្យូទ័រ", link: "professor/computer-science", highlight: true },
        { img: "assets/images/mechanical_icon.png", text: "វិស្វកម្មមេកានិក", link: "professor/mechanical" },
        { img: "assets/images/optics_icon.png", text: "វិទ្យាសាស្ត្រអុបទិក", link: "professor/optics" },
        { img: "assets/images/civil_icon.png", text: "វិស្វកម្មសំណង់ស៊ីវិលនិងស្ថាបត្យកម្ម", link: "professor/civil" },
        { img: "assets/images/electronics_icon.png", text: "វិស្វកម្មអេឡិចត្រូនិកនិងទូរគមនាគមន៍", link: "professor/electronics" },
        { img: "assets/images/automotive_icon.png", text: "វិស្វកម្មមេកានិករថយន្ត", link: "professor/automotive" },
        { img: "assets/images/tourism_icon.png", text: "ទេសចរណ៍និងបដិសណ្ឋារកិច្ច", link: "professor/tourism" }
    ];

    productContainer.innerHTML = `
    <div class="section-header mb-4 text-center">
        <h3 class="fw-bold">ជំនាញសិក្សានៅសាកលវិទ្យាល័យ</h3>
        <p class="text-muted small">University Majors & Subjects</p>
    </div>

    <div class="card-grid">
        ${categories.map(cat => `
            <a href="./?page=${cat.link}" class="card-link text-decoration-none">
                <div class="card shadow-sm border-0 p-3 text-center h-100 transition-hover" style="border-radius: 15px;">
                    <div class="icon-wrapper mb-2">
                        <img src="${cat.img}" alt="${cat.text}" style="width: 60px; height: 60px; object-fit: contain;">
                    </div>
                    <p class="mb-0 fw-semibold text-dark ${cat.highlight ? 'text-primary' : ''}" style="font-size: 0.9rem;">
                        ${cat.text}
                    </p>
                </div>
            </a>
        `).join('')}
    </div>`;
}
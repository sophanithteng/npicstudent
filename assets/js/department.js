const departments = [
    {
        slug: 'electrical',
        kh: 'ជំនាញវិស្វកម្មអគ្គិសនី',
        en: 'Electrical Engineering',
        id: 'electrical-root'
    },
    {
        slug: 'computer-science',
        kh: 'ជំនាញវិទ្យាសាស្ត្រកុំព្យូទ័រ',
        en: 'Computer Science',
        id: 'computer-science-root'
    },
    {
        slug: 'mechanical',
        kh: 'ជំនាញវិស្វកម្មមេកានិក',
        en: 'Mechanical Engineering',
        id: 'mech-root'
    },
    {
        slug: 'optical',
        kh: 'វិទ្យាសាស្ត្រអុបទិក',
        en: 'Optical Science',
        id: 'optical-root'
    },
    {
        slug: 'civil-architecture',
        kh: 'សំណង់ស៊ីវិល និង ស្ថាបត្យកម្ម',
        en: 'Civil Engineering & Architecture',
        id: 'civil-root'
    },
    {
        slug: 'electronics-telecom',
        kh: 'អេឡិចត្រូនិក និង ទូរគមនាគមន៍',
        en: 'Electronics & Telecommunication',
        id: 'telecom-root'
    },
    {
        slug: 'automotive',
        kh: 'មេកានិករថយន្ត',
        en: 'Automotive Engineering',
        id: 'auto-root'
    },
    {
        slug: 'tourism-hospitality',
        kh: 'ទេសចរណ៍ និង បដិសណ្ឋារកិច្ច',
        en: 'Tourism & Hospitality',
        id: 'tourism-root'
    }
];

async function loadDepartmentContent(department, titleKh, titleEn, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = `
        <div class="section-header mb-4 text-center">
            <h3 class="fw-bold">${titleKh}</h3>
            <p class="text-muted small">${titleEn}</p>
        </div>

        <div class="card-grid">
            ${Array(4).fill(0).map(() => `
                <div class="skeleton-card">
                    <div class="skeleton-pulse sk-image"></div>
                    <div class="skeleton-pulse sk-title"></div>
                </div>
            `).join('')}
        </div>
    `;

    try {
        const response = await fetch(
            `api/get_professors.php?slug=${encodeURIComponent(department)}`
        );

        if (!response.ok) throw new Error("Network error");

        const professors = await response.json();

        // 🔹 No data
        if (!Array.isArray(professors) || professors.length === 0) {
            container.innerHTML = `
                <div class="section-header mb-4 text-center">
                    <h3 class="fw-bold">${titleKh}</h3>
                    <p class="text-muted small">${titleEn}</p>
                </div>
                <p class="text-center">No professors found.</p>
            `;
            return;
        }

        // 🔹 Render Cards
        container.innerHTML = `
            <div class="section-header mb-4 text-center">
                <h3 class="fw-bold">${titleKh}</h3>
                <p class="text-muted small">${titleEn}</p>
            </div>

            <div class="card-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:20px;">
                ${professors.map(prof => `
                    <a href="${prof.link || '#'}" class="text-decoration-none text-dark">
                        <div class="card p-3 shadow-sm border-0 h-100 text-center">

                            <img src="${prof.img}" 
                                 onerror="this.src='assets/professor_images/emptyuser.png'"
                                 style="width:100px;height:100px;object-fit:cover;border-radius:50%;border:3px solid #eee;"
                                 class="mx-auto mb-2">

                            <h6 class="fw-bold mb-1">${prof.name}</h6>
                            <p class="small text-primary mb-1">${prof.role}</p>
                            <p class="small text-muted mb-0" style="font-size:0.75rem;">
                                ${prof.desc}
                            </p>

                        </div>
                    </a>
                `).join('')}
            </div>
        `;

    } catch (error) {
        console.error(error);

        container.innerHTML = `
            <div class="section-header mb-4 text-center">
                <h3 class="fw-bold">${titleKh}</h3>
                <p class="text-muted small">${titleEn}</p>
            </div>
            <p class="text-danger text-center">Error loading content.</p>
        `;
    }
}
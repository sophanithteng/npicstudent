async function loadElectricalContent() {
    const productContainer = document.getElementById('electrical-root');
    if (!productContainer) return;


    productContainer.innerHTML = `<div class="card-grid">
            ${Array(8).fill(0).map(() => `
                <div class="skeleton-card">
                    <div class="skeleton-pulse sk-image"></div>
                    <div class="skeleton-pulse sk-title"></div>
                </div>`).join('')}
        </div>`;


    await new Promise(r => setTimeout(r, 2000));

    const professors = [
        {
            img: "assets/images/electrical_icon.png",
            name: "បណ្ឌិត ចេង ហ៊ឈង",
            role: "ប្រធានមហាវិទ្យាល័យអគ្គិសនី",
            link: "professor/electrical"
        },
        {
            img: "assets/images/computer_science_icon.png",
            name: "វិទ្យាសាស្ត្រកុំព្យូទ័រ",
            role: "ដេប៉ាតឺម៉ង់",
            link: "professor/cs"
        }
    ];

    productContainer.innerHTML = `
        <div class="card-grid">
            ${professors.map(prof => `
                <a href="?page=${prof.link}" class="card-link text-decoration-none text-dark">
                    <div class="card p-3 shadow-sm border-0 h-100 transition-hover">
                        <div class="text-center">
                            <img src="${prof.img}" class="mb-2" style="width:80px; height:80px; object-fit:cover; border-radius:50%;">
                            <h6 class="fw-bold mb-1">${prof.name}</h6>
                            <p class="small text-muted mb-0">${prof.role}</p>
                        </div>
                    </div>
                </a>
            `).join('')}
        </div>
    `;
}

document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const page = params.get("page");

    if (page === "electrical") {
        loadDashboardContent();
    }
});
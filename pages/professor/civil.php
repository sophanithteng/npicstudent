<main class="container py-5">

    <?php if (isAdmin()): ?>
        <div class="row align-items-center mb-5 g-3">
            <div class="col-12 col-md-7 text-center text-md-start">
                <h3 class="fw-bold text-body mb-1">Add Professor</h3>
                <p class="text-secondary small mb-0">
                    Create card for the new professor in our website.
                </p>
            </div>

            <div class="col-12 col-md-5 text-center text-md-end">
                <a href="./?page=professor/create_professor"
                    class="btn btn-primary px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    <span>Add New Professor</span>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <div id="civil-root"></div>

</main>
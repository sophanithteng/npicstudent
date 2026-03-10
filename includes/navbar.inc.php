<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <img src="assets/images/npic_logo.png" alt="Logo" style="max-width:40px; border-radius:6px;">
            <div class="d-flex flex-column flex-md-row align-items-baseline">
                <span class="brand-Emilia fw-bold text-primary">NPIC</span>
                <span class="brand-Computer ms-md-1 d-none d-sm-inline opacity-75">Student</span>
            </div>
        </a>

        <div class="d-flex align-items-center gap-2 order-lg-last">
            
            <button id="theme-toggle" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-moon-stars"></i> Mode
            </button>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="search-wrap d-flex my-2 my-md-0 mx-md-4 flex-grow-1" role="search">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Search products..." aria-label="Search">
                    <button class="btn btn-primary" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Account</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
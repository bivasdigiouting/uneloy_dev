@php
    $menuService = app(\App\Services\MenuService::class);
    $mobileMenuHtml = $menuService->renderMobileMenu();
@endphp

<!-- Mobile Menu Toggle -->
<div class="mobile-menu-toggle d-md-none">
    <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
        <i class="fa fa-bars"></i> Menu
    </button>
</div>

<!-- Mobile Menu Offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileMenuLabel">
            <img src="{{ asset('frontend-assets/design_img/logo.png') }}" alt="Uonely Solutions" style="height: 40px;">
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        {!! $mobileMenuHtml !!}
    </div>
</div>

<style>
.mobile-menu-toggle {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
}

.mobile-menu-nav {
    list-style: none;
    padding: 0;
    margin: 0;
}

.mobile-menu-nav li {
    border-bottom: 1px solid #eee;
}

.mobile-menu-nav a {
    display: block;
    padding: 15px 0;
    color: #333;
    text-decoration: none;
    font-weight: 500;
}

.mobile-menu-nav a:hover {
    color: #ea526c;
    background-color: #f8f9fa;
}

.mobile-menu-nav .has-children > a {
    position: relative;
}

.mobile-menu-nav .has-children > a::after {
    content: '\f107';
    font-family: 'FontAwesome';
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
}

.mobile-menu-nav .submenu {
    display: none;
    padding-left: 20px;
    background-color: #f8f9fa;
}

.mobile-menu-nav .submenu.show {
    display: block;
}

.mobile-menu-nav .submenu a {
    padding: 10px 0;
    font-size: 14px;
    color: #666;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle mobile menu submenu toggles
    const mobileMenuItems = document.querySelectorAll('.mobile-menu-nav .has-children > a');
    
    mobileMenuItems.forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = this.nextElementSibling;
            if (submenu && submenu.classList.contains('submenu')) {
                submenu.classList.toggle('show');
                
                // Update arrow direction
                if (submenu.classList.contains('show')) {
                    this.style.setProperty('--arrow-content', '"\f106"');
                } else {
                    this.style.setProperty('--arrow-content', '"\f107"');
                }
            }
        });
    });
});
</script>
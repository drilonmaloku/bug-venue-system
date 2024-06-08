var menuOptions = {
    init: function () {
        var hamburgerMenu = document.querySelector('.hamburger-menu');
        var navbarLinks = document.querySelector('.hubers-navbar-links');

        if (hamburgerMenu && navbarLinks) {
            hamburgerMenu.addEventListener('click', function () {
                this.classList.toggle('active');
                navbarLinks.classList.toggle('active');
            });
        }
    }
};

var filterOptions = {
    init: function () {
        var filterBtn = document.querySelector('.huber-filter-btn');
        var filterOptions = document.querySelector('.hubers-filter-options');

        if (filterBtn && filterOptions) {
            filterBtn.addEventListener('click', function () {
                filterBtn.classList.toggle('active');
                filterOptions.classList.toggle('active');
            });
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    menuOptions.init();
    filterOptions.init();
});
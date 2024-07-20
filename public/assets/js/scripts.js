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
var passwordToggle = {
    init: function () {
        var toggleIcon = document.querySelector('.password-input-toggle-icon');
        var passwordField = document.querySelector('#password');

        if (toggleIcon && passwordField) {
            toggleIcon.addEventListener('click', function () {
                var type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
            });
        }
    }
};
var filterInputOptions = {
    init: function () {
        document.querySelectorAll('.file-uploader').forEach(function(uploader) {
            const input = uploader.querySelector('.form-input-attachment');
            const fileNameDisplay = uploader.querySelector('.file-name');
            const removeButton = uploader.querySelector('.remove-file');

            input.addEventListener('change', function(event) {
                var fileName = event.target.files[0] ? event.target.files[0].name : "Upload File";
                fileNameDisplay.textContent = fileName;
                removeButton.style.display = 'inline-block';
            });

            removeButton.addEventListener('click', function(event) {
                event.stopPropagation();
                input.value = '';
                fileNameDisplay.textContent = 'Upload File';
                removeButton.style.display = 'none';
            });
        });
    }
}
document.addEventListener('DOMContentLoaded', function () {
    menuOptions.init();
    filterOptions.init();
    passwordToggle.init();
    filterInputOptions.init();
});
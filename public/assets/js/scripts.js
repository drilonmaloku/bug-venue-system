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
var checkboxOptions = {
    init: function () {
        document.querySelectorAll('.bug-table').forEach(table => {
            var mainCheckbox = table.querySelector('.main-checkbox');
            var checkboxes = table.querySelectorAll('.table-checkbox');

            if (mainCheckbox) {
                mainCheckbox.addEventListener('click', () => {
                    checkboxes.forEach(checkbox => checkbox.checked = mainCheckbox.checked);
                });

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('click', () => {
                        mainCheckbox.checked = Array.from(checkboxes).every(cb => cb.checked);
                    });
                });
            }
        });
    }
}
var exportOptions = {
    export: function (baseUrl, filename, targetTableId = null) {
        let selectedCheckboxes;

        if (targetTableId) {
            const targetTable = document.getElementById(targetTableId);
            if (targetTable) {
                selectedCheckboxes = targetTable.querySelectorAll('.table-checkbox:checked');
            } else {
                alert('Target table not found.');
                return;
            }
        } else {
            selectedCheckboxes = document.querySelectorAll('.table-checkbox:checked');
        }

        let selectedIds = [];
        selectedCheckboxes.forEach(checkbox => {
            selectedIds.push(checkbox.value);
        });

        if (selectedIds.length > 0) {
            const link = document.createElement('a');
            link.href = `${baseUrl}?ids=${selectedIds.join(',')}`;
            link.download = filename;  // Change the file name if necessary
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } else {
            alert('Please select at least one client to export.');
        }
    }
}
document.addEventListener('DOMContentLoaded', function () {
    menuOptions.init();
    filterOptions.init();
    passwordToggle.init();
    filterInputOptions.init();
    checkboxOptions.init();
});
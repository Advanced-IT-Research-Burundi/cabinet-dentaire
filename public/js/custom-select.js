
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all custom selects
    initCustomSelects();

    // Setup treatment type change to update price
    setupTreatmentTypePrice();
});

function initCustomSelects() {
    const customSelects = document.querySelectorAll('.custom-select');

    customSelects.forEach(select => {
        const selectedDiv = select.querySelector('.select-selected');
        const hiddenInput = select.nextElementSibling;
        const options = select.querySelectorAll('.select-option');
        const searchInput = select.querySelector('input[type="text"]');

        // Set initial selection if value exists
        if (hiddenInput.value) {
            options.forEach(option => {
                if (option.dataset.value === hiddenInput.value) {
                    option.classList.add('selected');
                    selectedDiv.textContent = option.textContent.trim();
                }
            });
        }

        // Toggle dropdown on click
        selectedDiv.addEventListener('click', function() {
            closeAllCustomSelects();
            select.classList.toggle('active');
            if (select.classList.contains('active') && searchInput) {
                setTimeout(() => searchInput.focus(), 100);
            }
        });

        // Handle search input
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const options = select.querySelectorAll('.select-option');

                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    option.style.display = text.includes(filter) ? '' : 'none';
                });
            });

            // Prevent dropdown close when clicking search
            searchInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Handle option selection
        options.forEach(option => {
            option.addEventListener('click', function() {
                const value = this.dataset.value;
                hiddenInput.value = value;

                // Remove previous selection
                options.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');

                // Update selected display
                selectedDiv.textContent = this.textContent.trim();

                // Close dropdown
                select.classList.remove('active');

                // Trigger change event on hidden input
                const event = new Event('change', { bubbles: true });
                hiddenInput.dispatchEvent(event);
            });
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-select')) {
            closeAllCustomSelects();
        }
    });
}

function closeAllCustomSelects() {
    const activeSelects = document.querySelectorAll('.custom-select.active');
    activeSelects.forEach(select => select.classList.remove('active'));
}

function setupTreatmentTypePrice() {
    const treatmentTypeInput = document.getElementById('treatment_type_id');
    const priceInput = document.getElementById('applied_price');

    treatmentTypeInput.addEventListener('change', function() {
        const options = document.querySelectorAll('#treatment_type_options .select-option');

        options.forEach(option => {
            if (option.dataset.value === this.value && option.dataset.price) {
                priceInput.value = option.dataset.price;
            }
        });
    });
}

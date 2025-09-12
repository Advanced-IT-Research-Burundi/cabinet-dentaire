class TreatmentMultiSelect {
    constructor(containerSelector) {
        this.container = document.querySelector(containerSelector);
        this.displayArea = this.container.querySelector('#multiSelectDisplay');
        this.dropdownMenu = this.container.querySelector('#multiSelectMenu');
        this.searchField = this.container.querySelector('#treatmentSearchInput');
        this.optionsArea = this.container.querySelector('#menuOptionsContainer');
        this.hiddenField = document.querySelector('#selectedTreatmentTypes');
        this.selectedItems = [];
        this.availableOptions = [];

        this.initialize();
    }

    initialize() {
        // Stocker toutes les options disponibles
        this.availableOptions = Array.from(this.optionsArea.children);

        // Gestionnaires d'événements
        this.displayArea.addEventListener('click', (e) => {
            if (!e.target.closest('.multi-select-menu')) {
                this.toggleDropdown();
            }
        });

        this.searchField.addEventListener('input', (e) => this.performSearch(e.target.value));
        this.searchField.addEventListener('click', (e) => e.stopPropagation());

        this.optionsArea.addEventListener('click', (e) => {
            const optionItem = e.target.closest('.menu-option-item');
            if (optionItem) {
                this.handleOptionToggle(optionItem);
            }
        });

        // Fermer le menu en cliquant à l'extérieur
        document.addEventListener('click', (e) => {
            if (!this.container.contains(e.target)) {
                this.closeDropdown();
            }
        });

        this.refreshDisplay();
    }

    toggleDropdown() {
        this.dropdownMenu.classList.toggle('show');
        if (this.dropdownMenu.classList.contains('show')) {
            this.searchField.focus();
        }
    }

    closeDropdown() {
        this.dropdownMenu.classList.remove('show');
        this.searchField.value = '';
        this.performSearch('');
    }

    handleOptionToggle(optionItem) {
        const itemValue = optionItem.dataset.value;
        const checkbox = optionItem.querySelector('.option-check-input');

        if (this.selectedItems.includes(itemValue)) {
            // Retirer de la sélection
            this.selectedItems = this.selectedItems.filter(v => v !== itemValue);
            checkbox.checked = false;
            optionItem.classList.remove('option-selected');
        } else {
            // Ajouter à la sélection
            this.selectedItems.push(itemValue);
            checkbox.checked = true;
            optionItem.classList.add('option-selected');
        }

        this.refreshDisplay();
        this.updateHiddenField();
    }

    removeSelectedItem(itemValue) {
        this.selectedItems = this.selectedItems.filter(v => v !== itemValue);

        // Mettre à jour l'option correspondante
        const optionItem = this.optionsArea.querySelector(`[data-value="${itemValue}"]`);
        if (optionItem) {
            const checkbox = optionItem.querySelector('.option-check-input');
            checkbox.checked = false;
            optionItem.classList.remove('option-selected');
        }

        this.refreshDisplay();
        this.updateHiddenField();
    }

    refreshDisplay() {
        // Vider la zone d'affichage
        this.displayArea.innerHTML = '';

        if (this.selectedItems.length === 0) {
            this.displayArea.innerHTML = '<span class="placeholder-message">Sélectionnez un ou plusieurs types de traitement</span>';
        } else {
            // Créer les badges pour chaque élément sélectionné
            this.selectedItems.forEach(itemValue => {
                const optionItem = this.optionsArea.querySelector(`[data-value="${itemValue}"]`);
                if (optionItem) {
                    const badge = document.createElement('span');
                    badge.className = 'selected-item-tag badge rounded-pill px-2 py-1';
                    badge.innerHTML = `
                        ${optionItem.textContent.trim()}
                        <span class="tag-close-btn" data-value="${itemValue}">×</span>
                    `;

                    // Gestionnaire pour supprimer le badge
                    badge.querySelector('.tag-close-btn').addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.removeSelectedItem(itemValue);
                    });

                    this.displayArea.appendChild(badge);
                }
            });
        }
    }

    updateHiddenField() {
        this.hiddenField.value = this.selectedItems.join(',');
    }

    performSearch(searchTerm) {
        const term = searchTerm.toLowerCase();

        this.availableOptions.forEach(option => {
            const text = option.textContent.toLowerCase();
            if (text.includes(term)) {
                option.classList.remove('d-none');
            } else {
                option.classList.add('d-none');
            }
        });
    }

    // Méthode pour définir les valeurs sélectionnées
    setSelectedValues(values) {
        this.selectedItems = Array.isArray(values) ? values : [values];

        // Mettre à jour les options
        this.availableOptions.forEach(option => {
            const value = option.dataset.value;
            const checkbox = option.querySelector('.option-check-input');

            if (this.selectedItems.includes(value)) {
                checkbox.checked = true;
                option.classList.add('option-selected');
            } else {
                checkbox.checked = false;
                option.classList.remove('option-selected');
            }
        });

        this.refreshDisplay();
        this.updateHiddenField();
    }

    // Méthode pour obtenir les valeurs sélectionnées
    getSelectedValues() {
        return this.selectedItems;
    }

    // Méthode pour vider la sélection
    clearSelection() {
        this.selectedItems = [];
        this.availableOptions.forEach(option => {
            const checkbox = option.querySelector('.option-check-input');
            checkbox.checked = false;
            option.classList.remove('option-selected');
        });
        this.refreshDisplay();
        this.updateHiddenField();
    }
}

// Initialisation du composant
document.addEventListener('DOMContentLoaded', function() {
    const treatmentSelector = new TreatmentMultiSelect('.multi-select-wrapper');

    // Exemple d'utilisation :
    // treatmentSelector.setSelectedValues(['1', '3']);

    // Pour déboguer, vous pouvez utiliser :
    window.treatmentSelector = treatmentSelector;
});

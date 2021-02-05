/* eslint-disable import/no-unresolved */
import Plugin from 'src/plugin-system/plugin.class';
import DomAccess from 'src/helper/dom-access.helper';

export default class SwagCustomizedProductsFormValidator extends Plugin {
    static options = {
        inputFieldsSelector: 'input, textarea',
        selectors: {
            buyButton: '#productDetailPageBuyProductForm .btn-buy',
            fileUploadContainer: '.customized-products-upload',
            confirmInput: '#swag-customized-products-confirm-input'
        }
    };

    init() {
        this.parentEl = this.el.parentNode;
        this.exclusionsValid = true;
        this._registerEventListeners();
    }

    /**
     * @returns {void}
     */
    _registerEventListeners() {
        this.$emitter.subscribe(
            'buyButtonDisable',
            this.updateExclusionValidity.bind(this)
        );

        this.$emitter.subscribe(
            'change',
            this.onFormChange.bind(this)
        );

        const inputFields = DomAccess.querySelectorAll(this.parentEl, this.options.inputFieldsSelector, false);
        inputFields.forEach((field) => {
            field.addEventListener(
                'invalid',
                this._onInputInvalid.bind(this)
            );
        });

        // Initially check for required fields
        this.onFormChange();
    }

    /**
     * Validates if all required input fields are filled
     */
    onFormChange() {
        const buyButton = DomAccess.querySelector(this.parentEl, this.options.selectors.buyButton);
        const inputFields = DomAccess.querySelectorAll(this.parentEl, this.options.inputFieldsSelector, false);
        const selectionValidation = {};
        const requiredSimpleFieldsFilled = Array.from(inputFields).reduce((acc, field) => {
            // Collect validity information of every selection input group
            const selectionOptionId = field.dataset.swagCustomizedProductsSelectionRequired;
            if (selectionOptionId !== undefined) {
                selectionValidation[selectionOptionId] = selectionValidation[selectionOptionId] || field.checked;
                if (field.hasAttribute('required')) {
                    field.removeAttribute('required');
                }
            }

            const fileUploadContainer = field.closest(this.options.selectors.fileUploadContainer);
            // If we are dealing with a file upload
            if (fileUploadContainer) {
                // FileUpload not required
                if (!DomAccess.getDataAttribute(field, 'required', false)) {
                    return acc;
                }

                const fileUploadPlugin = window.PluginManager.getPluginInstanceFromElement(
                    fileUploadContainer,
                    'SwagCustomizedProductsFileUpload'
                );
                // The registry property holds the actual files and the input field value gets reset
                if (fileUploadPlugin !== undefined && fileUploadPlugin.registry.size > 0) {
                    return acc;
                }

                acc = false;
                return acc;
            }


            if (!acc || !field.required) {
                return acc;
            }

            if (field.required && field.value.length) {
                return acc;
            }

            acc = false;
            return acc;
        }, true);

        let requiredFieldsFilled = requiredSimpleFieldsFilled &&
            Object.values(selectionValidation).every(groupValid => groupValid) &&
            this.exclusionsValid;

        const confirmInput = DomAccess.querySelector(this.parentEl, this.options.selectors.confirmInput, false);
        if (confirmInput) {
            requiredFieldsFilled = requiredFieldsFilled && confirmInput.checked;
        }

        if (requiredFieldsFilled) {
            if (buyButton.hasAttribute('disabled')) {
                buyButton.removeAttribute('disabled');
            }

            return;
        }

        buyButton.setAttribute('disabled', 'disabled');
    }

    _onInputInvalid(event) {
        const element = event.target.closest('.collapse');

        /* eslint-env jquery */
        $(element).collapse('show');

        // Fire event
        this.$emitter.publish('invalid', {
            element: event.target
        });
    }

    /**
     * Callback which updates the local property if the exclusions are valid
     *
     * @param {Event} event
     * @returns {void}
     */
    updateExclusionValidity(event) {
        this.exclusionsValid = !event.detail;
        this.onFormChange();
    }
}

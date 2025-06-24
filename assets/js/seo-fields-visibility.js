document.addEventListener('DOMContentLoaded', function() {
    const pageType = document.body.dataset.pageType;
    const seoFields = document.querySelectorAll('.seo-field-group');
    
    if (!window.seoFieldsVisibility || !window.seoFieldsVisibility[pageType]) {
        seoFields.forEach(field => {
            field.style.display = 'none';
        });
    }
});
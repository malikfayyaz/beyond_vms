document.addEventListener('DOMContentLoaded', () => {
    ['#additionalRequirementEditor', '#buJustification'].forEach(selector => {
        const container = document.querySelector(selector);
        if (container) {
            new Quill(container, {
                theme: 'snow',
                placeholder: `Enter ${selector === '#additionalRequirementEditor' ? 'additional requirements' : 'business justification'}...`,
            });
        } else {
            console.error(`Container for ${selector} not found!`);
        }
    });
});     
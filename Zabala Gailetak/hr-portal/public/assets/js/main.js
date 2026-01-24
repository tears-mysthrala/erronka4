/**
 * Zabala Gailetak - HR Portal Main Script
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Zabala Gailetak HR Portal loaded');
    
    // Initialize tooltips if Bootstrap is present
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

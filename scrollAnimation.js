// Reveal options to create reveal animations
ScrollReveal( {
    reset: false,
    distance: '60px',
    duration: 2500,
    delay: 400
});

// Target elements, and specify options to create reveal animations
// For Admin Dashboard
ScrollReveal().reveal('.admin-title', { delay: 300, origin: 'left' });
ScrollReveal().reveal('.admin-row, .clock-box', { origin: 'bottom' });
ScrollReveal().reveal('.summaryColumn, .barChart-container, .pieChart-container', { delay: 450, origin: 'bottom', interval: 600 });
ScrollReveal().reveal('.report', { delay: 300, origin: 'left' });

// For Edit Customer & Edit Product Page
ScrollReveal().reveal('.admin-title', { delay: 200, origin: 'left' });
ScrollReveal().reveal('.displayTable', { origin: 'bottom' });
ScrollReveal().reveal('.column-form', { delay: 350, origin: 'left', interval: 500 });
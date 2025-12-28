// DOM Elements
const sidebar = document.querySelector('.sidebar');
const overlay = document.querySelector('.overlay');
const slideSheet = document.getElementById('slide-sheet');

// Mobile Menu Toggle
function toggleSidebar() {
    if (!sidebar) return;
    sidebar.classList.toggle('active');
    toggleOverlay();
}

function toggleOverlay() {
    if (!overlay) return;

    if (
        (sidebar && sidebar.classList.contains('active')) ||
        (slideSheet && slideSheet.classList.contains('active'))
    ) {
        overlay.classList.add('active');
    } else {
        overlay.classList.remove('active');
    }
}

// Slide Sheet Control
function openSlideSheet(contentId) {
    if (!slideSheet || !overlay) return;
    slideSheet.classList.add('active');
    overlay.classList.add('active');
}

function closeSlideSheet() {
    if (slideSheet) slideSheet.classList.remove('active');
    if (overlay) overlay.classList.remove('active');
    if (sidebar) sidebar.classList.remove('active');
}

// Close on overlay click
if (overlay) {
    overlay.addEventListener('click', closeSlideSheet);
}

// Global Fetch Wrapper
async function fetchData(url, options = {}) {
    try {
        const response = await fetch(url, options);
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

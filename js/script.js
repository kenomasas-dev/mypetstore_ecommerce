document.addEventListener('DOMContentLoaded', function() {
    // Check local storage for user role
    const userRole = localStorage.getItem('userRole');

    // Get the current page's filename
    const currentPage = window.location.pathname.split('/').pop(); // Get filename from path

    // Redirect if the user role doesn't match the page
    if ((userRole === 'admin' && currentPage !== 'admin.html') ||
        (userRole === 'staff' && currentPage !== 'staff.html') ||
        (userRole === 'user' && currentPage !== 'user.html')) {
        window.location.href = 'index.html'; // Redirect to the home page
    }
});
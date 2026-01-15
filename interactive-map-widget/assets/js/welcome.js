document.addEventListener('DOMContentLoaded', function() {
    // Check if the form was submitted
    if (document.getElementById('nahiro-redirect-flag')) {
        window.location.href = nahiroRedirectData.redirectUrl;
    }
});
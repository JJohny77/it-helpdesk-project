// main.js

document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Λειτουργία για αυτόματη απόκρυψη των Alerts μετά από 3 δευτερόλεπτα
    const alerts = document.querySelectorAll('.alert');
    
    if (alerts.length > 0) {
        setTimeout(function() {
            alerts.forEach(function(alert) {
                // Χρησιμοποιούμε το Bootstrap transition για να σβήσει απαλά
                alert.classList.remove('show');
                alert.classList.add('fade');
                
                // Μετά το animation, το αφαιρούμε τελείως από το DOM
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000); // 3000ms = 3 δευτερόλεπτα
    }

});

// 2. Επιβεβαίωση Διαγραφής (Αντικαθιστά το inline onclick για πιο καθαρό κώδικα)
function confirmDelete(event) {
    if (!confirm("Είστε σίγουρος ότι θέλετε να διαγράψετε αυτό το αίτημα;")) {
        event.preventDefault(); // Ακυρώνει το κλικ αν πατήσει 'Ακυρο'
    }
}
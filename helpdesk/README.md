# IT Helpdesk Ticketing System 🎫

Ένα ολοκληρωμένο Web Project για τη διαχείριση τεχνικών αιτημάτων (Tickets), αναπτυγμένο στο πλαίσιο του μαθήματος "Προγραμματιστικές Εφαρμογές στο Διαδίκτυο".

## 🚀 Δυνατότητες (Features)
* **Role-Based Access:** Διαχωρισμός σε Public Area (Υπάλληλοι) και Admin Area (Τεχνικοί).
* **Σύστημα Login/Logout:** Ασφαλής είσοδος διαχειριστών με Hashed Passwords.
* **CRUD Operations:** Πλήρης κύκλος ζωής αιτήματος (Δημιουργία, Προβολή, Επίλυση, Διαγραφή).
* **Analytics Dashboard:** Στατιστικά στοιχεία σε πραγματικό χρόνο (Σύνολο, Εκκρεμότητες, Επείγοντα).
* **Smart Search & Filters:** Δυναμική αναζήτηση και φιλτράρισμα αιτημάτων.
* **Excel Export:** Εξαγωγή δεδομένων σε αρχείο CSV για περαιτέρω επεξεργασία.
* **Responsive Design:** Μοντέρνο UI με χρήση Bootstrap 5 και Custom CSS.

## 🛠️ Τεχνολογίες (Tech Stack)
* **Backend:** PHP (PDO), MySQL
* **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript
* **Tools:** VS Code, MySQL Workbench, XAMPP

## ⚙️ Οδηγίες Εγκατάστασης (Installation)

1.  **Βάση Δεδομένων:**
    * Δημιουργήστε μια κενή βάση δεδομένων με όνομα `helpdesk_db`.
    * Κάντε Import το αρχείο `finaldb_helpdesk.sql` που περιλαμβάνεται στα αρχεία.

2.  **Ρύθμιση Σύνδεσης:**
    * Ανοίξτε το αρχείο `db.php`.
    * Ελέγξτε ότι τα στοιχεία (username/password) ταιριάζουν με τη δική σας MySQL εγκατάσταση.

3.  **Εκτέλεση:**
    * Τοποθετήστε τον φάκελο στο `htdocs` (αν χρησιμοποιείτε XAMPP).
    * Ανοίξτε τον browser στο `http://localhost/helpdesk` (ή το όνομα του φακέλου σας).

## 🔐 Στοιχεία Εισόδου (Demo Credentials)
* **Username:** admin
* **Password:** 1234

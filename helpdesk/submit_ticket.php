<?php
include 'db.php';

$successMsg = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];

    // Εδώ δεν έχουμε session user, οπότε απλά το αποθηκεύουμε
    $stmt = $pdo->prepare("INSERT INTO tickets (title, description, priority) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$title, $description, $priority])) {
        $successMsg = "Το αίτημά σας καταχωρήθηκε επιτυχώς! Ένας τεχνικός θα το αναλάβει σύντομα.";
    } else {
        $errorMsg = "Υπήρξε πρόβλημα κατά την καταχώρηση.";
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Δήλωση Βλάβης</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style> body { background-color: #e9ecef; } </style>
</head>
<body class="py-5">
    <div class="container" style="max-width: 600px;">
        
        <div class="mb-3 text-end">
            <a href="login.php" class="text-decoration-none text-secondary">
                <i class="fas fa-sign-in-alt"></i> Είσοδος Διαχειριστή
            </a>
        </div>

        <div class="card shadow-lg border-0">
            <div class="card-header bg-dark text-white p-4 text-center">
                <h3 class="mb-0">IT Support Center</h3>
                <small>Φόρμα Αναφοράς Προβλημάτων</small>
            </div>
            <div class="card-body p-5">
                
                <?php if($successMsg): ?>
                    <div class="alert alert-success shadow-sm" role="alert">
                        <h4 class="alert-heading">Επιτυχία!</h4>
                        <p><?= $successMsg ?></p>
                        <hr>
                        <p class="mb-0">Μπορείτε να κλείσετε τη σελίδα ή να <a href="submit_ticket.php" class="alert-link">δηλώσετε νέα βλάβη</a>.</p>
                    </div>
                <?php elseif($errorMsg): ?>
                    <div class="alert alert-danger"><?= $errorMsg ?></div>
                <?php else: ?>

                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Θέμα Προβλήματος</label>
                        <input type="text" name="title" class="form-control form-control-lg" required placeholder="π.χ. Δεν λειτουργεί ο εκτυπωτής">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Προτεραιότητα</label>
                        <select name="priority" class="form-select">
                            <option value="Low">Low (Δεν επείγει)</option>
                            <option value="Medium" selected>Medium (Κανονική)</option>
                            <option value="High">High (Επείγον!)</option>
                        </select>
                        <div class="form-text">Επιλέξτε "High" μόνο αν έχει σταματήσει η εργασία σας.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Περιγραφή</label>
                        <textarea name="description" class="form-control" rows="5" required placeholder="Περιγράψτε τι συνέβη... (Προαιρετικά γράψτε και το όνομά σας εδώ)"></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Αποστολή Αναφοράς</button>
                    </div>
                </form>
                <?php endif; ?>

            </div>
        </div>
        <div class="text-center mt-3 text-muted">
            <small>&copy; IT Department Helpdesk System</small>
        </div>
    </div>
</body>
</html>
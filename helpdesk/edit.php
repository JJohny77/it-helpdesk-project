<?php
session_start();
// Έλεγχος ασφαλείας
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = $_GET['id'];

// --- UPDATE ΛΟΓΙΚΗ (ΤΟ 'U' ΤΟΥ CRUD) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Παίρνουμε μόνο αυτά που επιτρέπεται να αλλάξει ο Admin
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $resolution = $_POST['resolution']; // Η απάντηση του τεχνικού

    // Ενημερώνουμε ΜΟΝΟ την κατάσταση και τη λύση. ΟΧΙ το αρχικό πρόβλημα.
    $stmt = $pdo->prepare("UPDATE tickets SET priority=?, status=?, resolution=? WHERE id=?");
    $stmt->execute([$priority, $status, $resolution, $id]);

    header("Location: index.php");
    exit;
}

// Τραβάμε τα στοιχεία για να τα δείξουμε
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) die("Το αίτημα δεν βρέθηκε.");
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Διαχείριση Αιτήματος #<?= $ticket['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="py-5">
    <div class="container" style="max-width: 800px;">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Ticket #<?= $ticket['id'] ?></h4>
                <span class="badge bg-light text-dark">Ημ/νία: <?= date('d/m/Y', strtotime($ticket['created_at'])) ?></span>
            </div>
            <div class="card-body p-4">
                
                <form method="POST" action="">
                    
                    <h5 class="text-secondary border-bottom pb-2 mb-3">Αναφορά Χρήστη (Μη επεξεργάσιμη)</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Θέμα</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($ticket['title']) ?>" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Περιγραφή Προβλήματος</label>
                        <textarea class="form-control bg-light" rows="4" readonly><?= htmlspecialchars($ticket['description']) ?></textarea>
                    </div>

                    <h5 class="text-primary border-bottom pb-2 mb-3 mt-5">Ενέργειες Διαχειριστή</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Προτεραιότητα</label>
                            <select name="priority" class="form-select">
                                <option value="Low" <?= $ticket['priority'] == 'Low' ? 'selected' : '' ?>>Low</option>
                                <option value="Medium" <?= $ticket['priority'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="High" <?= $ticket['priority'] == 'High' ? 'selected' : '' ?>>High</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Κατάσταση (Status)</label>
                            <select name="status" class="form-select">
                                <option value="Open" <?= $ticket['status'] == 'Open' ? 'selected' : '' ?>>Open (Ανοιχτό)</option>
                                <option value="In Progress" <?= $ticket['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress (Σε εξέλιξη)</option>
                                <option value="Closed" <?= $ticket['status'] == 'Closed' ? 'selected' : '' ?>>Closed (Ολοκληρωμένο)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-primary">Απάντηση / Λύση Τεχνικού</label>
                        <textarea name="resolution" class="form-control border-primary" rows="4" placeholder="Γράψτε εδώ τις ενέργειες που κάνατε ή τη λύση του προβλήματος..."><?= htmlspecialchars($ticket['resolution'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="index.php" class="btn btn-secondary">Πίσω στη Λίστα</a>
                        <button type="submit" class="btn btn-primary px-5 btn-lg">Ενημέρωση Ticket</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
</body>
</html>
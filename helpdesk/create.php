<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];

    $stmt = $pdo->prepare("INSERT INTO tickets (title, description, priority) VALUES (?, ?, ?)");
    if ($stmt->execute([$title, $description, $priority])) {
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Νέο Αίτημα</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="py-5">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Καταχώρηση Νέας Βλάβης</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Θέμα / Τίτλος</label>
                        <input type="text" name="title" class="form-control" required placeholder="π.χ. Δεν λειτουργεί το Wi-Fi">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Προτεραιότητα</label>
                        <select name="priority" class="form-select">
                            <option value="Low">Low (Χαμηλή)</option>
                            <option value="Medium">Medium (Μεσαία)</option>
                            <option value="High">High (Υψηλή)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Περιγραφή Προβλήματος</label>
                        <textarea name="description" class="form-control" rows="5" required></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Ακύρωση</a>
                        <button type="submit" class="btn btn-success px-4">Αποθήκευση</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
</body>
</html>
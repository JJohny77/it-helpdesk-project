<?php
session_start(); // Υπάρχει ήδη στο index.php, βεβαιώσου ότι είναι παντού
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

// Τραβάμε όλα τα tickets από τη βάση
$stmt = $pdo->query("SELECT * FROM tickets ORDER BY created_at DESC");
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>IT Helpdesk Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 50px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">IT Helpdesk System</span>
            <div>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Διαχείριση Αιτημάτων (Tickets)</h2>
            <a href="create.php" class="btn btn-success">+ Νέο Αίτημα</a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Θέμα</th>
                            <th>Προτεραιότητα</th>
                            <th>Κατάσταση</th>
                            <th>Ημερομηνία</th>
                            <th>Ενέργειες</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td>#<?= $ticket['id'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($ticket['title']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars(substr($ticket['description'], 0, 50)) ?>...</small>
                                </td>
                                <td>
                                    <?php 
                                        $pBadge = 'bg-secondary';
                                        if($ticket['priority'] == 'High') $pBadge = 'bg-danger';
                                        if($ticket['priority'] == 'Medium') $pBadge = 'bg-warning text-dark';
                                        if($ticket['priority'] == 'Low') $pBadge = 'bg-success';
                                    ?>
                                    <span class="badge <?= $pBadge ?>"><?= $ticket['priority'] ?></span>
                                </td>
                                <td>
                                    <?php 
                                        $sBadge = 'bg-primary';
                                        if($ticket['status'] == 'Closed') $sBadge = 'bg-secondary';
                                        if($ticket['status'] == 'In Progress') $sBadge = 'bg-info text-dark';
                                    ?>
                                    <span class="badge <?= $sBadge ?>"><?= $ticket['status'] ?></span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($ticket['created_at'])) ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $ticket['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="delete.php?id=<?= $ticket['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Είστε σίγουρος ότι θέλετε να διαγράψετε το αίτημα;')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($tickets)): ?>
                            <tr><td colspan="6" class="text-center">Δεν υπάρχουν αιτήματα.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>s
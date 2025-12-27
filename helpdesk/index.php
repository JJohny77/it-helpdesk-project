<?php
session_start();
// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

// --- ΣΤΑΤΙΣΤΙΚΑ (DASHBOARD WIDGETS) ---
// 1. Συνολικά Tickets
$totalTickets = $pdo->query("SELECT COUNT(*) FROM tickets")->fetchColumn();

// 2. Ανοιχτά Tickets (Εκκρεμότητες)
$openTickets = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'Open' OR status = 'In Progress'")->fetchColumn();

// 3. Υψηλής Προτεραιότητας (Επείγοντα - που δεν έχουν κλείσει)
$urgentTickets = $pdo->query("SELECT COUNT(*) FROM tickets WHERE priority = 'High' AND status != 'Closed'")->fetchColumn();


// --- ΛΟΓΙΚΗ ΑΝΑΖΗΤΗΣΗΣ ΚΑΙ ΦΙΛΤΡΩΝ ---
$sql = "SELECT * FROM tickets WHERE 1=1";
$params = [];

if (!empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $sql .= " AND (title LIKE ? OR description LIKE ?)";
    $params[] = "%$searchTerm%"; 
    $params[] = "%$searchTerm%";
}

if (!empty($_GET['status_filter'])) {
    $statusFilter = $_GET['status_filter'];
    $sql .= " AND status = ?";
    $params[] = $statusFilter;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-dark mb-5 shadow">
        <div class="container">
            <span class="navbar-brand mb-0 h1 fw-bold text-uppercase">
                <i class="fas fa-shield-alt me-2"></i>Admin Panel
            </span>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 small">
                    <i class="fas fa-user me-1"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                </span>
                <a href="logout.php" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold text-secondary mb-0">Επισκόπηση Συστήματος</h2>
                <p class="text-muted small">Συγκεντρωτική εικόνα και διαχείριση αιτημάτων.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="export.php" class="btn btn-success text-white shadow-sm">
                    <i class="fas fa-file-excel me-2"></i>Εξαγωγή σε Excel
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-0 opacity-75 small">Συνολικα Αιτηματα</h6>
                            <h2 class="display-6 fw-bold mb-0"><?= $totalTickets ?></h2>
                        </div>
                        <i class="fas fa-folder-open fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-warning text-dark shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-0 opacity-75 small fw-bold">Εκκρεμουν</h6>
                            <h2 class="display-6 fw-bold mb-0"><?= $openTickets ?></h2>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-danger text-white shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-0 opacity-75 small">Επειγοντα (High)</h6>
                            <h2 class="display-6 fw-bold mb-0"><?= $urgentTickets ?></h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-body bg-white mb-4 shadow-sm border-0 rounded-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Αναζήτηση..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <select name="status_filter" class="form-select">
                        <option value="">Κατάσταση: Όλα</option>
                        <option value="Open" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Open') ? 'selected' : '' ?>>Open (Ανοιχτά)</option>
                        <option value="In Progress" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] == 'In Progress') ? 'selected' : '' ?>>In Progress (Σε εξέλιξη)</option>
                        <option value="Closed" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Closed') ? 'selected' : '' ?>>Closed (Κλειστά)</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-filter"></i> Φίλτρο</button>
                    <?php if(!empty($_GET['search']) || !empty($_GET['status_filter'])): ?>
                        <a href="index.php" class="btn btn-outline-secondary" title="Καθαρισμός"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card shadow border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 text-uppercase small fw-bold">ID</th>
                            <th class="text-uppercase small fw-bold">Θέμα & Περιγραφή</th>
                            <th class="text-uppercase small fw-bold">Προτεραιότητα</th>
                            <th class="text-uppercase small fw-bold">Κατάσταση</th>
                            <th class="text-uppercase small fw-bold">Ημ/νία</th>
                            <th class="text-end pe-4 text-uppercase small fw-bold">Ενέργειες</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#<?= $ticket['id'] ?></td>
                                <td style="max-width: 300px;">
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($ticket['title']) ?></div>
                                    <div class="text-muted small text-truncate"><?= htmlspecialchars($ticket['description']) ?></div>
                                </td>
                                <td>
                                    <?php 
                                        $pBadge = 'bg-secondary';
                                        if($ticket['priority'] == 'High') $pBadge = 'bg-danger';
                                        if($ticket['priority'] == 'Medium') $pBadge = 'bg-warning text-dark';
                                        if($ticket['priority'] == 'Low') $pBadge = 'bg-success';
                                    ?>
                                    <span class="badge rounded-pill <?= $pBadge ?>"><?= $ticket['priority'] ?></span>
                                </td>
                                <td>
                                    <?php 
                                        $sBadge = 'bg-primary';
                                        if($ticket['status'] == 'Closed') $sBadge = 'bg-secondary';
                                        if($ticket['status'] == 'In Progress') $sBadge = 'bg-info text-dark';
                                    ?>
                                    <span class="badge rounded-pill <?= $sBadge ?>"><?= $ticket['status'] ?></span>
                                </td>
                                <td class="text-muted small"><?= date('d/m/Y', strtotime($ticket['created_at'])) ?></td>
                                
                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="edit.php?id=<?= $ticket['id'] ?>" class="btn btn-sm btn-outline-primary" title="Επεξεργασία / Επίλυση">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete.php?id=<?= $ticket['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Διαγραφή"
                                           onclick="confirmDelete(event)">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($tickets)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted opacity-50">
                                        <i class="fas fa-clipboard-check fa-3x mb-3"></i>
                                        <p>Δεν βρέθηκαν αιτήματα.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
</body>
</html>
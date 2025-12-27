<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit("Access Denied");
}
include 'db.php';

$filename = "tickets_export_" . date('Y-m-d') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

$output = fopen('php://output', 'w');

// BOM για Ελληνικά
fputs($output, "\xEF\xBB\xBF");

// --- ΑΛΛΑΓΗ 1: Προσθέσαμε στήλη "Ώρα" στις επικεφαλίδες ---
fputcsv($output, array('ID', 'Θέμα', 'Περιγραφή', 'Προτεραιότητα', 'Κατάσταση', 'Λύση', 'Ημερομηνία', 'Ώρα'), ";");

$stmt = $pdo->query("SELECT id, title, description, priority, status, resolution, created_at FROM tickets ORDER BY created_at DESC");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Καθαρισμός κειμένων
    $title = str_replace(array("\n", "\r"), ' ', $row['title']);
    $desc = str_replace(array("\n", "\r"), ' ', $row['description']);
    $res = str_replace(array("\n", "\r"), ' ', $row['resolution'] ?? '');

    // --- ΑΛΛΑΓΗ 2: Σπάμε την ημερομηνία και την ώρα ---
    $dateOnly = date('d/m/Y', strtotime($row['created_at']));
    $timeOnly = date('H:i', strtotime($row['created_at']));

    // Φτιάχνουμε τη γραμμή με τα νέα δεδομένα
    $lineData = array(
        $row['id'],
        $title,
        $desc,
        $row['priority'],
        $row['status'],
        $res,
        $dateOnly, // Στήλη G
        $timeOnly  // Στήλη H
    );

    fputcsv($output, $lineData, ";");
}

fclose($output);
exit;
?>
<?php
session_start();
include 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Λάθος όνομα χρήστη ή κωδικός.";
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Login - IT Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <style>
        body { display: flex; align-items: center; justify-content: center; height: 100vh; background-color: #212529; }
        .card { width: 100%; max-width: 400px; }
    </style>
</head>
<body>
    <div class="card shadow-lg">
        <div class="card-body p-5">
            <h3 class="text-center mb-4 fw-bold">Είσοδος</h3>
            
            <?php if($error): ?>
                <div class="alert alert-danger shadow-sm"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-muted">Username</label>
                    <input type="text" name="username" class="form-control form-control-lg" required>
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">Login</button>

                <div class="text-center border-top pt-3">
                    <p class="text-muted small">Δεν είστε διαχειριστής;</p>
                    <a href="submit_ticket.php" class="btn btn-outline-dark w-100">
                        Δήλωση Βλάβης (Υπάλληλοι)
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script> </body>
</html>
<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = DbConnection::make();

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT id, firstname, lastname, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['name']    = $user['firstname'] . ' ' . $user['lastname'];

            header('Location: index.php');
            exit;
        } else {
            $error = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SaaS Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
<div class="w-full max-w-md bg-white rounded-2xl shadow p-8">
    <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

    <?php if ($error): ?>
        <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-3 py-2">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" required
                   class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring focus:border-gray-500">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring focus:border-gray-500">
        </div>
        <button type="submit"
                class="w-full py-2 rounded-xl text-sm font-semibold bg-gray-900 text-white hover:bg-gray-800">
            Masuk
        </button>
    </form>

    <p class="mt-4 text-xs text-gray-500 text-center">
        User baru bisa dibuat oleh Super Admin di menu <strong>User</strong>.
    </p>
</div>
</body>
</html>

<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config/db.php';

// Wajib login & wajib super_admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'super_admin') {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

$pdo = DbConnection::make();

// Handle POST: add user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname  = trim($_POST['lastname'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = trim($_POST['password'] ?? '');
    $role      = $_POST['role'] ?? 'user';

    if ($firstname && $lastname && $email && $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare('INSERT INTO users (firstname, lastname, email, password_hash, role) VALUES (?,?,?,?,?)');
        $stmt->execute([$firstname, $lastname, $email, $hash, $role]);
    }
    header('Location: users.php');
    exit;
}

// Handle GET delete
if (($_GET['action'] ?? '') === 'delete' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: users.php');
    exit;
}

// Ambil semua user
$stmt = $pdo->query('SELECT id, firstname, lastname, email, role, created_at FROM users ORDER BY id DESC');
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-900 text-sm">
<div class="p-4">
    <table class="min-w-full border text-xs">
        <thead>
        <tr class="bg-gray-100">
            <th class="border px-2 py-1 text-left">ID</th>
            <th class="border px-2 py-1 text-left">Nama</th>
            <th class="border px-2 py-1 text-left">Email</th>
            <th class="border px-2 py-1 text-left">Role</th>
            <th class="border px-2 py-1 text-left">Created</th>
            <th class="border px-2 py-1 text-left">Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td class="border px-2 py-1"><?= (int)$u['id'] ?></td>
                <td class="border px-2 py-1">
                    <?= htmlspecialchars($u['firstname'] . ' ' . $u['lastname']) ?>
                </td>
                <td class="border px-2 py-1"><?= htmlspecialchars($u['email']) ?></td>
                <td class="border px-2 py-1"><?= htmlspecialchars($u['role']) ?></td>
                <td class="border px-2 py-1"><?= htmlspecialchars($u['created_at']) ?></td>
                <td class="border px-2 py-1">
                    <a href="users.php?action=delete&id=<?= (int)$u['id'] ?>"
                       onclick="return confirm('Hapus user ini?')"
                       class="text-red-600 hover:underline">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$users): ?>
            <tr>
                <td colspan="6" class="border px-2 py-2 text-center text-gray-500">
                    Belum ada user.
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

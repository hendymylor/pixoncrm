<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$currentUserId = (int) $_SESSION['user_id'];
$currentRole   = $_SESSION['role'] ?? 'user';
$currentName   = $_SESSION['name'] ?? 'User';

// ambil flash (kalau ada)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SaaS Multi User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<!-- TOAST -->
<div id="toast"
     class="fixed top-4 right-4 z-50 hidden transform transition-all duration-300">
    <div id="toast-inner"
         class="px-4 py-3 rounded-xl shadow-lg text-sm flex items-center gap-2">
        <span id="toast-icon">✔️</span>
        <span id="toast-message"></span>
    </div>
</div>

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col">
        <div class="p-4 border-b border-gray-700">
            <div class="text-xl font-bold">SaaS Panel</div>
            <div class="text-xs text-gray-400 mt-1">
                Halo, <span class="font-semibold text-white"><?= htmlspecialchars($currentName) ?></span>
            </div>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <button data-target="dashboard"
                    class="nav-link w-full text-left px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm font-medium">
                Dashboard
            </button>
            <button data-target="settings"
                    class="nav-link w-full text-left px-3 py-2 rounded-lg hover:bg-gray-800 text-sm font-medium">
                Setting
            </button>

            <?php if ($currentRole === 'super_admin'): ?>
                <button data-target="users"
                        class="nav-link w-full text-left px-3 py-2 rounded-lg hover:bg-gray-800 text-sm font-medium">
                    User
                </button>
            <?php endif; ?>
        </nav>
        <div class="p-4 border-t border-gray-700 text-xs text-gray-400 flex items-center justify-between">
            <div>
                Role: <span class="font-semibold text-white"><?= htmlspecialchars($currentRole) ?></span>
            </div>
            <a href="logout.php"
               class="ml-2 text-red-400 hover:text-red-300 hover:underline">
                Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <!-- DASHBOARD -->
        <section id="dashboard" class="page-section">
            <h1 class="text-2xl font-semibold mb-4">Dashboard</h1>
            <div class="bg-white rounded-2xl shadow p-6 max-w-xl">
                <h2 class="text-lg font-semibold mb-4">
                    Form Kontak & WhatsApp
                </h2>
                <form id="contactForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Firstname</label>
                        <input type="text" name="firstname" required
                               class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring focus:border-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lastname</label>
                        <input type="text" name="lastname" required
                               class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring focus:border-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required
                               class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring focus:border-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No WhatsApp</label>
                        <input type="text" name="whatsapp" required
                               placeholder="8xxxxxxxxxx"
                               class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring focus:border-gray-500">
                    </div>

                    <input type="hidden" name="user_id" value="<?= $currentUserId ?>">

                    <button type="submit"
                            class="w-full py-2 rounded-xl text-sm font-semibold bg-gray-900 text-white hover:bg-gray-800">
                        Kirim ke API
                    </button>
                </form>
                <div id="contactResult" class="mt-4 text-sm"></div>
            </div>
        </section>

        <!-- SETTINGS -->
        <section id="settings" class="page-section hidden">
            <h1 class="text-2xl font-semibold mb-4">Setting API</h1>
            <div class="bg-white rounded-2xl shadow p-6 max-w-xl">
                <form action="save_settings.php" method="POST" class="space-y-4">
                    <input type="hidden" name="user_id" value="<?= $currentUserId ?>">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">API URL</label>
                        <input type="text" name="api_url" required
                               placeholder="https://example.com/api/send"
                               class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">API Token (Bearer)</label>
                        <input type="text" name="api_token" required
                               class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Template Name</label>
                        <input type="text" name="template_name" required value="welcome_message"
                               class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bahasa (template_language)</label>
                        <input type="text" name="language" required value="en"
                               class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Phone Number ID</label>
                        <input type="text" name="from_phone_number_id" required
                               placeholder="890304274172282"
                               class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>

                    <button type="submit"
                            class="w-full py-2 rounded-xl text-sm font-semibold bg-gray-900 text-white hover:bg-gray-800">
                        Simpan Setting
                    </button>
                </form>
            </div>
        </section>

        <!-- USERS (khusus super_admin) -->
        <?php if ($currentRole === 'super_admin'): ?>
            <section id="users" class="page-section hidden">
                <h1 class="text-2xl font-semibold mb-4">User Management</h1>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl shadow p-4">
                        <h2 class="text-lg font-semibold mb-3">Daftar User</h2>
                        <iframe src="users.php" class="w-full h-64 border rounded-xl"></iframe>
                        <p class="text-xs text-gray-500 mt-2">
                            Halaman <code>users.php</code> menampilkan list, tambah, dan hapus user.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl shadow p-4">
                        <h2 class="text-lg font-semibold mb-3">Tambah User</h2>
                        <form action="users.php" method="POST" class="space-y-3">
                            <input type="hidden" name="action" value="add">
                            <div>
                                <label class="block text-xs font-medium mb-1">Firstname</label>
                                <input name="firstname" required class="w-full border rounded-xl px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium mb-1">Lastname</label>
                                <input name="lastname" required class="w-full border rounded-xl px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium mb-1">Email</label>
                                <input type="email" name="email" required class="w-full border rounded-xl px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium mb-1">Password</label>
                                <input type="password" name="password" required class="w-full border rounded-xl px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium mb-1">Role</label>
                                <select name="role" class="w-full border rounded-xl px-3 py-2 text-sm">
                                    <option value="user">User</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                            </div>
                            <button type="submit"
                                    class="w-full py-2 rounded-xl text-sm font-semibold bg-gray-900 text-white hover:bg-gray-800">
                                Simpan User
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
</div>

<script>
// Toast util
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastInner = document.getElementById('toast-inner');
    const toastMessage = document.getElementById('toast-message');
    const toastIcon = document.getElementById('toast-icon');

    toastMessage.textContent = message;

    toastInner.className = 'px-4 py-3 rounded-xl shadow-lg text-sm flex items-center gap-2';

    if (type === 'success') {
        toastInner.classList.add('bg-emerald-600', 'text-white');
        toastIcon.textContent = '✔️';
    } else {
        toastInner.classList.add('bg-red-600', 'text-white');
        toastIcon.textContent = '⚠️';
    }

    toast.classList.remove('hidden', 'opacity-0', 'translate-y-2');
    toast.classList.add('opacity-100');

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 300);
    }, 3000);
}

// Toggle menu
document.querySelectorAll('.nav-link').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.getAttribute('data-target');
        document.querySelectorAll('.page-section').forEach(sec => sec.classList.add('hidden'));
        document.getElementById(target).classList.remove('hidden');

        document.querySelectorAll('.nav-link').forEach(b => b.classList.remove('bg-gray-800'));
        btn.classList.add('bg-gray-800');
    });
});

// Submit dashboard form -> send.php
const contactForm = document.getElementById('contactForm');
const contactResult = document.getElementById('contactResult');

if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        contactResult.textContent = 'Mengirim...';
        contactResult.className = 'mt-4 text-sm text-gray-600';

        const formData = new FormData(contactForm);

        try {
            const response = await fetch('send.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                contactResult.className = 'mt-4 text-sm text-green-600';
                contactResult.textContent = 'Berhasil mengirim ke API.';
                contactForm.reset();
                showToast('Berhasil mengirim data ke API.', 'success');
            } else {
                contactResult.className = 'mt-4 text-sm text-red-600';
                contactResult.textContent = 'Gagal: ' + (data.message || data.error || 'Unknown error');
                showToast('Gagal mengirim ke API.', 'error');
            }
        } catch (err) {
            contactResult.className = 'mt-4 text-sm text-red-600';
            contactResult.textContent = 'Error jaringan: ' + err.message;
            showToast('Error jaringan: ' + err.message, 'error');
        }
    });
}

// Jalankan toast dari PHP flash (setting, dll)
<?php if ($flash): ?>
    showToast(
        <?= json_encode($flash['message']) ?>,
        <?= json_encode($flash['type']) ?>
    );
<?php endif; ?>
</script>
</body>
</html>

<?php
session_start();
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard PHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 bg-gray-900 text-gray-100">
<div class="container mx-auto max-w-xl">
<h1 class="text-2xl font-bold mb-4">PHP Leaderboard Example</h1>
<?php if($isAdmin): ?>
<p class="text-green-400 mb-2">Zalogowano jako admin.</p>
<button id="logoutBtn" class="bg-red-600 px-3 py-1 rounded text-white mb-4">Wyloguj</button>
<?php else: ?>
<div class="mb-4">
    <input type="text" id="adminLogin" placeholder="Login" class="border p-2 text-black">
    <input type="password" id="adminPassword" placeholder="Hasło" class="border p-2 text-black">
    <button id="adminLoginBtn" class="bg-blue-600 px-3 py-1 rounded text-white">Zaloguj jako admin</button>
</div>
<?php endif; ?>
<div id="content">Tu można dodać dalszą część aplikacji (JS jak w oryginale).</div>
</div>
<script>
function adminLogin() {
    const login = document.getElementById('adminLogin').value;
    const password = document.getElementById('adminPassword').value;
    fetch('login.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `login=${encodeURIComponent(login)}&password=${encodeURIComponent(password)}`
    }).then(r=>r.json()).then(d=>{ if(d.success){location.reload();} else {alert('Błąd logowania');}});
}
function logout(){
    fetch('logout.php').then(()=>location.reload());
}
<?php if(!$isAdmin): ?>
document.getElementById('adminLoginBtn').addEventListener('click', adminLogin);
<?php else: ?>
document.getElementById('logoutBtn').addEventListener('click', logout);
<?php endif; ?>
</script>
</body>
</html>

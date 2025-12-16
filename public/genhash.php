<?php
// GANTI 'admin123' dengan password yang kamu mau
$password = 'Akuang742748';

$hash = password_hash($password, PASSWORD_BCRYPT);

echo 'Password: ' . $password . "<br>";
echo 'Hash: ' . $hash . "<br>";
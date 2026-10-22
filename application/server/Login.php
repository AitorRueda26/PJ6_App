<?php
// 1. Leer el JSON (misma carpeta que Login.php)
$usuarios = json_decode(file_get_contents(__DIR__ .. '../users/users.json'), true);

// 2. Recoger los datos: GET o POST, lo que llegue
$user = $_GET['user']  ?? $_POST['user']  ?? null;
$pass = $_GET['password'] ?? $_POST['password'] ?? null;

// 3. Verificar
$ok = false;
foreach ($usuarios as $u) {
    if ($user === $u['user'] && $pass === $u['password']) {
        $ok = true;
        break;
    }
}

// 4. Responder
if ($ok) {
    echo "Welcome to the command options of the " . htmlspecialchars($user) . "<br>";
} else {
    echo "No tens permís per accedir a aquesta web<br>";
}

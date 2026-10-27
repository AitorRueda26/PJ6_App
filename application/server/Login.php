<?php
$json = file_get_contents(__DIR__ . '/../../users/users.json');
$datos = json_decode(preg_replace('/^\xEF\xBB\xBF/', '', $json), true);

$nombre = trim($_REQUEST['name'] ?? '');
$passwd = trim($_REQUEST['passwd'] ?? '');

$ok = false;
foreach ($datos as $u) {
    if (trim($u['user']) === $nombre && trim($u['password']) === $passwd) {
        $ok = true;
        break;
    }
}

if (!$ok) {
    die('Usuario o contraseña incorrectos');
}

header('Location: ./../client/interface_admin.html');
?>
<?php
$json = file_get_contents(__DIR__ . '/../../users/users.json');
$datos = json_decode(preg_replace('/^\xEF\xBB\xBF/', '', $json), true);

$nombre = trim($_REQUEST['name'] ?? '');
$passwd = trim($_REQUEST['passwd'] ?? '');

$ok = false;
foreach ($datos as $u) {
    if (trim($u['user']) === $nombre && trim($u['password']) === $passwd) {
        $ok = true;
    }
}

if (!$ok) {
    die('Usuario o contraseña incorrectos');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
</head>
<body>
    <p>welcome Admin, here you can add commands. Acces to them and seen them adn see a particular user!</p><br>
    <button><p>add commands</p></button>
</body>
</html>
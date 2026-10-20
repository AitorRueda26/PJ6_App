<?php



  if( ($_GET["name"]=="Aitor") && ($_GET["passwd"]=="qazqaz123") ) {
     echo "Benvingut ". $_GET['name']. "</br>";
     exit(0);
  }
  else {
	  echo "No tens permís per accedir a aquesta web<br>";
	  exit(1);
  }

$nombre = $_POST['name'];
$email  = $_POST['passwd'];

$archivo = '/OrderManagmentApp/users/users.json';

iif (file_exists($archivo)) {
    $datos = json_decode(file_get_contents($archivo), true) ?: [];
} else {
    $datos = [];
    file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT));
}


$datos[] = [
    'name' => $name,
    'passwd'  => $passwd
];

if (file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo 'Datos guardados correctamente.';
} else {
    http_response_code(500);    
    echo 'Error al guardar los datos.';
}
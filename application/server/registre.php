<?php
	//1- Fitxer de dades i noves dades
	$filename = "usuaris.db";
	$temporal = "usuaris.db.tmp";
	
	$dades_noves = array(
		'nom' => "joan",
		'cognoms' => "pons puig",
		'usuari' => "jpp",
		'directori' => "/home/jpp",
		'ctsnya' => password_hash("fjeclot", PASSWORD_DEFAULT)
	);
		
	//2- Comprovacion
	if (!file_exists($filename)) {
		die("El fitxer no existeix. Executa primer creaFitAfegRegist.php.\n");
	}

	$apuntador_fitxer_dades = fopen($filename, "r+");  // Obrint el fixer en mode lectura i escriptura, sense crear-lo
	if ($apuntador_fitxer_dades === false){
		die("No es pot obrir el fitxer (permisos?).\n");
	}
	
	if (!flock($apuntador_fitxer_dades, LOCK_EX)){  // bloqueig durant tota l'operació
		fclose($apuntador_fitxer_dades);
		die("No s'ha pogut bloquejar el fitxer.\n");
	}
   	
   	
   	//3- Llegir dades existents i desar-les dins de l'array dades[]
   	$dades_llegides = stream_get_contents($apuntador_fitxer_dades);
   	$dades = [];
   	
   	if (trim($dades_llegides) !== "") {
		try {
			$dades = json_decode($dades_llegides, true, 512, JSON_THROW_ON_ERROR);
			var_dump($dades);
		} catch (JsonException $e) {
			flock($apuntador_fitxer_dades, LOCK_UN);
			fclose($apuntador_fitxer_dades);
			die("El fitxer no és JSON vàlid: " . $e->getMessage() . "\n");
		}
		if (!is_array($dades)) {
			flock($apuntador_fitxer_dades, LOCK_UN);
			fclose($apuntador_fitxer_dades);
			die("El fitxer no conté una llista d'usuaris.\n");
		}
	}
	//4- Evitar duplicats pel camp "usuari"
	foreach ($dades as $dada) {
		if (($dada['usuari'] ?? null) === $dades_noves['usuari']) {
			flock($apuntador_fitxer_dades, LOCK_UN);
			fclose($apuntador_fitxer_dades);
			die("L'usuari '" . $dades_noves['usuari'] . "' ja existeix.\n");
		}
	}
	//5- Afegint dades a l'array
	$dades[]=$dades_noves;
 	//6- Afegint ara totes les dades (les anteriors i la nova) al fitxer
   	copy($filename,$temporal);
   	ftruncate($apuntador_fitxer_dades, 0); // buida el fitxer i ho desa a memòria
   	rewind($apuntador_fitxer_dades); // torna el punter al principi
	fwrite($apuntador_fitxer_dades, json_encode($dades, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)); // escriu el contingut nou
	fflush($apuntador_fitxer_dades); //Obliga a escriure en el fixer. fwrite generalment omple un buffer intern que s'envia més tard al fixer.
	flock($apuntador_fitxer_dades, LOCK_UN);
	fclose($apuntador_fitxer_dades);
	unlink($temporal);

	echo "Dades afegides amb èxit\n";
?>

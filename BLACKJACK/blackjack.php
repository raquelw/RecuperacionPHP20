<?php
	include "funciones2.php";
	
	//Almaceno en variables los valores adquiridos del formulario y los valido. Almaceno todas las apuestas en el array "$apuestas".
	//Además compruebo la cantidad mínima y máxima. Si no se encuentra en el rango correcto se pone valor "null".
	$apuesta1 = limpiarCampos($_POST['apuesta1']);
	$apuesta2 = limpiarCampos($_POST['apuesta2']);
	$apuesta3 = limpiarCampos($_POST['apuesta3']);
	$apuesta4 = limpiarCampos($_POST['apuesta4']);
	$apuestas = Array($apuesta1, $apuesta2, $apuesta3, $apuesta4);
	
	//Leemos fichero "blackjack.txt", donde se encuentra el bote que almacena cada jugador.
	$fichero = file("blackjack.txt");
	//Llamamos a la funcion "leerBotes" pasando el fichero con los botes  y el array con las apuestas como parámetro. Devuelve un array que se va a almacenar
	//en "$botes" que contiene el bote de todos los jugadores siempre y cuando sean superiores a 100€ y restando la apuesta introducida, sino el valor es null.
	$botes = leerBote($fichero, $apuestas);
	echo "$apuesta1";
	echo "$apuesta2";
	echo "$apuesta3";
	echo "$apuesta4";
	var_dump($botes);
	//Se comprueba los valores de las apuestas y de los botes, si hay algún "null", saldrá un mensaje de error y no se ejecutará el programa
	if(($apuesta1 != null) && ($apuesta2 != null) && ($apuesta3 != null) && ($apuesta4 != null) && ($botes[0] != null) && ($botes[1] != null) && ($botes[2] != null) && ($botes[3] != null)){	
		//Array "$baraja" contiene la baraja de cartas francesa, siendo "r" => rombos, "c" => corazones, "p" => picas y "t" => tréboles.
		$baraja = array("1C", "2C", "3C", "4C", "5C", "6C", "7C", "8C", "9C", "10C", "JC", "QC", "KC",
			"1T", "2T", "3T", "4T", "5T", "6T", "7T", "8T", "9T", "10T", "JT", "QT", "KT",
			"1P", "2P", "3P", "4P", "5P", "6P", "7P", "8P", "9P", "10P", "JP", "QP", "KP",
			"1R", "2R", "3R", "4R", "5R", "6R", "7R", "8R", "9R", "10R", "JR", "QR", "KR");
			
		//Almacenamos los puntos máximos de los jugadores y la banca a la hora de repartir.
		$topeJug = 15;
		$topeBanca = 17;
	
		//Se reparten las cartas a los jugadores y a la banca. Se almacenan en los arrays correspondientes. En la última posición de los arrays se guardan
		//los valores totales de las cartas.	
		//Banca
		$banca = obtenerCartas($baraja, $topeBanca);
		//Jugador 1
		$jugador1 = obtenerCartas($baraja, $topeJug);
		//Jugador 2
		$jugador2 = obtenerCartas($baraja, $topeJug);
		//Jugador 3
		$jugador3 = obtenerCartas($baraja, $topeJug);
		//Jugador 4
		$jugador4 = obtenerCartas($baraja, $topeJug);
	
		//Almacenamos en array "$ganancias" el array que devuelve la funcion "$impresionCartas" con las ganancias de todos los jugadores según la mano que 
		//hayan tenido. orden: Pos 0 => J1, 1 => J2, 2 => J3, 3 => J4.
		//Se pasan por parámetro las cartas repartidas a los jugadores y a la banca, y el array con las apuestas realizadas por cada jugador.
		$ganancias = impresionCartas($jugador1, $jugador2, $jugador3, $jugador4, $banca, $apuestas);
		
		//Se llama a la funcion "actualizarFicheroBote" pasando por parametros el array "$ganancias" que contiene las ganancias de todos los jugadores y
		// el array "$botes" que contiene el bote de cada jugador.La funcion va a sobreescribir el fichero "blackjack.txt" con los nuevos botes dependiendo
		//de si los jugadores han ganado, perdido o empatado.
		actualizarFicheroBote($ganancias, $botes);
	}else{
		echo "LA APUESTA MÍNIMA ES DE 5 EUROS Y LA MÁXIMA DE 50 EUROS. COMPRUEBEN LOS IMPORTES INTRODUCIDOS";
	}
?>
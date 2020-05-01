<?php
	include "funciones.php";
	
	//Array "$baraja" contiene la baraja de cartas francesa, siendo "r" => rombos, "c" => corazones, "p" => picas y "t" => tréboles.
	$baraja = array("1c", "2c", "3c", "4c", "5c", "6c", "7c", "8c", "9c", "10c", "Jc", "Qc", "Kc",
			"1t", "2t", "3t", "4t", "5t", "6t", "7t", "8t", "9t", "10t", "Jt", "Qt", "Kt",
			"1p", "2p", "3p", "4p", "5p", "6p", "7p", "8p", "9p", "10p", "Jp", "Qp", "Kp",
			"1r", "2r", "3r", "4r", "5r", "6r", "7r", "8r", "9r", "10r", "Jr", "Qr", "Kr");
	//Variable "$cartasjugadores" es el nº de cartas que se reparte a cada jugador.
	$cartasjugadores = 2;
	// Variable "$cartasmesa" es el nº de cartas que se pone en la mesa.
	$cartasmesa = 3;
	
	//Se llama a la funcion 'generarCartas' pasando por parametro la baraja (original la primera vez y modificada el resto) y el numero de cartas a repartir.
	//El array, con las cartas repartidas, devuelto se almacena en variables: $j1 => jugador1, $j2 => jugador2, $j3 => jugador3, $j4 => jugador4, 
	//$mesa => cartas de la mesa.
	//Entre cada llamada a la funcion anterior se llama a la funcion 'eliminarRepartidas' pasando por parámetro la baraja y las cartas repartidas por ultima vez.
	//La funcion modifica las cartas repartidas a 'null' y devuelve la nueva baraja actualizada.
	$j1 = generarCartas($baraja, $cartasjugadores);
	$baraja = eliminarRepartidas($baraja, $j1);
	echo "jugador1";
	var_dump($j1);	
	echo "Baraja despues del jugador 1";
	var_dump($baraja);
	$j2 = generarCartas($baraja, $cartasjugadores);
	$baraja = eliminarRepartidas($baraja, $j2);
	echo "jugador 2";
	var_dump($j2);
	echo "baraja despues del jugador 2";
	var_dump($baraja);
	$j3 = generarCartas($baraja, $cartasjugadores);			 //ERRORRRR!!!!  
	$baraja = eliminarRepartidas($baraja, $j3);				//1 VEZ ME HA REPETIDO CARTA, CUANDO REPITE ANULA LA PRIMERA DE LA BARAJA
	echo "jugador 3";
	var_dump($j3);
	echo "baraja despues del jugador 3";
	var_dump($baraja);
	$j4 = generarCartas($baraja, $cartasjugadores);
	$baraja = eliminarRepartidas($baraja, $j4);
	echo "jugador 4";
	var_dump($j4);
	echo "baraja despues del jugador 4";
	var_dump($baraja);
	$mesa = generarCartas($baraja, $cartasmesa);
	$baraja = eliminarRepartidas($baraja, $mesa);
	echo "mesa";
	var_dump($mesa);
	echo "baraja despeus de la mesa.";
	var_dump($baraja);
	
	// $a = Array("1c", "3c");
	// $b = Array("10c", "Kc", "5c");
	// $mano1 = comprobarJugadas($a, $b);
	// echo $mano1;

	
	//Obtenemos la mano de cada jugador llamando a la funcion 'comprobarJugadas'. Se le pasa por parámetros las cartas de cada jugador y las de la mesa.	
	$mano1 = comprobarJugadas($j1, $mesa);
	$mano2 = comprobarJugadas($j2, $mesa);
	$mano3 = comprobarJugadas($j3, $mesa);
	$mano4 = comprobarJugadas($j4, $mesa);
	
	// comprobarJugadas($j4, $mesa);


?>
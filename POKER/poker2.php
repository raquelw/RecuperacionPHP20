<?php
	include "funciones2.php";
	
	//Array "$baraja" contiene la baraja de cartas francesa, siendo "r" => rombos, "c" => corazones, "p" => picas y "t" => tréboles.
	$baraja = array("1C", "2C", "3C", "4C", "5C", "6C", "7C", "8C", "9C", "10C", "JC", "QC", "KC",
			"1T", "2T", "3T", "4T", "5T", "6T", "7T", "8T", "9T", "10T", "JT", "QT", "KT",
			"1P", "2P", "3P", "4P", "5P", "6P", "7P", "8P", "9P", "10P", "JP", "QP", "KP",
			"1R", "2R", "3R", "4R", "5R", "6R", "7R", "8R", "9R", "10R", "JR", "QR", "KR");
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
	$j2 = generarCartas($baraja, $cartasjugadores);
	$baraja = eliminarRepartidas($baraja, $j2);
	$j3 = generarCartas($baraja, $cartasjugadores);			  
	$baraja = eliminarRepartidas($baraja, $j3);			
	$j4 = generarCartas($baraja, $cartasjugadores);
	$baraja = eliminarRepartidas($baraja, $j4);
	$mesa = generarCartas($baraja, $cartasmesa);
	$baraja = eliminarRepartidas($baraja, $mesa);

	//Obtenemos la mano de cada jugador llamando a la funcion 'comprobarJugadas'. Se le pasa por parámetros las cartas de cada jugador y las de la mesa.	
	$mano1 = comprobarJugadas($j1, $mesa);
	$mano2 = comprobarJugadas($j2, $mesa);
	$mano3 = comprobarJugadas($j3, $mesa);
	$mano4 = comprobarJugadas($j4, $mesa);
	
	imprimirResultado($mano1, $mano2, $mano3, $mano4, $j1, $j2, $j3, $j4, $mesa);
?>
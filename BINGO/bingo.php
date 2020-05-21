<?php
	include "funciones.php";
	
	//Se sacan los 3 cartones por cada jugador llamando a la funcion "generarCarton". No se pasa nada por parámetro.
	$jugador11 = generarCarton();
	$jugador12 = generarCarton();
	$jugador13 = generarCarton();
	$jugador21 = generarCarton();
	$jugador22 = generarCarton();
	$jugador23 = generarCarton();
	$jugador31 = generarCarton();
	$jugador32 = generarCarton();
	$jugador33 = generarCarton();
	$jugador41 = generarCarton();
	$jugador42 = generarCarton();
	$jugador43 = generarCarton();
	
	//Unifico todos los cartones de los 4 jugadores en un array multidimensional
	$cartones = Array(
		1 => Array(
			$jugador11,
			$jugador12,
			$jugador13
		),
		2 => Array(
			$jugador21,
			$jugador22,
			$jugador23
		),
		3 => Array(
			$jugador31,
			$jugador32,
			$jugador33
		),
		4 => Array(
			$jugador41,
			$jugador42,
			$jugador43,
		)
	);
	
	
	
	//Función "imprimirResultado" se le pasa por parámetro el array multidimensional con todos los 3 cartones por cada jugador.
	//La función imprime los resultados finales. No devuelve nada.
	imprimirResultado($cartones);
?>
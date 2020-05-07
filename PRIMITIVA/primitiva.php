<?php
	include "funciones.php";
	
	//Almaceno en variables los valores adquiridos del formulario y los valido.
	$fecha = limpiarCampos($_POST['fechasorteo']);
	$bote = limpiarCampos($_POST['recaudacion']);
	
	//Se llama a la funcion "$apuestaGanadora()" y se almacena en la variable "$apGan" el array con la combinación ganadora
	$apGan = apuestaGanadora();
	
	//Se abre el fichero "r22_primitiva.txt" para poder trabajar con él
	$fichero = file("r22_primitiva2.txt");
	
	//Llamo a la funcion "$obtenerJugadas" pasando por parámetro el fichero "r22_primitiva2.txt" y se almacena en array asociativo "$jugadas" todas las 
	//cobinaciones de todos los jugadores utilizando como clave el id de la apuesta.
	$jugadas = obtenerJugadas($fichero);
	
	//Se llama a la funcion "$compararResultados" pasando la apuesta ganadora y el array con todas las jugadas de los jugadores por paramétros para 
	//comprobar los aciertos de cada jugador que se almacenarán en el array "$aciertos".
	$aciertos = compararResultados($apGan, $jugadas);
	
	//Se llama a la funcion "mostrar" pasando por parámetros la fecha, el fichero "r22_primitiva.txt", el array con los aciertos, la jugada ganadora y
	//el array asociativo con las jugadas. Mostrará el resultado final por pantalla.
	mostrar($fecha, $aciertos, $apGan, $jugadas);
	
	//Llamo a la función "tratarFecha()" para almacenar en la variable "$nombreFichero" el nombre del fichero donde voy a almacenar los premios,
	//con la fecha sin guiones y bien ordenada como pide en el enunciado.
	//Paso la fecha como parámetro. Devuelve el nombre completo del fichero bien escrito.
	$nombreFichero = tratarFecha($fecha);	
	
	$fichPremios = fopen($nombreFichero, "a+");
	
	//Llamo a la función "calcularPremios()" pasando como parámetros el fichero donde apuntaremos los premios entregados, 
	//el fichero con las jugadas, el bote a repartir y el array con los aciertos;
	calcularPremios($fichPremios, $fichero, $bote, $aciertos);
?>
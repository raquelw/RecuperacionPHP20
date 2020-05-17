<?php
	//Función "limpiarCampos()" valida todos los campos recibidos del formulario eliminando espacios en blanco en los extremos, eliminando la barra
	//invertida "\", convierte caracteres especiales a entidades HTML. 
	function limpiarCampos($campoformulario){
		$campoformulario = trim($campoformulario); //elimina espacios en blanco por izquierda/derecha
		$campoformulario = stripslashes($campoformulario); //elimina la barra de escape "\", utilizada para escapar caracteres
		$campoformulario = htmlspecialchars($campoformulario);  
  
		if($campoformulario < 5 || $campoformulario > 50)
			$campoformulario = null;
		
		return $campoformulario;
	}	
	
	//Funcion "leerBote" recibe por parametro el fichero donde se almacena el bote de los jugadores y el array con las apuestas de cada uno.
	//La funcion lee el fichero y almacena en el array "$botes" el bote total de cada jugador restando la apuesta introducida, en el orden: J1 - J2 - J3 - J4.
	//También se comprueba que el bote sea 100€ o superior, sino el valor es null. La funcion devuelve el array con los botes finales.
	function leerBote($fichero, $apuestas){
		$botes = Array();
		$cont = 0;
		
		foreach($fichero as $clave => $valor){
			$bote = substr($valor, strpos($valor, "#")+1);
			if($bote < 100)
				$bote = null;
			else
				$bote = $bote - $apuestas[$cont];
				echo "bot: ".$bote."</br>";
			array_push($botes, $bote);	
			$cont++;
		}		
		return $botes;
	}
	
	//Función "$obtenerCartas" recibe por parámetro la baraja completa de cartas, y el tope en el que tiene que parar de repartir. 15 => si obtenemos las cartas
	//de un jugador, y 17 => si obtenemos las cartas de la banca.
	//La funcion obtiene aleatoriamente cartas hasta que el valor de las mismas llegan al tope recibido. Las cartas se comprueban que no se hayan repartido.
	//Si se reparten, en $baraja se pone valor "null". Luego se comprueba el valor de la carta y se suma a "$valor" para tenerlo actualizado y saber cuando 
	//dejar de repartir.
	//La funcion devuelve un array con las cartas repartidas al jugador, y en la ultima posicion se almacena el valor.
	function obtenerCartas($baraja, $tope){
		$valor = 0;		
		//Array "$jugador" se almacenarán las cartas repartidas al jugador.
		$jugador = Array();
		//Almaceno en el array $numeros el valor de cada carta en el mismo orden sin contar con los palos. Nos servirá para calcular el valor de cada carta.
		$numeros = Array();
		for($k = 0; $k < count($baraja); $k++){
			$num = substr($baraja[$k], 0, strlen($baraja[$k])-1);
			array_push($numeros, $num);
		}
		//Repartimos carta
		while($valor < $tope){
			$pos = rand(0,51);
			if($baraja[$pos] != null){
				array_push($jugador, $baraja[$pos]);
				//Comprobamos el valor de la carta sacada y lo sumamos a "$valor", para saber los puntos del jugador.
				if($numeros[$pos] == "K" || $numeros[$pos] == "Q" || $numeros[$pos] == "J" || $numeros[$pos] == "10"){
					$valor += 10;
				}else if($numeros[$pos] == "9"){
					$valor += 9;
				}else if($numeros[$pos] == "8"){
					$valor += 8;
				}else if($numeros[$pos] == "7"){
					$valor += 7;
				}else if($numeros[$pos] == "6"){
					$valor += 6;
				}else if($numeros[$pos] == "5"){
					$valor += 5;
				}else if($numeros[$pos] == "5"){
					$valor += 5;
				}else if($numeros[$pos] == "4"){
					$valor += 4;
				}else if($numeros[$pos] == "3"){
					$valor += 3;
				}else if($numeros[$pos] == "2"){
					$valor += 2;
				}else{
					if($tope == 15){ //Esta condicion se comprueba cuando se reparte a un jugador el As.
						if(($valor + 11 >= 15) && ($valor + 11 <= 21))
							$valor += 11;
						else
							$valor += 1;
					}else{ //Esta  condicion se comprueba cuando se juega con banca porque el tope es 17 y no 15 puntos.
						if(($valor + 11 >= 17) && ($valor + 11 <= 21))
							$valor += 11;
						else
							$valor += 1;
					}					
				}
				//Doy valor "null" a la carta repartida para no darla de nuevo.
				$baraja[$pos] = null;
			}
		}
		//Compruebo si la mano del jugador es BlackJack. Si lo es, "$valor" en vez de tener el valor de las cartas tendrá "BJ".
		if(count($jugador) == 2){
			$carta1 = substr($jugador[0], 0, strlen($jugador[0])-1);
			$carta2 = substr($jugador[1], 0, strlen($jugador[1])-1);
			if(($carta1 == "K" && $carta2 == "1") || ($carta1 == "1" && $carta2 == "K") || ($carta1 == "Q" && $carta2 == "1") || ($carta1 == "1" && $carta2 == "Q") || ($carta1 == "J" && $carta2 == "1") || ($carta1 == "1" && $carta2 == "J"))
				$valor = "BJ";
		}
		
		//Añado el valor de la jugada en la última posicion del array.
		array_push($jugador, $valor);
		
		return $jugador;
	}
	
	//Funcion "$impresionCartas" recibe por parámetro las cartas de todos los jugadores, las de la banca, y el array con la apuesta introducida por todos
	//los participantes. La función realiza dos trabajos, el primero es imprimir el resultado del juego y las manos de todos los jugadores, y la segunda
	//calcula las ganancias en base a las manos de cada uno almacenandolas en el array "$ganancias".
	//La funcion devuelve el array "$ganancias".
	function impresionCartas($jugador1, $jugador2, $jugador3, $jugador4, $banca, $apuestas){
		//Array "$ganancias" de 4 posiciones va a contener lass ganancias de todos los jugadores segun la mano que tengan.
		//Si pierden la mano no ganan nada. Si ganan la mano, ganan lo apostado. Si ganan con BlackJack, ganan el doble de lo apostado.
		//Si empatan, ganan el 50% de lo apostado y si empatan con BlackJack, ganan el 75% de lo apostado.
		$ganancias = Array(0, 0, 0, 0);
		//Array "$valoresManos" contiene el valor de las manos.Posiciones:  0 => Banca, 1 => J1, 2 => J2, 3 => J3, 4 => J4
		$valoresManos = Array($banca[count($banca) -1], $jugador1[count($jugador1) -1], $jugador2[count($jugador2) -1], $jugador3[count($jugador3) -1], $jugador4[count($jugador4) -1]) ;
		//Sobreescribo los arrays eliminando la ultima posicion que contenia el valor de las manos.
		$banca = array_splice($banca, 0,count($banca)-1);
		$jugador1 = array_splice($jugador1, 0,count($jugador1)-1);
		$jugador2 = array_splice($jugador2, 0,count($jugador2)-1);
		$jugador3 = array_splice($jugador3, 0,count($jugador3)-1);
		$jugador4 = array_splice($jugador4, 0,count($jugador4)-1);		
		//Array "$jugadores" contiene todas las cartas de todos los jugadores.
		$jugadores = Array($jugador1, $jugador2, $jugador3, $jugador4);
		$cont = 1; //Indica el jugador con el que se esta comprobando la mano
		$contGan = 0; //Indica la posicion con la que se trabaja el array "$ganancias". pos 0 => jugador1, 1 => J2, 2 => J3, 3 => J4
		echo "<h1>RESULTADOS BLACKJACK</h1>";
		//Banca
		echo "<h2>Banca:</h2>";		
		for($i = 0; $i < count($banca) ; $i++){
			echo "<img src= 'images/".$banca[$i].".PNG' width = '5%'/>";
		}
		
		if($valoresManos[0] <= 20){
			echo "</br></br>Puntos: ".$valoresManos[0].".</br>Pierden jugadores con menos ".$valoresManos[0]."</br>Igual jugadores con ".$valoresManos[0]."</br>";
			echo "Ganan jugadores con mas ".$valoresManos[0]." (sin pasarse de 21)";
		}else if($valoresManos[0] == "BJ"){
			echo "</br>Puntos: 21.</br>BlackJack</br>Pierden jugadores con menos 21 o 21 sin blackjack</br>Igual jugadores blackjack";
		}else if($valoresManos[0] == 21){
			echo "</br>Puntos: 21.</br>Pierden jugadores con menos de 21</br>Igual jugadores con 21";
		}else{
			echo "</br>Puntos: ".$valoresManos[0]."</br>BANCA SE PASA</br>Ganan todos los jugadores que no se hayan pasado.";
		}
		//Jugadores
		foreach($jugadores as $datos){
			echo "</br></br><h2>Jugador ".$cont.":</h2>";
			foreach($datos as $valor)
				echo "<img src = 'images/".$valor.".PNG' width = '5%'/>";
				
			echo "</br></br>Puntos: ".$valoresManos[$cont];
			
			if($valoresManos[$cont] == "BJ")
				echo " BlackJack";
			//Posibles resultados teniendo en cuenta que la Banca tenga Blackjack.
			if($valoresManos[0] == "BJ"){
				if($valoresManos[$cont] == "BJ"){
					echo "</br>Empate. Banca(BlackJack)";
					$ganancias[$contGan] = $apuestas[$contGan] * 0.75;
				}else if($valoresManos[$cont] > 21){
					echo "</br>Pierde. Se pasa";
				}else{
					echo "</br>Pierde lo apostado. Banca (BlackJack)";
				}
			//Posibles resultados teniendo en cuenta que la banca tenga cualquier valor menor o igual a 21 sin blackjack
			}else if($valoresManos[0] <= 21){
				if($valoresManos[$cont] > 21){
					echo "</br>Pierde, se pasa.";
				}else if($valoresManos[$cont] == "BJ"){
					echo "</br>BlackJack. </br>Gana lo apostado</br>Banca(".$valoresManos[0].").";
					$ganancias[$contGan] = $apuestas[$contGan] * 2;
				}else if($valoresManos[$cont] == $valoresManos[0]){
					echo "</br>Empate. Banca(".$valoresManos[0].").";
					$ganancias[$contGan] = $apuestas[$contGan] * 0.5;
				}else if($valoresManos[$cont] < $valoresManos[0]){
					echo "</br>Pierde lo apostado </br>Banca(".$valoresManos[0].")";
				}else {
					echo "</br>Gana lo apostado. Banca(".$valoresManos[0].")";
					$ganancias[$contGan] = $apuestas[$contGan];
				}
			//Posibles resultados si la banca se pasa de 21
			}else{
				if($valoresManos[$cont] > 21){
					echo "</br>Pierde, se pasa.";
				}else if($valoresManos[$cont] == "BJ"){
					echo "</br>BlackJack. GANA DOBLE DE LO APOSTADO</br>Banca se pasa";
					$ganancias[$contGan] = $apuestas[$contGan] * 2;
				}else{
					echo "</br>Gana lo apostado</br>Banca se pasa";
					$ganancias[$contGan] = $apuestas[$contGan];
				}
			}	
			$cont++;
			$contGan++;
		}	
		return $ganancias;
	}
	
	//Funcion "actualizarFicheroBote" recibe por parámetros el array "$ganancias" con las ganancias de todos los jugadores dependiendo de si han ganado, perdido,
	//o empatado, y el array "$botes" que contiene el bote de cada jugador.
	//La funcion sobreescribe el fichero "blackjack.txt" con el nuevo bote que van a tener teniendo en cuenta las ganancias conseguidas.
	//La funcion no devuelve nada
	function actualizarFicheroBote($ganancias, $botes){
		$cont = 1;
		$fichero = fopen("blackjack.txt", "w");
		
		for($i = 0; $i < count($ganancias); $i++){
			$botes[$i] += $ganancias[$i];
			fwrite($fichero, "Jugador".$cont."#".$botes[$i]."\n");
			$cont++;
		}
	}
?>
<html>
<head></head>
<body>
<h1>RESULTADOS POKER</h1>
</br>
</br>
<?php
	//Funcion generarCartas, recibe como parámetro la baraja completa (con cartas anuladas en caso de que se hayan repartido ya) y el numero de cartas a repartir
	//(2 para un jugador o 3 para la mesa). Devuelve array con 2 o 3 cartas dependiendo de quien haya llamado a la funcion.
	function generarCartas($baraja, $cartasARepartir){
		$cartas = array();
		while(count($cartas) < $cartasARepartir){
			$pos = rand(0,51);
			if($baraja[$pos] != null)
				array_push($cartas, $baraja[$pos]);
		}
		return $cartas;
	}
	
	//Funcion "eliminarRepartidas" recibe por parametro la baraja (original o ya modificada) y las cartas a eliminar de la ultima reparticion.
	//Se busca la posicion de las cartas repartidas y se modifica a null en la baraja.
	//Devuelve la baraja modificada.
	function eliminarRepartidas($baraja, $cartasRepartidas){
		for($i = 0; $i < count($cartasRepartidas); $i++){
			$num = array_search($cartasRepartidas[$i], $baraja);
			$baraja[$num] = null;
		}
		return $baraja;
	}
	
	function comprobarJugadas($jugador, $mesa){
		$palos = Array();
		$numeros = Array();
		$aux = Array();
		$ordenado = Array();
		$jugada = null; // Variable en la que almaenaré la jugada a la que pertenece.
		
		//Unifico en el array '$aux' todas las cartas de la mano empezando por $jugador y continuando por $mesa en el mismo orden.
		$aux = array_merge_recursive($jugador, $mesa);		
		//Almaceno en el array $numeros el valor de cada carta en el mismo orden
		for($k = 0; $k < count($aux); $k++){
			$num = substr($aux[$k], 0, strlen($aux[$k])-1);
			array_push($numeros, $num);
		}
		
		//Hago un array paralelo, $ordenado, para no tocar el inicial y poder ordenarlo de menor a mayor para cuando sea necesario. 
		$ordenado = $numeros;
		sort($ordenado);		
		
		//Almaceno en el array $palos, el palo de cada carta en el mismo orden.
		for($j = 0; $j < count($aux); $j++){
			$let = substr($aux[$j], strlen($aux[$j])-1);
			array_push($palos, $let);
		}
		
		//Creo un array asociativo "$repeticiones" donde se va a almacenar el numero de repeticiones de cada numero.
		$repeticiones = Array($ordenado[0] => null, $ordenado[1] => null, $ordenado[2] => null, $ordenado[3] => null, $ordenado[4] => null);
		
		//Recorro el array "$ordenado" pasando por todos los valores y contando las veces que se repite. Luego se almacena el resultado en el array "$repeticiones"
		foreach($ordenado as $clave){
			$cont = 0;
			$pos = $clave;
			for($i = 0; $i < count($ordenado); $i++){
				if($pos == $ordenado[$i])
					$cont++;			
			}
			$repeticiones[$clave] = $cont;
		}
		
		//POKER - FULL - TRIO - DOBLE PAREJA - PAREJA
		//"$cont3" va a contener el numero de cartas que pertenecen a un trío. 
		//"$cont2" va a contener el numero de cartas que pertenecen a una pareja.
		$cont3 = 0;
		$cont2 = 0;
		//Recorro array "$repeticiones", si alguno de los valores es "4", significa que el jugador tiene Poker.
		//Si alguno de los valores es "3" se sumará a "$cont3" para la próxima comprobación entre Trío o Full.
		//Si alguno de los valores es "2" se sumará a "$cont2" par la próxima comprobación entre Pareja o Doble Pareja
		foreach($repeticiones as $clave){
			if($clave == 4)				
				$jugada = "Poker";
			else if($clave == 3)
				$cont3++;
			else if($clave == 2)
				$cont2++;
		}
		//En caso de que haya un trío, se comprobará si existe o no una pareja para que sea un Full o sino la jugada será un Trío.
		if($cont3 == 1){
			if($cont2 == 1)
				$jugada = "Full";
			else
				$jugada = "Trio";
		}else{
			//En caso de que no exista trío con la posibilidad de full, se comprobarán las parejas. Si 4 de los valores se repiten 2 veces => 2 parejas.
			//Si el contador tuviera un "2" significa que solo existe una pareja. 
			if($cont2 == 2)
				$jugada = "Doble Pareja";
			else if($cont2 == 1)
				$jugada = "Pareja";
			
		}
		
		//ESCALERA REAL - COLOR
		if($jugada ==  null){
			$contC = 0;
			$contR = 0;
			$contT = 0;
			$contP = 0;
			//Comprobamos que sean todos del mismo palo.
			for($i = 0; $i < count($palos); $i++){
				if($palos[$i] == "c")
					$contC++;		
				if($palos[$i] == "r")
					$contR++;	
				if($palos[$i] == "p") 
					$contP++;
				if($palos[$i] == "t")
					$contT++;
			}
			//Solo si son del mismo palo, Comprobamos los numeros. Utilizo el array $ordenado para comprobar que sean las cartas "10", "J", "Q", "K", "1".
			//El unico detalle, al ordenar el array ponen la "Q" con mas valor que la "K" pero como no es necesario que esten en orden para comprobar la escalera
			//real, almaceno en variable $jugada => "Escalera Real". Si no fuera escalera real, automaticamente "$jugada" => "color".
			if($contC == 5 || $contR == 5 || $contP == 5 || $contT == 5){
				if($ordenado[0] == "1" && $ordenado[1] == "10" && $ordenado[2] == "J" && $ordenado[3] == "K" && $ordenado[4] == "Q")
					$jugada = "Escalera Real";	
				else
					$jugada = "Color";
			}
		}		
		
		//CARTA MAS ALTA
		//Solo se comprueba si el valor de "$jugada" sigue siendo "null".
		//Si ha llegado a esta comprobación, significa que en la mano no tiene ninguna otra jugada, solo queda saber la carta mas alta.
		//Utilizo el array "$ordenado" que contiene los numeros de las cartas, sin los palos, ordenados de memor a mayor.
		//Con la funcion "sort" para ordenar la escalera, no se porque ordena como la letra "Q" superior a la "K", por lo que tengo que hacer una comprobacion
		// y en caso de que la "K" quede en la posicion "3", contarla como la carta más alta independientemente de su posicion.
		//La variable "$jugada" tendrá el valor de la carta mas alta.
		if($jugada == null){
			if($ordenado[3] == "K"){
				$jugada = "Carta más alta con un ".$ordenado[3];
			}else{
				$jugada = "Carta más alta con un ".$ordenado[4];
			}
		}		
		return $jugada;
	}
	
	//Funcion "imprimirResultado" recibe como parámetros las cartas de cada jugador, las de la mesa, y la jugada que han hecho.
	//No devuelve nada. Esta función imprime el resultado por pantalla.
	//Unifico en un array bidimensional "$todasC" para trabajar más cómodo. $todas1 => 5 cartas del jugador 1; $todas2 => 5 cartas del jugador 2;
	//$todas3 => 5 cartas del jugador 3; $todas4 => 5 cartas del jugador 4.
	function imprimirResultado($mano1, $mano2, $mano3, $mano4, $j1, $j2, $j3, $j4, $mesa){
		$todas1 = Array();
		$todas2 = Array();
		$todas3 = Array();
		$todas4 = Array();		
		$todas1 = array_merge_recursive($j1, $mesa);
		$todas2 = array_merge_recursive($j2, $mesa);
		$todas3 = array_merge_recursive($j3, $mesa);
		$todas4 = array_merge_recursive($j4, $mesa);
		$todasC = Array($todas1, $todas2, $todas3, $todas4);
		$manos = Array($mano1, $mano2, $mano3, $mano4);
		
		//Imprimo con las imagenes correspondientes, las 5 cartas de cada jugador.
		$cont = 1;
		foreach($todasC as $clave){
			echo "Jugador ".$cont.":";
			foreach($clave as $valor ){
				echo "<img src = images/".$valor.".png width = '5%'/>";
			}
			$cont++;
			echo "</br></br>";
		}

		//Imprimo la jugada de cada participante.
		$cont2 = 1;
		foreach($manos as $valor){
			echo "El jugador ".$cont2." tiene : ".$valor;		
			$cont2++;
			echo "</br>";
		}
	}
?>
</body>
</html>
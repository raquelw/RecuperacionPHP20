<?php
	//Funcion generarCartas, recibe como parámetro la baraja completa (con cartas anuladas en caso de que se hayan repartido ya) y el numero de cartas a repartir
	//(2 para un jugador o 3 para la mesa). Devuelve array con 2 o 3 cartas dependiendo de quien haya llamado a la funcion.
	function generarCartas($baraja, $cartasARepartir){
		$cartas = array();
		while(count($cartas) < $cartasARepartir){
			$pos = rand(0,51);
			if($baraja[$pos] != null){
				array_push($cartas, $baraja[$pos]);
			}
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
						
		//ESCALERA REAL
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
		//real, almaceno en variable $jugada => "ER" de escalera real.
		if($contC == 5 || $contR == 5 || $contP == 5 || $contT == 5){
			if($ordenado[0] == "1" && $ordenado[1] == "10" && $ordenado[2] == "J" && $ordenado[3] == "K" && $ordenado[4] == "Q")
				$jugada = "ER";			
		}
		
		//POKER
		//Si $jugada sigue 'null' (no se ha definido jugada) se realiza la comprobacion, sino no.
		//Comprobamos que sean 4 cartas con el mismo valor. Si es así, '$jugada' => 'P'.
		if($jugada == null){
			$contPoker = 0;
			$valor = $numeros[0];
			for($l = 1; $l < count($numeros); $l++){
				if($valor == $numeros[$l]){
					$contPoker++;
				}
			}
			if($contPoker == 4){
				$jugada = "P";					//NO FUNCIONA!!!!!
			}else{
				$contPoker = 0;
				$valor = $numeros[1];
				if($valor == $numeros[0]){
					$contPoker++;
					for($m = 2; $m < count($numeros); $m++){
						if($valor == $numeros[$m]){
							$contPoker++;
						}
					}
				}
				if($contPoker == 4){
					$jugada = "P";
				}
			}			
		}
		
		//COLOR
		//Se realiza la comprobacion solo si la variable '$jugada' sigue 'null'.
		if($jugada == null){
			$contC = 0;
			$contR = 0;
			$contT = 0;
			$contP = 0;
			//Comprobamos que sean todos del mismo palo. Si las 5 cartas son del mismo palo, la variable '$jugada' => "C".
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
		
			if($contC == 5 || $contR == 5 || $contP == 5 || $contT == 5)
				$jugada = "C";
		}
		
		return $jugada;
	}
?>
<?php
	//Función "generarCarton" no recibe nada por parámetro. Utilizando un array multidimensional generamos el cartón de 15 números aleatorios del 1 al 60
	//ordenados de menor a mayor, de arriba a abajo y de izquierda a derecha.
	//La función devuelve el array multidimensional correspondiente al cartón generado.
	function generarCarton(){
		//Array donde vamos a almacenar los numeros ya sacados para que no se repitan
		$repartidas = Array();
		//Array auxiliar donde vamos a almacenar los numeros correctos para el carton que despues convertiremos en un array multidimensional
		$cartonAux = Array();
		//Se crea array multidimensional para generar el carton.
		$carton[0][0] = null; $carton[0][1] = null;$carton[0][2] = null; $carton[0][3] = null; $carton[0][4] = null;
		$carton[1][0] = null; $carton[1][1] = null;$carton[1][2] = null; $carton[1][3] = null; $carton[1][4] = null;
		$carton[2][0] = null; $carton[2][1] = null;$carton[2][2] = null; $carton[2][3] = null; $carton[2][4] = null;
		
		while(count($cartonAux) < 15){
			$num = rand(1, 60);		
			if(!in_array($num, $repartidas)){
				array_push($repartidas, $num);
				array_push($cartonAux, $num);
			}
		}
		//Se ordena el array con los numeros del carton de menor a mayor.
		sort($cartonAux);
		//Se genera array multidimensional con numeros del carton ordenados.
		$cont = 0;
		for($i = 0; $i < 5; $i++){
			for($j = 0; $j < 3; $j++){
				$carton[$j][$i] = $cartonAux[$cont];
				$cont++;
			}		
		}
		return $carton;
	}
	
	
	function imprimirResultado($cartones){
		echo "<html><head></head><body><table border = '1px'>";
		$contJug = 1;
		
		foreach($cartones as $clave){
			echo "<h3>Jugador ".$contJug.": </h3>";
			$contCart = 1;

			foreach($clave as $valor){
				echo "<h4>Carton ".$contCart.": </h4>";
				echo "<tr>";
				for($i = 0; $i < 3; $i++){
					echo "</br>";
					for($j = 0; $j < 5; $j++){
						echo "</td>",$valor[$i][$j]."   </td> ";
					}
				}
				echo "</tr></br>";
				$contCart++;
			}
			$contJug++;
		}
		echo "</table></body></html>";
	}
?>
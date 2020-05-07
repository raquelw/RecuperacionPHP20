<?php
	//Función "$apuestaGanadora" no recibe nada como parámetro.
	//Genera la combinación ganadora para los 6 numeros y el complementario con números aleatorios del 1 al 49, y para el reintegro del 0 al 9 sin ser
	//repetidos. Los resultados se almacenan en el array "$combGanadora" en este orden => N1 N2 N3 N4 N5 N6 C R.
	//La función devuelve el array con la combinación generada.
	function apuestaGanadora(){
		$combGanadora = Array();
		//Numeros y complementario
		while(count($combGanadora) < 7){
			$num = rand(1, 49);
			if(!in_array($num, $combGanadora)){
				array_push($combGanadora, $num);
			}
		}
		
		//Reintegro
		$reintegro  = rand(0,9);
		array_push($combGanadora, $reintegro);
		
		return $combGanadora;
	}
	
	//Funcion "jugadas()" recibe como parámetro el fichero con todas las jugadas. 
	//La función lee el fichero con las jugadas y las almacena en un array asociativo "$jugadas". Las claves del array son los identificadores de los
	//jugadores. Se quitan los guiones y la primera linea del fichero no se cuenta. 
	//Devuelve el array asociativo con todas las jugadas.
	function obtenerJugadas($fichero){
		$clave = null;	
		
		foreach($fichero as $columna => $fila){
			if($columna > 0){
				$clave = substr($fila, 0, strpos($fila, "-"));
				$resto = substr($fila, strpos($fila, "-")+1);	
				$jugadas[$clave] = explode("-", $resto);		
			}
		}
		return $jugadas;
	}
	
	//Funcion "compararResultados()" que compara el array de la combinacion ganadora con cada jugada almacenada en el array asociativo "$jugadas".
	//Recibe por parámetro la apuesta ganadora y el array con todas las jugadas de los apostantes. Devuelve array "$aciertos" con todos los posibles aciertos
	function compararResultados($apGan, $jugadas){		
		$cont = 0;
		$aciertos = Array(0 => 0, //0 aciertos
							1 => 0, //1
							2 => 0, //2
							3 => 0, //3
							4 => 0, //4
							5 => 0, //5
							6 => 0, //6
							7 => 0, //5 + complementario
							8 => 0); //Reintegros
							
		foreach($jugadas as $clave1 => $valor1){
			//Creo un segundo array con las posiciones del 0 al 5 (6 numeros) de cada jugada. (sin complementario ni reintegro)			
				for($j=0; $j <= 5; $j++){
					$valor2[$j]= $valor1[$j];
				}
					//Recorro array de combinacion ganadora sin contar el complementario ni el reintegro, y si el valor se encuentra dentro. Suma contador
					for($i = 0; $i < count($apGan)-2; $i++){				
						if(in_array($apGan[$i], $valor2))
							$cont++;		
					}
					//Dependiendo de los aciertos obtenidos, incremento en 1 el valor del array correspondiente para obtener el nº de jugadores que 
					//han acertado en cada opcion de resultado.
					if(($cont == 5) && ($apGan[6] == $valor1[6])){//5 + complementario
						$aciertos[7]++;
					}else{
						$aciertos[$cont]++;//cualquier numero (lo dictamina el contador, puesto que se llama igual la posicion que el valor)
					}
					
					if($apGan[7] == $valor1[7])//Reintegros acertados
						$aciertos[8]++;
					
					$cont = 0;//Cuando termine una jugada, inicializamos contador a 0 y seguimos con la siguiente
		}
		return $aciertos;
	}
	
	//Funcion que imprime los resultados finales.
	//Recibe como parámetros la fecha, el array con los aciertos, la apuesta ganadora y el array asociativo con las jugadas de todos los participantes.
	//No devuelve nada.
	function mostrar($fecha, $aciertos, $apGan, $jugadas){
		echo "<h1>Loteria Primitiva de Espana / Sorteo ".$fecha."</h1>";
		
		//Mostrar imagenes de la combinacion ganadora
		echo "<h2>Combinacion Ganadora</h2>";
		for($j = 0; $j <= 5; $j++){
			echo "<img src='images/".$apGan[$j].".png' width='5%'/>";
		}
		
		//Muestro complementario y reintegro
		echo "</br></br>Complementario: ".$apGan[6]."</br>";
		echo "Reintegro: ".$apGan[7]."</br></br></br>";
		
		echo "Apuestas Jugadas: ".count($jugadas);	
		
		//Muestro acertantes
		echo "<h2>Acertantes</h2>";	
		for($i = 0; $i < count($aciertos); $i++){			
			if($i == 6){
				echo "Acertantes ".$i.", numeros: ".$aciertos[$i]."<br/>";
			}else if($i == 7){
				echo "Acertantes 5 + complementario, numeros: ".$aciertos[$i]."<br/>";
			}else if($i == 5){
				echo "Acertantes ".$i.", numeros: ".$aciertos[$i]."<br/>";
			}else if($i == 4){
				echo "Acertantes ".$i.", numeros: ".$aciertos[$i]."<br/>";
			}else if($i == 3){
				echo "Acertantes ".$i.", numeros: ".$aciertos[$i]."<br/>";
			}else if($i == 8){
				echo "Acertantes reintegro, numeros: ".$aciertos[$i]."<br/>";
			}else if($i == 2){
				echo "Sin premio ".$i.", numeros: ".$aciertos[$i]."<br/>";
			}else if($i == 1){
				echo "Sin premio ".$i.", numeros: ".$aciertos[$i]."<br/>";
			}else{
				echo "Sin premio ".$i.", numeros: ".$aciertos[$i]."<br/>";
			}			
		}
	}	
	
	//Función "limpiarCampos()" valida todos los campos recibidos del formulario eliminando espacios en blanco en los extremos, eliminando la barra
	//invertida "\", convierte caracteres especiales a entidades HTML. 
	function limpiarCampos($campoformulario){
		$campoformulario = trim($campoformulario); //elimina espacios en blanco por izquierda/derecha
		$campoformulario = stripslashes($campoformulario); //elimina la barra de escape "\", utilizada para escapar caracteres
		$campoformulario = htmlspecialchars($campoformulario);  
  
		return $campoformulario;
	}	
	
	//Función "$tratarFecha" recibe la fecha introducida en el formulario por parámetro.
	//Quitamos los guiones y se ordena para su posterior utilizacion en el nombre del archivo con los resultados.
	//Devuelve variable con el nombre completo del fichero y la fecha introducida en el formulario.
	function tratarFecha($fecha){
		$fech = explode("-", $fecha);
		$nombrefichero = "premiosorteo_".$fech[2].$fech[1].$fech[0].".txt";

		return $nombrefichero;
	}
	
	//Función "calcularPremios()" Calcula los premios a repartir a cada acertante según porcentaje dado. Si no hay acertantes en algun número de aciertos
	//No se entregaría dinero a nadie, por lo que pongo "0" como valor. También los resultados obtenidos se escriben en un fichero txt.
	//Recibe como parámetro el fichero donde quedará reflejado el bote repartido según acertantes, el fichero con las jugadas, el bote adquirido, y el array
	//con los aciertos. No devuelve nada.
	function calcularPremios($ficheroPremios, $fichero, $bote, $aciertos){		
		//80% del bote para los premios.
		$boteTotalPremiados = $bote * 0.8;
		
		//6 aciertos
		$boteSeis = $boteTotalPremiados * 0.4;
		if($aciertos[6] != 0){
			$boteSeisPers = $boteSeis / $aciertos[6];
		}else{
			$boteSeisPers = $boteSeis.", no se reparten 0 acertantes";
		}
		
		//5 + complemento
		$boteCincoCompl = $boteTotalPremiados * 0.3;
		if($aciertos[7] != 0){
			$boteCincoComplPers = $boteCincoCompl / $aciertos[7];
		}else{
			$boteCincoComplPers = $boteCincoCompl.", no se reparten 0 acertantes";
		}
		
		//5 aciertos
		$boteCinco = $boteTotalPremiados * 0.15;
		if($aciertos[5] != 0){
			$boteCincoPers = $boteCinco / $aciertos[5];
		}else{
			$boteCincoPers = $boteCinco.", no se reparten 0 acertantes";
		}
		
		//4 aciertos
		$boteCuatro = $boteTotalPremiados * 0.05;
		if($aciertos[4] != 0){
			$boteCuatroPers = $boteCuatro / $aciertos[4];
		}else{
			$boteCuatroPers = $boteCuatro.", no se reparten 0 acertantes";
		}
		
		//3 aciertos
		$boteTres = $boteTotalPremiados * 0.08;
		if($aciertos[3]){
			$boteTresPers = $boteTres / $aciertos[3];
		}else{
			$boteTresPers = $boteTres.", no se reparten 0 acertantes";
		}
		
		//Reintegro
		$boteReintegro = $boteTotalPremiados * 0.02;
		if($aciertos[8] != 0){
			$boteReinPers = $boteReintegro / $aciertos[8];
		}else{
			$boteReinPers = $boteReintegro.", no se reparten 0 acertantes";
		}
		
		fwrite($ficheroPremios, "C6#".$boteSeisPers."\n");
		fwrite($ficheroPremios, "C5+#".$boteCincoComplPers."\n");
		fwrite($ficheroPremios, "C5#".$boteCincoPers."\n");
		fwrite($ficheroPremios, "C4#".$boteCuatroPers."\n");
		fwrite($ficheroPremios, "C3#".$boteTresPers."\n");
		fwrite($ficheroPremios, "CR#".$boteReinPers."\n");		
	}	
?>
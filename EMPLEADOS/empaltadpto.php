<?php
	function crearDepartamento(){
		$servername = "localhost";
		$username = "root";
		$password = "rootroot";
		$dbname = "empleados";
		
		$nomdpto = $_REQUEST['nomdpto'];
				
		// Creamos la conexion
		$conn = mysqli_connect($servername, $username, $password, $dbname);

		// Comprobamos la conexion
		if (!$conn) {
			die("Error de conexion".mysqli_connect_error());
		}
		
		//Hago select para conseguir el codigo del departamento mayor de la tabla, quitamos la letra D y sumamos uno más, y volvemos a poner la latra, para tener
		//el próximo código
		$codmax = "SELECT MAX(cod_dpto) FROM departamento";
		$result = mysqli_query($conn, $codmax);
		$codnuevo = "";
		
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				$maxcod = $row["max(cod_dpto)"];
				$codnuevo = substr($maxcod, 1);
				settype($codnuevo, "integer");
				$codnuevo = $codnuevo + 1;
				$codnuevo = str_pad($codnuevo, 3, "0", STR_PAD_LEFT);
				$codnuevo = "D".$codnuevo;
			}
		}

		$sql = "INSERT INTO departamento(cod_dpto, nombre_dpto) VALUES ('$codnuevo', '$nomdpto')";
		
		if (mysqli_query($conn, $sql)) { 		
			echo "Insertado correcto";
		} else {
			echo "Error insertando informacion: " . mysqli_error($conn);
		}
			
		mysqli_close($conn);
	}
	
	crearDepartamento();
?>
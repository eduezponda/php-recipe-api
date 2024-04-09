<?php
	
	function conexion () {
		$servidor = "observer";
		$bd = "db_grupo08";
		$user = "grupo08";
		$password = "fai8eXooch";

		$con = mysqli_connect($servidor, $user, $password, $bd);

		if (!$con) {
			echo "Error de conexión de base de datos <br>";
			echo "Error número: " . mysqli_connect_errno();
			echo "Texto error: " . mysqli_connect_error();
			exit;
		}

		$con->query("create table prueba( id NUMBER PRIMARY KEY);");

		return $con;
	}
	conexion();
?>
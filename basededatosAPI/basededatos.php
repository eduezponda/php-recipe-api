<?php
	
	function conexion () {
		$servidor = "dbserver";
		$user = "grupo08";
		$password = "fai8eXooch";
		$bd = "db_grupo08";

		$con = mysqli_connect($servidor, $user, $password, $bd);

		if (!$con) {
			echo "Error de conexión de base de datos <br>";
			echo "Error número: " . mysqli_connect_errno();
			echo "Texto error: " . mysqli_connect_error();
			exit;
		}
		return $con;
	}
?>
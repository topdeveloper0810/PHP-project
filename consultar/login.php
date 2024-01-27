<?php
session_start();
error_reporting(0);
?>
<HTML>
<meta charset="utf-8">

</HTML>
<?php

include("conexBD.php");

$BD = conexion::connect();

$login = $_POST['username'];
$passwordd = $_POST['password'];
$consulta = "SELECT r.name as name_rol, u.password, u.id as iduser, u.user_name, u.user_mail, u.active as statusUser FROM user u, rol r where user_name='$login' and u.id_rol=r.id";

$resultado = mysqli_query($BD, $consulta) or die("Algo ha ido mal en la consulta a la base de datos");
$result = mysqli_fetch_array($resultado);
$hash = $result["password"];
$name_rol = $result["name_rol"];
$statusUser = $result["statusUser"];
$user_name = $result["user_name"];
$user_mail = $result["user_mail"];
$iduser = $result["iduser"];

// Motrar el resultado de los registro de la base de datos
if ($resultado) {
	if (mysqli_num_rows($resultado) > 0 && password_verify($passwordd, $hash)) {
		// if(mysqli_num_rows($resultado) > 0 && $passwordd == $hash) {
		if ($statusUser == "1") {

			//ENVIA A LA PAGINA PRINCIPAL DEL ADMINISTRADOR
			$_SESSION['activa'] = 'true';
			$_SESSION['name_rol'] = $name_rol;
			$_SESSION['user_name'] = $user_name;
			$_SESSION['user_mail'] = $user_mail;
			$_SESSION['iduser'] = $iduser;
			// header("location: ../boddy.php");
?>
			<script type="text/javascript">
				window.location = "../boddy.php";
			</script>
		<?php
		} else {
		?>
			<script type="text/javascript">
				alert("!! Usuario Desactivado !!");
			</script>
			<script type="text/javascript">
				window.location = "../index.php";
			</script>
		<?php
		}
		// exit();
	} else {
		//Login failed
		?>
		<script type="text/javascript">
			alert("!! Contraseña/Usuario incorrecto !!");
		</script>
		<script type="text/javascript">
			window.location = "../index.php";
		</script>
<?php
	}
} else {
	die("Query falla");
}
?>
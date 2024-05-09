<?php
    require_once '../frontOffice/funcionalidadesUsuario.php';
    require_once '../frontOffice/funcionalidadesAPI.php';

    session_start();
    
    if (!isset($_SESSION['user_name'])) {
        header('Location: home.php');
        exit();
    }

    $idiomas = obtenerIdiomas();
    $datosUsuario = verInformacionUsuario($_SESSION['user_name']);

    $nombreUsuario = $_SESSION['user_name'];
    $correoUsuario = $datosUsuario[0];
    $idiomaUsuario = $datosUsuario[1];
    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />

    <title>Mazapan Company-User</title>

    <link rel="icon" href="assets/images/logoMazapan.png" type="image/png" />

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css" />
    <link rel="stylesheet" href="assets/css/templatemo-villa-agency.css" />
    <link rel="stylesheet" href="assets/css/owl.css" />
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link
      rel="stylesheet"
      href="https://unpkg.com/swiper@7/swiper-bundle.min.css"
    />

    <link rel="stylesheet" href="assets/css/user.css" />
  </head>

  <body>
    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" 
                                src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
                                            <span class="font-weight-bold"><?php echo "{$_SESSION['user_name']}" ?></span></div>
            </div>
            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Configuración de perfil</h4>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Nombre de usuario</label><p class="form-control" ><?php echo $nombreUsuario ?></p></div>
                        <div class="col-md-12"><label class="labels">Correo</label><p class="form-control" ><?php echo $correoUsuario ?></p></div>
                        <div class="col-md-12"><label class="labels">Idioma</label><p class="form-control" ><?php echo $idiomaUsuario ?></p></div>
                        <div class="col-md-12">
                            <form action="formularioCambiarIdioma.php" method="post">
                                <input type="hidden" name="username" value="<?= $nombreUsuario?>">
                                <label class="labels">Cambiar idioma</label>
                                <select class="form-control" name="language">
                                    <?php foreach ($idiomas as $idioma): ?>
                                        <option value="<?= $idioma['clave'] ?>" <?= $idioma['idioma'] == $idiomaUsuario ? 'selected' : '' ?>>
                                            <?= $idioma['idioma'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="submit">Guardar idioma</button></div>
                            </form>
                        </div>
                        <form action="formularioCambiarContrasena.php" method="post">
                            <input type="hidden" name="username" value="<?= $nombreUsuario?>">
                            <div class="col-md-12">
                                <label class="labels">
                                    Nueva contraseña
                                </label>
                                <input type="password" class="form-control" name="newPassword"placeholder="New password..." value="">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">
                                    Confirmar contraseña
                                </label>
                                <input type="password" class="form-control" name="verificateSamePassword" placeholder="New password..." value="">
                            </div>
                            <div class="mt-5 text-center">
                                <button class="btn btn-primary profile-button" type="submit">
                                    Guardar nueva contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience"><button onclick='logout.php' class="border px-3 p-1 add-experience">&nbsp;Log out</button></div><br><br><br>
                <img
                    class = "image"
                    src="assets/images/logoMazapan.png"
                    alt="Logo Mazapan"
                    style="max-width: 150px"
                />
            </div>
        </div>
        </div>
    </div>
  </body>
</html>
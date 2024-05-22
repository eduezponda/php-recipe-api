<?php
    session_start();

    include_once "../../basededatosAPI/basededatos.php";

    if (!isset($_SESSION['admin'])) {
        header("Location: ../../plantillaWeb/home.php");
    }
?>

<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mazapan Company-Admin</title>

    <link rel="icon" href="assets/images/logoMazapan.png" type="image/png" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/libs/css/style.css">
    <link rel="stylesheet" href="assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/datatables/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/datatables/css/buttons.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/datatables/css/select.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/datatables/css/fixedHeader.bootstrap4.css">
</head>

<body>
    <div class="dashboard-main-wrapper">
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a class="navbar-brand" href="admin.php">MAZAPAN</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="assets/images/admin.jpg" alt="" class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name"><?php echo $_SESSION['user_name']; ?></h5>
                                </div>
                                <a class="dropdown-item" href="../../plantillaWeb/user.php"><i class="fas fa-user mr-2"></i>Account</a>
                                <a class="dropdown-item" href="../../frontOffice/logout.php"><i class="fas fa-power-off mr-2"></i>Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        
        <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <img
                        src="assets/images/logoMazapan.png"
                        alt="Logo Mazapan"
                        style="max-width: 230px"
                    />
                </nav>
            </div>
        </div>
    </div>

    <div class="dashboard-wrapper">
        <div class="container-fluid  dashboard-content">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">Basic Table</h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                    $con = conexion();

                                    $stmt = $con->prepare("SELECT nombreUsuario, correo, claveIdioma FROM final_usuario");
                                    $stmt->execute();
                                    $resultado = $stmt->get_result();

                                    $cadena = file_get_contents("plantillaTabla.html");
                                    $trozos = explode("##fila##", $cadena);

                                    $cuerpo = "";
                                    while ($datos = $resultado->fetch_assoc()) {
                                        $aux = $trozos[1];
                                        $aux = str_replace("##nombre##", $datos["nombreUsuario"], $aux);
                                        $aux = str_replace("##correo##", $datos["correo"], $aux);
                                        $aux = str_replace("##idioma##", $datos["claveIdioma"], $aux);
                                        $cuerpo .= $aux;
                                    }

                                    $con->close();

                                    echo $trozos[0] . $cuerpo . $trozos[2];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="assets/vendor/multi-select/js/jquery.multi-select.js"></script>
    <script src="assets/libs/js/main-js.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="assets/vendor/datatables/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/vendor/datatables/js/data-table.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.0.4/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    
</body>
 
</html>
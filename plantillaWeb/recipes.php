<?php
  require_once "../frontOffice/funcionalidadesAPI.php";
  session_start();

  $idRecetas = [];
  for ($i = 0; $i < 9; $i++) {
    $idRecetas[] = seleccionarIdReceta();
  }
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

    <title>Mazapan Company-Recipe</title>

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
  </head>

  <body>
    <!-- ***** Preloader Start ***** -->
    <div id="js-preloader" class="js-preloader">
      <div class="preloader-inner">
        <span class="dot"></span>
        <div class="dots">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
    <!-- ***** Preloader End ***** -->

    <div class="sub-header">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-8">
            <ul class="info">
              <li><i class="fa fa-envelope"></i> mazapan@company.com</li>
              <li>
                <i class="fa-regular fa-calendar"></i> Última actualización:
                <?php echo obtenerUltimaFechaDeActualizacion(); ?>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <nav class="main-nav">
              <!-- ***** Logo Start ***** -->
              <a href="home.php" class="logo">
                <h1>Mazapan</h1>
              </a>
              <!-- ***** Logo End ***** -->
              <!-- ***** Menu Start ***** -->
              <ul class="nav">
                <li><a href="home.php">Home</a></li>
                <li><a href="recipes.php" class="active">Recipes</a></li>
                <!-- ***** Add new Tab ***** -->
                <?php 
                  if (isset($_SESSION['user_name'])) {
                    echo "<li>
                    <a href='user.php'
                        ><i class='fa-solid fa-user'></i>{$_SESSION['user_name']}</a>
                    </li>";
                  }
                  else {
                    echo "<li>
                      <a href='signUp.php?result=0'
                        ><i class='fa-solid fa-lock'></i>Sign Up</a
                      >
                    </li>";
                  }
                ?>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <div class="page-heading header-text">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <span class="breadcrumb"><a href="#">Home</a> / Recipe</span>
            <h3>Recipe</h3>
          </div>
        </div>
      </div>
    </div>

    <div class="section properties">
      <div class="container">
        <ul class="properties-filter">
          <li>
            <a class="is_active" href="#!" data-filter="*">Show All</a>
          </li>
        </ul>
        <div class="row properties-box">
          <?php
            $finales = ["adv", "str", "adv rac", "str", "rac str", "rac adv", "rac str", "rac adv", "rac adv"];

            for ($i=0; $i<9; $i++) {
              $datosReceta = obtenerDatosReceta($idRecetas[$i]);

              echo '<div
                class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 ' . $finales[$i] . '">
                <div class="item">
                  <a href="recipe-details.php?id=' . $idRecetas[$i] . '"
                    ><img
                      src="' . $datosReceta[0]["imagen"] . '"
                      alt=""
                  /></a>
                  <span class="category">' . $datosReceta[0]["comida"] . '</span>
                  <h6>' . $datosReceta[0]["minutos"] . ' min</h6>
                  <h4><a href="recipe-details.php?id=' . $idRecetas[$i] . '">' . $datosReceta[0]["titulo"] . '</a></h4>
                  <ul>
                    <li>Proteínas: <span>' . $datosReceta[0]["proteinas"] . '</span></li>
                    <li>Grasas: <span>' . $datosReceta[0]["grasas"] . '</span></li>
                    <li>Calorías: <span>' . $datosReceta[0]["calorias"] . '</span></li>
                  </ul>
                  <div class="main-button">
                    <a href="recipe-details.php?id=' . $idRecetas[$i] . '">Más información: </a>
                  </div>
                </div>
              </div>';
            }
          ?>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <ul class="pagination">
              <li><a href="#">1</a></li>
              <li><a class="is_active" href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">>></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <footer>
      <div class="container">
        <div class="col-lg-8 footer-content">
          <img
            src="assets/images/logoMazapan.png"
            alt="Logo Mazapan"
            style="max-width: 80px"
          />
          <p>Mazapan Corporate</p>
        </div>
      </div>
    </footer>

    <!-- Scripts -->
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/isotope.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/counter.js"></script>
    <script src="assets/js/custom.js"></script>
  </body>
</html>

<?php
  require_once "../frontOffice/funcionalidadesAPI.php";
  session_start();

  $g_value = 0;
  if (isset($_GET['g'])) {$g_value = $_GET['g'];}

  $list_requerimientos = ['colesterol', 'azucar', 'calorias', 'grasas', 'proteinas', 'carbohidratos'];
  $requerimiento = $list_requerimientos[$g_value];

  $datosGraficas=[];
  if (isset($_SESSION['user_name'])) {
      $datosGraficas = recogerDatosGraficasAPI($requerimiento);
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

    <title>Mazapan Company-Home</title>

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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>

  <body>

    <?php 
      $id_receta = seleccionarIdReceta();
      $datosReceta = obtenerDatosReceta($id_receta);
    ?>

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
                <i class="fa-regular fa-calendar"></i> Last update:
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
                <li><a href="home.php" class="active">Home</a></li>
                <li><a href="searchByFood.php">Food</a></li>
                <li><a href="searchByDiet.php">Diet</a></li>
                <li><a href="searchByKitchen.php">Kitchen</a></li>
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

    <div class="main-banner">
      <div class="owl-carousel owl-banner">
        <div class="item item-1">
          <div class="header-text">
            <span class="category"><em><a href="https://www.lostesorosdelconvento.com/">Monastery of San Clemente,</a> Toledo</em></span>
            <h2>Hurry!<br />Enjoy the finest handmade marzipan</h2>
          </div>
        </div>
        <div class="item item-2">
          <div class="header-text">
            <span class="category"><em><a href="https://pasteleriaplazamayor.es/categoria-producto/mazapanes/">Plaza Mayor Bakery,</a> Madrid</em></span>
            <h2>Don't miss out!<br />Savor the most exquisite marzipan</h2>
          </div>
        </div>
        <div class="item item-3">
          <div class="header-text">
            <span class="category"><em><a href="https://confiterialosangeles.com/productos/">Los Ángeles Bakery,</a> Seville</em></span>
            <h2>Act now!<br />Taste the highest quality marzipan</h2>
          </div>
        </div>
      </div>
    </div>

    <?php 
        if (!empty($datosGraficas)) { ?>

            <div class="properties section">
              <div class="container">
                <div class="row">
                  <div class="col-lg-4 offset-lg-4">
                    <div class="section-heading text-center">
                      <h6>| Charts</h6>
                      <h2>Some info about our recipes</h2>
                    </div>
                  </div>
                </div>
                  <div class="grafico-container">
                    <canvas id="graficoDietas"></canvas>
                    <canvas id="graficoCocinas"></canvas>
                  </div>
              </div>
            </div>
            
            <div class="section best-deal" style="justify-content: center; align-items: center; display: flex;">
              <div class="tabs-content">
                <div class="row">
                  <div class="nav-wrapper ">
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item" role="presentation">
                        <a class="nav-link <?php if ($g_value < 1) {echo "active";}?>" style="margin-top: 150px;" id="colesterol" href="home.php">Colesterol</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link <?php if ($g_value == 1) {echo "active";}?>" style="margin-top: 150px;" id="azucar" href="home.php?g=1">Azucar</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link <?php if ($g_value == 2) {echo "active";}?>" style="margin-top: 150px;" id="calorias" href="home.php?g=2">Calorias</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link <?php if ($g_value == 3) {echo "active";}?>" style="margin-top: 150px;" id="grasas" href="home.php?g=3">Grasas</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link <?php if ($g_value == 4) {echo "active";}?>" style="margin-top: 150px;" id="proteinas" href="home.php?g=4">Proteinas</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link <?php if ($g_value > 4) {echo "active";}?>" style="margin-top: 150px;" id="carbohidratos" href="home.php?g=5">Carbohidratos</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="grafico-container">
              <canvas id="graficoMinutos"></canvas>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var datosDietas = <?php echo json_encode($datosGraficas['dietas']); ?>;
                    var datosCocinas = <?php echo json_encode($datosGraficas['cocinas']); ?>;
                    var datosMinutos = <?php echo json_encode($datosGraficas['minutos']); ?>;
        
                    var ctxDietas = document.getElementById('graficoDietas').getContext('2d');
                    var graficoDietas = new Chart(ctxDietas, {
                        type: 'bar',
                        data: {
                            labels: datosDietas.map(d => d.dieta),
                            datasets: [{
                                label: 'Número de Recetas',
                                data: datosDietas.map(d => d.cantidad_recetas),
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
        
                    var ctxCocinas = document.getElementById('graficoCocinas').getContext('2d');
                    var graficoCocinas = new Chart(ctxCocinas, {
                        type: 'pie',
                        data: {
                            labels: datosCocinas.map(c => c.cocina),
                            datasets: [{
                                data: datosCocinas.map(c => c.cantidad_recetas),
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        }
                    });
        
                    var ctxMinutos = document.getElementById('graficoMinutos').getContext('2d');
                    var graficoMinutos = new Chart(ctxMinutos, {
                        type: 'line',
                        data: {
                            labels: datosMinutos.map(r => r.<?php echo $requerimiento; ?>),
                            datasets: [{
                                label: 'Tiempo de Preparación por valores de <?php echo $requerimiento; ?>',
                                data: datosMinutos.map(c => c.minutos),
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
            </script>
    <?php
        }
    ?>

    <div class="featured section">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <div class="left-image">
              <img src="<?php if (!empty($datosReceta)) {echo $datosReceta[0]['imagen'];} ?>" alt="" />
              <a href="<?php echo "recipe-details.php?id=" . $id_receta; ?>"
                ><img
                  src="assets/images/LogoDetalles.png"
                  alt=""
                  style="max-width: 80px; padding: 0px"
              /></a>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="section-heading">
              <h6>| Most popular today</h6>
              <h2><?php if (!empty($datosReceta)) {echo $datosReceta[0]['titulo'];} ?></h2>
            </div>
            <div class="accordion" id="accordionExample">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                  <button
                    class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseOne"
                    aria-expanded="true"
                    aria-controls="collapseOne"
                  >
                    Nutritional information
                  </button>
                </h2>
                <div
                  id="collapseOne"
                  class="accordion-collapse collapse show"
                  aria-labelledby="headingOne"
                  data-bs-parent="#accordionExample"
                >
                  <div class="accordion-body">
                    <?php
                      if (!empty($datosReceta)) {
                        echo "Carbohydrate quantity: " . $datosReceta[0]['carbohidratos'] . "</br>";
                        echo "Proteins quantity: " . $datosReceta[0]['proteinas'] . "</br>";
                        echo "Fats quantity: " . $datosReceta[0]['grasas'] . "</br>";
                        echo "Calories quantity: " . $datosReceta[0]['calorias'] . "</br>";
                        echo "Cholesterol quantity: " . $datosReceta[0]['colesterol'] . "</br>";
                        echo "Sugar quantity: " . $datosReceta[0]['azucar'] . "</br>";
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo"
                    aria-expanded="false"
                    aria-controls="collapseTwo"
                  >
                    Ingredients needed
                  </button>
                </h2>
                <div
                  id="collapseTwo"
                  class="accordion-collapse collapse"
                  aria-labelledby="headingTwo"
                  data-bs-parent="#accordionExample"
                >
                  <div class="accordion-body">
                    <?php 
                      $datosIngredientes = obtenerIngredientesReceta($id_receta);

                      if (!empty($datosIngredientes)) {
                        foreach ($datosIngredientes as $fila) {
                          echo "- " . $fila['ingrediente'] . ": " . $fila['cantidad'] . " " . $fila['medidaCantidad'] . ".</br>";
                        }
                      }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="info-table">
              <ul>
                <li>
                  <img
                    src="assets/images/LogoComida.png"
                    alt=""
                    style="max-width: 52px"
                  />
                  <h4><?php if (!empty($datosReceta)) {echo $datosReceta[0]['comida'];} ?><br /><span>Base meal</span></h4>
                </li>
                <li>
                  <img
                    src="assets/images/LogoTiempoPreparacion.png"
                    alt=""
                    style="max-width: 52px"
                  />
                  <h4><?php if (!empty($datosReceta)) {echo $datosReceta[0]['minutos'] . 'min';} ?><br /><span>Preparation time</span></h4>
                </li>
                <li>
                  <img
                    src="assets/images/LogoPaisTradicional.png"
                    alt=""
                    style="max-width: 52px"
                  />
                  <h4><?php 
                    $todas_cocinas = '';
                    $datosCocinas = obtenerCocinasReceta($id_receta);

                    if (!empty($datosCocinas)) {
                      foreach ($datosCocinas as $fila) {
                          $todas_cocinas .= ", " . $fila['cocina'];
                      }
                      echo substr($todas_cocinas, 2); 
                    }
                  ?><br /><span>Kitchen type</span></h4>
                </li>
                <li>
                  <img
                    src="assets/images/LogoDieta.png"
                    alt=""
                    style="max-width: 52px"
                  />
                  <h4><?php 
                    $todas_dieta = '';

                    if (!empty($datosReceta)) {
                      foreach ($datosReceta as $fila) {
                          $todas_dieta .= ", " . $fila['dieta'];
                      }
                      echo substr($todas_dieta, 2); 
                    }
                  ?><br /><span>Compatible diet</span></h4>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer>
      <div class="container">
        <div class="col-lg-8 footer-content">
          <img
            src="assets/images/logoMazapanWhite.png"
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
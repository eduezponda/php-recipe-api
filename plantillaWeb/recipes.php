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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#query').keyup(function() {
                var query = $(this).val();
                if (query != '') {
                    $.ajax({
                        url: "../frontOffice/searchQuery.php",
                        method: "POST",
                        data: {query: query},
                        success: function(data) {
                            $('#result').html(data);
                        }
                    });
                } else {
                    $('#result').html('');
                }
            });
        });
        $(document).ready(function() {
          $('#search').click(function() {
              var query = $('#query').val();
              if (query != '') {
                  $.ajax({
                      url: "../frontOffice/searchQuery.php",
                      method: "POST",
                      data: {querySearch: query},
                      success: function(data) {
                        try {
                          $('#result').html(data);
                            var json = JSON.parse(data);
                            //$('#result').html(json); // Mostrar los IDs en result (opcional)
                            updateRecipeBlocks(json);
                        } catch (e) {
                            console.error("Failed to parse JSON: ", e);
                            //$('#result').html('Error parsing response');
                        }
                      }
                  });
              }
          });
      });

      function updateRecipeBlocks(idRecetas) {
        $('.properties-box').empty();  // Limpia los bloques actuales de recetas
          $.ajax({
              url: '../frontOffice/getRecipeDetails.php',
              method: 'POST',
              dataType: 'html',
              data: { id: idReceta },
              success: function(recipeDetails) {
                  $('.properties-box').append(recipeDetails);  // Añade la receta al contenedor
              },
              error: function(xhr, status, error) {
                  console.error("Error al obtener los detalles de la receta: ", error);
              }
          });
            }
    </script>
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
        <input id="query" type="text" placeholder="Search by food" value="">
        <ul class="properties-filter">
          <li>
            <button id="search" class="is_active" data-filter="*">Search</button>
          </li>
        </ul>
        <div id="result"></div>
        <div class="row properties-box">
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

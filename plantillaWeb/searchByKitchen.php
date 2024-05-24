<?php
  require_once "../frontOffice/funcionalidadesAPI.php";
  session_start();

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

    <title>Mazapan Company-Kitchen</title>

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

    <script src="vendor/jquery/jquery.min.js"></script>
    
    <!-- jQuery UI y su módulo de autocompletado -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

    <script>
        $(document).ready(function() {
            // Configurar autocompletado
            $('#kitchen').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "../frontOffice/searchQuery.php",
                        method: "POST",
                        dataType: 'json',
                        data: { kitchen: request.term },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 1
            });

            // Manejar la búsqueda de recetas
            $('#search').click(function() {
                var kitchen = $('#kitchen').val();
                if (kitchen != '') {
                    $.ajax({
                        url: "../frontOffice/searchQuery.php",
                        method: "POST",
                        dataType: 'json',
                        data: { kitchenSearch: kitchen },
                        success: function(data) {
                            try {
                                updateRecipeBlocks(data, 0);
                                $('.pagination a').removeClass('is_active');
                                $('.pagination a[data-page="0"]').addClass('is_active');
                            } catch (e) {
                                $('#result').html('Error parsing response');
                            }
                        }
                    });
                }
            });

            $('.pagination a').click(function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                var kitchen = $('#kitchen').val();
                
                if (kitchen != '') {
                    $('.pagination a').removeClass('is_active');
                    $(this).addClass('is_active');

                    $.ajax({
                        url: "../frontOffice/searchQuery.php",
                        method: "POST",
                        dataType: 'json',
                        data: { kitchenSearch: kitchen },
                        success: function(data) {
                            try {
                                updateRecipeBlocks(data, page);
                            } catch (e) {
                                $('#result').html('Error parsing response');
                            }
                        }
                    });
                }
            });
        });

        // Función para actualizar los bloques de recetas
        function updateRecipeBlocks(idRecetas, page) {
            $('.properties-box').empty(); // Limpiar los bloques actuales de recetas
            
            // Iterar sobre los primeros 9 índices de idRecetas
            for (var i = page*9; i < (page+1)*9 && i < idRecetas.length; i++) {
                $.ajax({
                    url: '../frontOffice/getRecipeDetails.php',
                    method: 'POST',
                    dataType: 'html',
                    data: { id: idRecetas[i], index: i-page*9 },
                    success: function(recipeDetails) {
                        $('.properties-box').append(recipeDetails);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al obtener los detalles de la receta: ", error);
                    }
                });
            }
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
                  <li><a href="searchByFood.php ">Search by Food</a></li>
                  <li><a href="searchByDiet.php">Search by Diet</a></li>
                  <li><a href="searchByKitchen.php " class="active">Search by Kitchen</a></li>
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
              <span class="breadcrumb"><a href="#">Home</a> / Kitchen</span>
              <h3>Kitchen</h3>
            </div>
          </div>
        </div>
      </div>
      
      <div class="section properties">
        <div class="container">
          <ul class="properties-filter">
          <?php
            if (isset($_SESSION['user_name'])){
                echo '<div class="export-pdf-container">
                      <form id="export-pdf-form" action="exportPDF.php" method="POST">
                          <input type="hidden" name="username" value="' . $_SESSION['user_name'] . '">
                          <button type="submit" class="btn btn-primary">Exportar a PDF</button>
                          </form>
                      </div>';
                for ($i = 0; $i < 3; $i++) {echo "<br>";}
            }
          ?>
            <input id="kitchen" type="text" placeholder="Search by kitchen" value="">
            <li>
              <button id="search" class="is_active" data-filter="*">Search</button>
            </li>
          </ul>
          <div id="result"></div>
          <div class="row properties-box">
          </div>
          <div class=white>
            <?php for ($i = 0; $i < 80; $i++) {echo "<br>";} ?>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <ul class="pagination">
                <li><a class="is_active" href="#" data-page="0">1</a></li>
                <li><a href="#" data-page="1">2</a></li>
                <li><a href="#" data-page="2">3</a></li>
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
    </div>

    <!-- Scripts -->
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/isotope.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/counter.js"></script>
    <script src="assets/js/custom.js"></script>
  </body>
</html>

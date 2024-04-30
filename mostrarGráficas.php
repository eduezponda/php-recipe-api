<?php
    include_once 'funcionalidadesAPI.php';

    $datos = recogerDatosGraficasAPI();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráficos de Recetas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="graficoDietas"></canvas>
    <canvas id="graficoCocinas"></canvas>
    <canvas id="graficoMinutos"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var datosDietas = <?php echo json_encode($datos['dietas']); ?>;
            var datosCocinas = <?php echo json_encode($datos['cocinas']); ?>;
            var datosMinutos = <?php echo json_encode($datos['minutos']); ?>;

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
                    labels: datos.map(dato => `${dato.minutos} min`),
                    datasets: [{
                        label: 'Calorías por Tiempo de Preparación',
                        data: datos.map(dato => dato.calorias),
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
</body>
</html>
<!DOCTYPE HTML>
<?php
    require("../../lib/database.php");
    require("../../lib/sentencias.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Productos mas comentados</title>

		<style type="text/css">

		</style>
	</head>
	<body>
<script src="code/highcharts.js"></script>
<script src="code/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>



		<script type="text/javascript">

Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Productos mas comentados'
    },
    subtitle: {
        text: 'ZevenStore.com'
    },
    xAxis: {
        categories: 
        [
            <?php
                $productos = Sentencias::ComentadosG();
                $n=1;
                foreach($productos as $producto)
                {
            ?>
                '<?php echo($producto["producto"]);?>',
            <?php
                }
            ?>
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Numero de comentarios'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: 
    [
        <?php
            $productos = Sentencias::ComentadosG();
            foreach($productos as $producto)
            {
        ?>
            {
                name: '<?php echo($producto["producto"]);?>',
                data: 
                [
                    <?php echo($producto["numero"]);?>,
                ]
            }, 
        <?php
            }
        ?>
    ]
});
        </script>
	</body>
</html>
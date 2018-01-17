<!DOCTYPE HTML>
<?php
    require("../../lib/database.php");
    require("../../lib/sentencias.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Clientes que mas comentados</title>

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
        text: 'Clientes que mas comentados'
    },
    subtitle: {
        text: 'ZevenStore.com'
    },
    xAxis: {
        categories: 
        [
            <?php
                $clientes = Sentencias::ComprasC();
                $n=1;
                foreach($clientes as $cliente)
                {
            ?>
                '<?php echo($cliente["usuario"]);?>',
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
            $clientes = Sentencias::ComprasC();
            foreach($clientes as $cliente)
            {
        ?>
            {
                name: '<?php echo($cliente["usuario"]);?>',
                data: 
                [
                    <?php echo($cliente["total"]);?>,
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
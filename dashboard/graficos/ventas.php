<!DOCTYPE HTML>
<?php
    require("../../lib/database.php");
    require("../../lib/sentencias.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Ventas por mes</title>

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
        type: 'line'
    },
    title: {
        text: 'Ventas por mes en 2017'
    },
    subtitle: {
        text: 'ZevenStore.com'
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'Ventas ($)'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series:
    [
        {
            name: 'Smartphone vendidos',
            data: 
            [
                <?php
                    $hoy = getdate();
                    $fecha = $hoy["mday"]."-".$hoy["mon"]."-".$hoy["year"];
                    $hora = $hoy["hours"].":".$hoy["minutes"].":".$hoy["seconds"];
                    //$inicio = "'$hoy[year]-$hoy[mon]-01'";
                    //$final = null;
                    $valor = $hoy["mon"]; 
                    //$mes = Render::Mes($valor);

                    for ($i=1; $i < 13; $i++) 
                    { 
                        $inicio = "'$hoy[year]-$i-01'";
                        $final = null;

                        if($i == 1 || $i == 3 || $i == 5 || $i == 7 || $i == 8 || $i == 10 || $i == 12)
                        {
                            $final = "'$hoy[year]-$i-31'";
                        }
                    
                        if($i == 4 || $i == 6 || $i == 9 || $i == 11)
                        {
                            $final = "'$i[year]-$i-31'";
                        }
                    
                        if($i == 2)
                        {
                            $final = "'$i[year]-$i-28'";
                        }

                        $vendido = Sentencias::VentasMA($inicio, $final);
                        if($vendido["ventas"] == null)
                        {
                            echo(0 . ",");
                        }

                        else
                        {
                            echo($vendido["ventas"].",");
                        }                     
                        //echo($i.",");
                    }
                ?>
            //7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6
            ]
        },
    ]
});
        </script>
        
        
	</body>
</html>
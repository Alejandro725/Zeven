<?php
    require('../fpdf/fpdf.php');
    require("../../lib/database.php");
    require("../../lib/sentencias.php");
    require('../../lib/renders.php');

    session_start();

    //Se hereda la clase para modificar el header y el footer del documento
    class PDF extends FPDF
    {
        //Cabecera
        function Header()
        {
            $this->Image('zeven.png',10,8,33);
            $this->SetFont('Arial','B',15);
            $this->Cell(40);
            $this->Cell(120,10,'Reporte de ventas por mes',0,1,'C');
            $this->Ln(30);
        }

        //Footer
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,'Page '.$this->PageNo()."/{nb}",0,0,'C');
        }

        //Funcion para crear un titulo al reporte
        function Titulo($titulo)
        {
            $this->SetFont('Arial', '', 12);
            $this->SetFillColor(30,136,229);
            $this->Cell(0, 6, $titulo, 0, 1, 'C', true);
            $this->Ln(4);
        }

        //Funcion para crear una tabla
        function Tabla($headers, $data)
        {
            $this->SetFillColor(255,0,0);
            $this->SetDrawColor(13,71,161);
            $this->SetLineWidth(.5);
            $this->SetFont('','B');

            //Se genera la cabecera
            foreach($headers as $col)
            {
                $this->Cell(63, 7, $col, 1, 0, 'C');
            }
            $this->Ln();

            $this->SetFont('','');

            //Se imprimen los datos
            foreach($data as $row)
            {
                $this->Cell(63, 6, $row["producto"], 1, 0, 'C');
                $this->Cell(63, 6, $row["total"], 1, 0, 'C');
                $this->Cell(63, 6, "$".$row["ventas"], 1, 0, 'C');
                $this->Ln();
            }
        }
    }

    if(isset($_SESSION["id"]))
    {
        $hoy = getdate();
        $fecha = $hoy["mday"]."-".$hoy["mon"]."-".$hoy["year"];
        $hora = $hoy["hours"].":".$hoy["minutes"].":".$hoy["seconds"];
        $inicio = "'$hoy[year]-$hoy[mon]-01'";
        $final = null;
        $valor = $hoy["mon"]; 
        $mes = Render::Mes($valor);

        //Se crea un switch para los dias del mes
        if($valor == 1 || $valor == 3 || $valor == 5 || $valor == 7 || $valor == 8 || $valor == 10 || $valor == 12)
        {
            $final = "'$hoy[year]-$hoy[mon]-31'";
        }
    
        if($valor == 4 || $valor == 6 || $valor == 9 || $valor == 11)
        {
            $final = "'$hoy[year]-$hoy[mon]-31'";
        }
    
        if($valor == 2)
        {
            $final = "'$hoy[year]-$hoy[mon]-28'";
        }
        $productos = Sentencias::VentasPM($inicio, $final);

        //Se instancia la variable que lleva la clase
        $pdf = new PDF('P');
        $pdf->Addpage();
        $pdf->SetFont('Arial', '', 11);
    
        //Se agrega la celda principal
        $primero = 'Ventas en el mes de '.$mes.'';
        $pdf->Titulo($primero);
        //$pdf->Cell(180, 10, 'Productos ingresados en el mes de '.$mes.'', 0, 1, 'C');
        $segundo = "Reporte creado por ".$_SESSION["nombre"]." en el $fecha a las $hora.";
        $pdf->Titulo($segundo);
        //$pdf->Cell(180, 10, "Reporte creado por $usuario en el $fecha a las $hora.", 0, 1, 'C');
        $pdf->Ln(10);

        //Se agregan las celdas en donde se pondran los datos
        $headers = array('Producto', 'Unidades', 'Ventas');
        $pdf->Tabla($headers, $productos);
    
        $pdf->Output();
    }

    else
    {
        header("../menu.php");
    }
?>
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
            $this->Cell(120,10,'Reporte de comentarios por productos',0,1,'C');
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
                $this->Cell(63, 6, $row["usuario"], 1, 0, 'C');
                $this->Cell(63, 6, $row["comentario"], 1, 0, 'C');
                $this->Cell(63, 6, $row["fecha"], 1, 0, 'C');
                $this->Ln();
            }
        }
    }

    if(isset($_SESSION["id"]))
    {
        $hoy = getdate();
        $fecha = $hoy["mday"]."-".$hoy["mon"]."-".$hoy["year"];
        $hora = $hoy["hours"].":".$hoy["minutes"].":".$hoy["seconds"];
        $valor = $hoy["mon"]; 
        $mes = Render::Mes($valor);

        $productos = Sentencias::ComentariosP($_GET["id_producto"]);
        $producto = Sentencias::Seleccionar("productos", "id", array($_GET["id_producto"]), 0, null);

        //Se instancia la variable que lleva la clase
        $pdf = new PDF('P');
        $pdf->Addpage();
        $pdf->SetFont('Arial', '', 11);
    
        //Se agrega la celda principal
        $primero = 'Comentarios del producto '.$producto["producto"].'';
        $pdf->Titulo($primero);
        //$pdf->Cell(180, 10, 'Productos ingresados en el mes de '.$mes.'', 0, 1, 'C');
        $segundo = "Reporte creado por ".$_SESSION["nombre"]." en el $fecha a las $hora.";
        $pdf->Titulo($segundo);
        //$pdf->Cell(180, 10, "Reporte creado por $usuario en el $fecha a las $hora.", 0, 1, 'C');
        $pdf->Ln(10);

        //Se agregan las celdas en donde se pondran los datos
        $headers = array('Usuario', 'Comentario', 'Fecha');
        $pdf->Tabla($headers, $productos);
    
        $pdf->Output();
    }

    else
    {
        header("../menu.php");
    }
?>
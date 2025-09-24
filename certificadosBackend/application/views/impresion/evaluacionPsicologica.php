<?php 

class MYPDF extends TCPDF
{
  public function Header(){}
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('dejavusans', 'I', 8);
    }
}
$data = json_decode($json);
$pageLayout = [216, 279];
$pdf = new MYPDF('P', 'mm', $pageLayout, true, 'UTF-8', false);
//$pdf->SetAutoPageBreak(true, 10); 
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('centromedico');
$pdf->SetTitle('examen medico');
$pdf->SetSubject('examen medico A');
$pdf->SetKeywords('TCPDF, CodeIgniter, PDF, Voucher');
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(5);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
  require_once(dirname(__FILE__) . '/lang/eng.php');
  $pdf->setLanguageArray($l);
}
$pdf->setFontSubsetting(true);
$pdf->SetMargins(25, 20, 20);
$pdf->SetAutoPageBreak(TRUE, 10);

$margen=0 ;

$pdf->AddPage();
  $logoWidth = 26;
  $logoX = $pdf->GetX();
  $logoY = $pdf->GetY();

  //$rutaImagen =  $data->foto;
  //$pdf->Image($rutaImagen, $logoX+140, $logoY+15, $logoWidth, '0', 'PNG');
  $pdf->SetFont('helvetica', 'B', 14);
  $afterLogoY = $logoY + $logoWidth + 2;
  $pdf->SetXY($pdf->getMargins()['left']-10,$afterLogoY-12);
    
  //nombre completo
  $pdf->SetFont('helvetica', 'N', 10);
  $pdf->SetXY($pdf->GetX()+8, $pdf->GetY()+15); // asegura posición
  $pdf->Cell(33, 5, $data->ap_paterno, $margen, 0, 'C');
  $pdf->Cell(3, 5, "", $margen, 0, 'C');
  $pdf->Cell(35, 5, $data->ap_materno, $margen, 0, 'C');
  $pdf->Cell(5, 5, "", $margen, 0, 'C');
  $pdf->Cell(40, 5, $data->nombre, $margen, 0, 'C');
  //edad
  $pdf->Cell(10, 5, "", $margen, 0, 'C');
  $pdf->Cell(10, 5, $data->edad, $margen, 0, 'C');
  //ci
  $pdf->Cell(8, 5, "", $margen, 0, 'C');
  $pdf->Cell(30, 5, $data->ci, $margen, 1, 'C');
  $pdf->SetXY($pdf->GetX()-3, $pdf->GetY()+12); // asegura posición
  //fecha
  $pdf->Cell(48, 5, $data->lugar_nacimiento.' '.$data->fecha_nacimiento, $margen, 0, 'L');
  //profesion
  $pdf->Cell(8, 5, "", $margen, 0, 'C');
  $pdf->Cell(45, 5, $data->profecion, $margen, 0, 'r');
  //fecha examen
  $pdf->Cell(8, 5, "", $margen, 0, 'C');
  $pdf->Cell(40, 5, $data->fecha_evaluacion, $margen, 1, 'r');
  //DOMICILIO
  $pdf->SetXY($pdf->GetX()-3, $pdf->GetY()+12); // asegura posición
  $pdf->Cell(45, 5, $data->domicilio, $margen, 0, 'r');
  // nro
  $pdf->Cell(3, 5, "", $margen, 0, 'C');
  $pdf->Cell(17, 5, $data->numero_domicilio, $margen, 0, 'C');
  // zona
  $pdf->Cell(3, 5, "", $margen, 0, 'C');
  $pdf->Cell(40, 5, $data->zona, $margen, 0, 'C');
  // telefono
  $pdf->Cell(2, 5, "", $margen, 0, 'C');
  $pdf->Cell(28, 5, $data->telefono, $margen, 1, 'C');
  // HISTORIAL FAMILIAR
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetXY($pdf->GetX()+2, $pdf->GetY()+10); // asegura posición
  $pdf->Cell(35, 5, "", $margen, 0, 'C');
  $texto='SE PRESENTA A CONSULTA SUJETO DE '.$data->historia_familiar.'  AÑOS DE EDAD SIN ANTECEDENTES   PSICOLOGICOS PERSONALES O FAMILIARES DESTACABLES';
  $pdf->MultiCell(138, 5, $texto, $margen, 'L');
   // examen psicologico
  /*
   $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  $pdf->Cell(38, 5, "", $margen, 0, 'C');
  $pdf->MultiCell(133, 5, $data->historial_familiar, $margen, 'L');
  */
  //coordinacion visomotora
  $pdf->SetFont('helvetica', 'N', 10);
  $pdf->SetXY($pdf->GetX()+4, $pdf->GetY()+25); // asegura posición
  switch (trim($data->coordinacion_visomotora)) {
    case 'adecuado':
        $pdf->Cell(35, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(80, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'observacion':
        $pdf->Cell(125, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    
  }
  //PERSONALIDAD
  $pdf->SetXY($pdf->GetX()+4, $pdf->GetY()+17); // asegura posición
  switch (trim($data->personalidad)) {
    case 'adecuado':
        $pdf->Cell(35, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(80, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'observacion':
        $pdf->Cell(125, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    
  }
  //memoria
  $pdf->SetXY($pdf->GetX()+4, $pdf->GetY()+20); // asegura posición
  switch (trim($data->atencion_cognitiva)) {
    case 'adecuado':
        $pdf->Cell(35, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(80, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'opservacion':
        $pdf->Cell(125, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    
  }
  //estres
  $pdf->SetXY($pdf->GetX()+4, $pdf->GetY()+25); // asegura posición
  switch (trim($data->reaccion_estres_riego)) {
    case 'optio':
        $pdf->Cell(33, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'medio':
        $pdf->Cell(75, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(125, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    
  }

   // observaciones
  $pdf->SetFont('helvetica', 'N', 6);
  $pdf->SetXY($pdf->GetX()+2, $pdf->GetY()+6); // asegura posición
  $pdf->Cell(30, 5, "", $margen, 0, 'C');
  $pdf->MultiCell(133, 5, '(EN ESTE ACAPITE SE DEBE INCORPORAR SI EL POSTULANTE ES APTO, SI NO FUERA APTO INDICAR LOS MOTIVOS)', $margen, 'L');
  
   // observaciones
  $pdf->SetFont('helvetica', 'B', 7);
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+1); // asegura posición
  //$pdf->MultiCell(165, 5, $data->observacion, $margen, 'L');
  //$pdf->MultiCell(165, 5, 'DEL PRESENTE APTO DE EVALUACION PSICOLOGICA DE ACUERDO A LOS RESULTADOS  OBTENIDOS DE LAS PRUEBAS APLICADAS CONCLUYO QUE EL INTERESADO, NO PRESENTA ALTERACIONES FUNCIONALES SIGNIFICATIVOS O DISMINUIDAS. EL PRESENTE OSTENTA CAPACIDADES ADECUADAS EN LO QUE CONCLUYO ES APTO PARA CONDUCIR VEHICULOS ', $margen, 'L');
  $pdf->MultiCell(165, 5, 'DEL PRESENTE APTO DE EVALUACION PSICOLOGICA DE ACUERDO A LOS RESULTADOS  OBTENIDOS DE LAS PRUEBAS APLICADAS CONCLUYO QUE EL INTERESADO, NO PRESENTA ALTERACIONES FUNCIONALES SIGNIFICATIVOS O DISMINUIDAS. EL PRESENTE OSTENTA CAPACIDADES ADECUADAS EN LO QUE CONCLUYO ES APTO PARA CONDUCIR VEHICULOS ', $margen, 'L');
  






$pdf->Output('movimiento_caja.pdf', 'I');
?>
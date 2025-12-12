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
  //imagen
   //imagen
  $rutaImagen =  $data->foto;
  $nombreArchivo = basename($data->foto); // => "6.jpg"
  // Ruta física completa en tu servidor
  $rutaImagen = FCPATH . "assets/inv_evaluacion_psicologico/" . $nombreArchivo;
  // Área máxima permitida (en mm: 1 cm = 10 mm)
  $maxWidth = 25;  // 3 cm
  $maxHeight = 30; // 2.5 cm

   if (file_exists($rutaImagen)) {
      // Obtener tamaño real de la imagen (en píxeles)
      list($width, $height) = getimagesize($rutaImagen);
      // Calcular proporción
      $xRatio = $maxWidth / $width;
      $yRatio = $maxHeight / $height;
      // Escala proporcional (usa el factor menor)
      $scale = min($xRatio, $yRatio);
      $newWidth = $width * $scale;
      $newHeight = $height * $scale;
      // Dibujar imagen con tamaño ajustado
      $pdf->Image($rutaImagen, $logoX + 140, $logoY + 37, $newWidth, $newHeight, '', '', false, 300);
    } else {
        $pdf->Cell(0, 5, "Imagen no encontrada: " . $rutaImagen, 0, 1, 'L');
    }
  //$rutaImagen =  $data->foto;
  //$pdf->Image($rutaImagen, $logoX+140, $logoY+15, $logoWidth, '0', 'PNG');
  //$pdf->SetFont('helvetica', 'B', 14);
  //$afterLogoY = $logoY + $logoWidth + 2;
  //$pdf->SetXY($pdf->getMargins()['left']-10,$afterLogoY-12);
    
  //nombre completo
  $pdf->SetFont('helvetica', 'N', 10);
  $pdf->SetXY($pdf->GetX()-10, $pdf->GetY()+36); // asegura posición
  $pdf->Cell(30, 5, $data->ap_paterno, $margen, 0, 'C');
  $pdf->Cell(3, 5, "", $margen, 0, 'C');
  $pdf->Cell(30, 5, $data->ap_materno, $margen, 0, 'C');
  $pdf->Cell(1, 5, "", $margen, 0, 'C');
  $pdf->Cell(30, 5, $data->nombre, $margen, 0, 'C');
  //edad
  //ci
  $pdf->Cell(5, 5, "", $margen, 0, 'C');
  $pdf->Cell(28, 5, $data->ci, $margen, 1, 'C');
  $pdf->SetXY($pdf->GetX()-4, $pdf->GetY()+13); // asegura posición
  //fecha
  $fecha = date("d/m/Y", strtotime($data->fecha_nacimiento));
  $pdf->Cell(48, 5, $data->lugar_nacimiento.' '.$fecha, $margen, 0, 'L');
  //profesion
  $pdf->Cell(23, 5, "", $margen, 0, 'C');
  $pdf->Cell(45, 5, $data->profecion, $margen, 1, 'L');
  //DOMICILIO
  $pdf->SetXY($pdf->GetX()-3, $pdf->GetY()+7); // asegura posición
  $pdf->Cell(40, 5, $data->domicilio, $margen, 0, 'r');
  // nro
  $pdf->Cell(1, 5, "", $margen, 0, 'C');
  $pdf->Cell(13, 5, $data->numero_domicilio, $margen, 0, 'C');
  // zona
  $pdf->Cell(8, 5, "", $margen, 0, 'C');
  $pdf->Cell(35, 5, $data->zona, $margen, 0, 'C');
  // telefono
  $pdf->Cell(2, 5, "", $margen, 0, 'C');
  $pdf->Cell(28, 5, $data->telefono, $margen, 1, 'C');
  // HISTORIAL medico
  $pdf->SetFont('helvetica', 'N', 12);
  $pdf->SetXY($pdf->GetX()-2, $pdf->GetY()+18); // asegura posición
  $pdf->MultiCell(155, 15, $data->historia_medica, $margen, 'L');
  // HISTORIAL FAMILIAR
  $pdf->SetFont('helvetica', 'N', 12);
  $pdf->SetXY($pdf->GetX()-2, $pdf->GetY()+11); // asegura posición
  $pdf->MultiCell(155, 15, $data->historia_familiar, $margen, 'L');
  //niveles de estres
  $pdf->SetFont('helvetica', 'N', 10);
  $pdf->SetXY($pdf->GetX()+2, $pdf->GetY()+59); // asegura posición
  switch (trim($data->niveles_estres??'')) {
    case 'adecuado':
        $pdf->Cell(10, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'medio':
        $pdf->Cell(50, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(101, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'observación':
        $pdf->Cell(151, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }
  
  //niveles de estres
  $pdf->SetFont('helvetica', 'N', 10);
  $pdf->SetXY($pdf->GetX()+2, $pdf->GetY()+16); // asegura posición
  switch (trim($data->estrategias_afrontamiento??'')) {
    case 'adecuado':
        $pdf->Cell(10, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'medio':
        $pdf->Cell(50, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(101, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'observación':
        $pdf->Cell(151, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }

  
  //vulnerabilidad_emocional
  $pdf->SetFont('helvetica', 'N', 10);
  $pdf->SetXY($pdf->GetX()+2, $pdf->GetY()+21); // asegura posición
  switch (trim($data->vulnerabilidad_emocional??'')) {
    case 'adecuado':
        $pdf->Cell(10, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'medio':
        $pdf->Cell(50, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(101, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'observación':
        $pdf->Cell(151, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }

  $pdf->AddPage();
  $pdf->SetFont('helvetica', 'N', 10);
  
  //atencion_sostenida_selectiva
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetXY($pdf->GetX()+5, $pdf->GetY()+26); // asegura posición
  switch (trim($data->atencion_sostenida_selectiva??'')) {
    case 'adecuado':
        $pdf->Cell(11, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'medio':
        $pdf->Cell(52, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(104, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'observación':
        $pdf->Cell(152, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }

  //capacidad_reaccion
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetXY($pdf->GetX()+5, $pdf->GetY()+21); // asegura posición
  switch (trim($data->capacidad_reaccion??'')) {
    case 'adecuado':
        $pdf->Cell(11, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'medio':
        $pdf->Cell(52, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(104, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'observación':
        $pdf->Cell(152, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }

  //control_impulso:                    string;
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetXY($pdf->GetX()+5, $pdf->GetY()+16); // asegura posición
  switch (trim($data->control_impulso??'')) {
    case 'adecuado':
        $pdf->Cell(11, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'medio':
        $pdf->Cell(52, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(104, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'observación':
        $pdf->Cell(152, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }

  //actidud_norma_autoridad
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetXY($pdf->GetX()+5, $pdf->GetY()+17); // asegura posición
  switch (trim($data->actidud_norma_autoridad??'')) {
    case 'adecuado':
        $pdf->Cell(11, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'medio':
        $pdf->Cell(52, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(104, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'observación':
        $pdf->Cell(152, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }

  //capacidad_reaccion
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetXY($pdf->GetX()+5, $pdf->GetY()+16); // asegura posición
  switch (trim($data->estabilidad_emocional??'')) {
    case 'adecuado':
        $pdf->Cell(11, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'medio':
        $pdf->Cell(52, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'inadecuado':
        $pdf->Cell(104, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'observación':
        $pdf->Cell(152, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }
  // Hresultado_recomendacion
  $pdf->SetFont('helvetica', 'N', 10);
  $pdf->SetXY($pdf->GetX()-2, $pdf->GetY()+13); // asegura posición
  $pdf->MultiCell(165, 15, $data->resultado_recomendacion, $margen, 'L');
  // observacion
  $pdf->SetFont('helvetica', 'N', 9);
  $pdf->SetXY($pdf->GetX()-2, $pdf->GetY()+10); // asegura posición
  $pdf->MultiCell(165, 15, $data->observacion, $margen, 'L');
  // lugar y fecha
  $pdf->SetFont('helvetica', 'N', 9);
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+15); // asegura posición
  $pdf->MultiCell(165, 15, 'Cochabamba, '.date("d/m/Y") , $margen, 'L');







$pdf->Output('movimiento_caja.pdf', 'I');
?>
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
  $rutaImagen =  $data->foto;
 // $pdf->Cell(5, 5, $rutaImagen, $margen, 0, 'l');
 // $pdf->Image($rutaImagen, $logoX+140, $logoY+15, $logoWidth, '0', '');
  
  // $data->foto viene como: "http://localhost/amondocorre/assets/evaluacion_medica/6.jpg"
// Lo transformamos en ruta física

$nombreArchivo = basename($data->foto); // => "6.jpg"

// Ruta física completa en tu servidor
$rutaImagen = FCPATH . "assets/evaluacion_medica/" . $nombreArchivo;

// Para depuración: verificar si existe
if (file_exists($rutaImagen)) {
    $pdf->Image($rutaImagen, $logoX + 140, $logoY + 18, $logoWidth, 0, '', '', false, 300);
} else {
    $pdf->Cell(0, 5, "Imagen no encontrada: " . $rutaImagen, 0, 1, 'L');
}




  //$pdf->Image($data->foto, $logoX-10, $logoY-10, $logoWidth, '', 'PNG');
  //$pdf->SetFont('helvetica', 'B', 14);
  $afterLogoY = $logoY + $logoWidth + 2;
  $pdf->SetXY($pdf->getMargins()['left']-10,$afterLogoY-12);
    
  //nombre completo
  $pdf->SetFont('helvetica', 'N', 10);
  $pdf->Cell(5, 5, "", $margen, 0, 'C');
  $pdf->Cell(35, 5, $data->ap_paterno, $margen, 0, 'C');
  $pdf->Cell(7, 5, "", $margen, 0, 'C');
  $pdf->Cell(35, 5, $data->ap_materno, $margen, 0, 'C');
  $pdf->Cell(7, 5, "", $margen, 0, 'C');
  $pdf->Cell(45, 5, $data->nombre, $margen, 1, 'C');
  //ci
  $pdf->Cell(5, 11, "", $margen, 1, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(30, 5, $data->ci, $margen, 0, 'C');
  $pdf->Cell(8, 5, "", $margen, 0, 'C');
  //edad
  $pdf->Cell(10, 5, $data->edad, $margen, 0, 'C');
  $pdf->Cell(15, 5, "", $margen, 0, 'C');
  //sexo
  $pdf->Cell(10, 5, $data->sexo, $margen, 0, 'C');
  $pdf->Cell(5, 5, "", $margen, 0, 'C');
  $pdf->Cell(27, 5, "COCHABAMBA, ".$data->fecha_evaluacion, $margen, 0, 'L');
  //fecha
  //$pdf->Cell(20, 5, $data->fecha, $margen, 1, 'L');
  //antecedentes_rc
  $pdf->SetXY($pdf->GetX()+4, $pdf->GetY()+2); // asegura posición
  $pdf->Cell(5, 21, "", $margen, 1, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(45, 5, "", $margen, 0, 'C');
  $pdf->MultiCell(110, 10, utf8_decode($data->antecendentes_rc), $margen, 'L');
  //antecedentes_pp
  //$pdf->Cell(5, 5, "", $margen, 1, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(45, 5, "", $margen, 0, 'C');
  $pdf->MultiCell(110, 10, utf8_decode($data->antecendentes_pp), $margen, 'L');
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+2); // asegura posición
  switch (trim($data->bebe)) {
    case 'Nunca':
        $pdf->Cell(35, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'Ocasionalmente':
        $pdf->Cell(73, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'Una o más a la semana':
        $pdf->Cell(136, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    
  }
  //fuma
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+5); // asegura posición
  switch (trim($data->fuma)) {
    case 'Nunca':
        $pdf->Cell(35, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'Ocasionalmente':
        $pdf->Cell(73, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'Una o más a la semana':
        $pdf->Cell(136, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    
  }
  //fiebre amarilla
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+7); // asegura posición
  switch (trim($data->f_amarilla)) {
    case '1':
        $pdf->Cell(43, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 0, 'C');
        $pdf->Cell(15, 5, "", $margen, 0, 'C');
        break;
    case '0':
        $pdf->Cell(58, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 0, 'C');
        break;
   
  }
  //antitetanica
  switch (trim($data->antitetanica)) {
    case '1':
        $pdf->Cell(62, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case '0':
        $pdf->Cell(77, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }
  //grupo sanguineo
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+9); // asegura posición
  $pdf->Cell(70, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(35, 5, $data->grupo_sanguineo, $margen, 1, 'C');
  //temperatura
  $pdf->Cell(5, 5, "", $margen, 1, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(40, 4, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(23, 5, $data->temperatura, $margen, 0, 'C');
  //presion arterial
  $pdf->Cell(35, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $presion = str_replace("/", "  ", $data->presion_arterial);
  $pdf->Cell(30, 5, $presion, $margen, 1, 'C');
  //frecuencia_cardiaca
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+2); // asegura posición
  $pdf->Cell(25, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(20, 5, $data->frecuencia_cardiaca, $margen, 0, 'C');
  //frecuencia_respiratoria
  $pdf->Cell(70, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(30, 5, $data->frecuencia_respiratoria, $margen, 1, 'C');
  //talla
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+5); // asegura posición
  $pdf->Cell(35, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(25, 5, $data->talla, $margen, 0, 'C');
  //peso
  $pdf->Cell(25, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(20, 5, $data->peso, $margen, 1, 'C');
  $pdf->SetFont('helvetica', 'N', 8);
  //cabeza
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+18); // asegura posición
  $pdf->Cell(10, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()); // asegura posición
  $pdf->MultiCell(150, 5, utf8_decode($data->cabeza), $margen, 'L');
  //cara
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+1); // asegura posición
  $pdf->Cell(10, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()); // asegura posición
  $pdf->MultiCell(150, 5, utf8_decode($data->cara), $margen, 'L');
  //cuello
    $pdf->SetXY($pdf->GetX(), $pdf->GetY()+1); // asegura posición
  $pdf->Cell(10, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()); // asegura posición
  $pdf->MultiCell(150, 5, utf8_decode($data->cuello), $margen, 'L');
    $pdf->SetFont('helvetica', 'N', 10);
  //ex_general_ojos
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  $pdf->Cell(80, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(70, 5, $data->ex_general_ojos, $margen, 1, 'L');
  //movimiento_oculares
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+1); // asegura posición
  $pdf->Cell(80, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(70, 5, $data->movimiento_oculares, $margen, 1, 'L');
  //reflejo_luminoso_corneal
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+1); // asegura posición
  $pdf->Cell(80, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(70, 5, $data->reflejo_luminoso_corneal, $margen, 1, 'L');
  //estrabismo
   $pdf->SetXY($pdf->GetX(), $pdf->GetY()+1); // asegura posición

  switch (trim($data->estrabismo)) {
    case '1':
        $pdf->Cell(74, 5, "", $margen, 0, 'C');
        $pdf->Cell(9, 5, "X", $margen, 1, 'C');
        break;
    case '0':
        $pdf->Cell(113, 5, "", $margen, 0, 'C');
        $pdf->Cell(9, 5, "X", $margen, 1, 'C');
        break;
  }
  //USA LENTES
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+1); // asegura posición
  switch (trim($data->usa_lentes)) {
    case '1':
        $pdf->Cell(74, 5, "", $margen, 0, 'C');
        $pdf->Cell(9, 5, "X", $margen, 0, 'C');
        $pdf->Cell(50, 5, "", $margen, 0, 'C');
        $pdf->Cell(43, 5, $data->tipo_lentes, $margen, 1, 'L');
        break;
    case '0':
        $pdf->Cell(113, 5, "", $margen, 0, 'C');
        $pdf->Cell(9, 5, "X", $margen, 0, 'C');
        $pdf->Cell(13, 5, "", $margen, 0, 'C');
        $pdf->Cell(33, 5, $data->tipo_lentes, $margen, 1, 'L');
        break;
  }
    //cirugia
 $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  switch (trim($data->cirugia)) {
    case '1':
        $pdf->Cell(73, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case '0':
        $pdf->Cell(112, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }
  //CAPIMETRIA
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  $pdf->Cell(67, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(30, 5, $data->campimetria, $margen, 0, 'L');
  $pdf->Cell(31, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(30, 5, $data->colorimetria, $margen, 1, 'L');
  //od od
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+6); // asegura posición
  //$pdf->Cell(5, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(17, 5, $data->od_con_lentes, $margen, 0, 'C');
  $pdf->Cell(17, 5, $data->od_sin_lentes, $margen, 0, 'C');
  $pdf->Cell(17, 5, $data->od_correccion, $margen, 0, 'C');
  $pdf->Cell(40, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(35, 5, $data->vision_profunda, $margen, 1, 'l');
  //oi od
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+1); // asegura posición
  //$pdf->Cell(5, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(17, 5, $data->oi_con_lentes, $margen, 0, 'C');
  $pdf->Cell(17, 5, $data->oi_sin_lentes, $margen, 0, 'C');
  $pdf->Cell(17, 5, $data->oi_correccion, $margen, 0, 'C');
  $pdf->Cell(53, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(40, 5, $data->dx_lampara_hendidura, $margen, 1, 'l');
  
  // --- Termina primera hoja ---


  // 👉 Agregar nueva página
  $pdf->AddPage();
  $pdf->SetFont('helvetica', 'N', 10);
  
  //oido externo
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+22); // asegura posición
  $pdf->Cell(30, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(140, 4, utf8_decode($data->oido_externo), $margen, 'L');
  //$pdf->SetFont('helvetica', 'N', 10);
  //otoscpia
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+4); // asegura posición
  $pdf->Cell(15, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(45, 5, $data->oroscopia, $margen, 0, 'l');
  //t_weber
  $pdf->Cell(18, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(38, 5, $data->t_weber, $margen, 0, 'l');
  //t_rinne
  $pdf->Cell(15, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(40, 5, $data->t_rinne, $margen, 1, 'l');
  //torax
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+12); // asegura posición
  $pdf->Cell(28, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(140, 5, $data->torax, $margen, 1, 'l');
  //cardiopulmonar
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  $pdf->Cell(45, 4, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(120, 5, utf8_decode($data->cardiopolmunar), $margen, 'L');
  //abdomen
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+8); // asegura posición
  $pdf->Cell(30, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(135, 5, utf8_decode($data->abdomen), $margen, 'L');
  //trofismo s
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+11); // asegura posición
  $pdf->Cell(45, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(30, 5, $data->s_trofismo, $margen, 0, 'C');
  //trofismo i
  $pdf->Cell(60, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(30, 5, $data->i_trofismo, $margen, 1, 'C');
  //muscular s
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  $pdf->Cell(45, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(30, 5, $data->s_tono_muscular, $margen, 0, 'C');
  //muscular i
  $pdf->Cell(60, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(30, 5, $data->i_tono_muscular, $margen, 1, 'C');
  //fuerza muscular s
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  $pdf->Cell(45, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(30, 5, $data->s_fuerza_muscular, $margen, 0, 'C');
  //fuerza muscular i
  $pdf->Cell(60, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->Cell(30, 5, $data->i_fuerza_muscular, $margen, 1, 'C');
  //COORDINACION Y MArCHA
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+10); // asegura posición
  $pdf->Cell(48, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(110, 5, utf8_decode($data->cordinacion_marcha), $margen, 'L');
  //reflejos osteotendiosos
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  $pdf->Cell(48, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(110, 5, utf8_decode($data->reflejos_osteotendinosos), $margen, 'L');
  //talon rodilla
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+7); // asegura posición
  $pdf->Cell(35, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(130, 5, utf8_decode($data->talon_rodilla), $margen, 'L');
  //dedo nariz
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+2); // asegura posición
  $pdf->Cell(35, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(130, 5, utf8_decode($data->dedo_nariz), $margen, 'L');
  //romberg
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+8); // asegura posición
  $pdf->Cell(32, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(130, 5, utf8_decode($data->romberg), $margen, 'L');
  //motoras sensitivas
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  $pdf->Cell(60, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(80, 5, utf8_decode($data->motoras_sensetivas_diagnosticadas), $margen, 'L');
   //evaluacion de especialidad
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+6); // asegura posición
  ////////////
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  $pdf->Cell(60, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(80, 5, 'eavulacio especialidad'.trim($data->requiere_evaluacion_especialidad), $margen, 'L');
   
  ////////////
  switch (trim($data->requiere_evaluacion_especialidad)) {
    case 'SI':
        $pdf->Cell(70, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'NO':
        $pdf->Cell(125, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }
   //motivo de especialidad
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+2); // asegura posición
  $pdf->Cell(60, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(80, 5, utf8_decode($data->evaluacion_especialidad), $margen, 'L');
   //resultado motivo de especialidad
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  $pdf->Cell(65, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(80, 5, utf8_decode($data->resultado_evaluacion), $margen, 'L');
  if ($data->resultado_evaluacion == '- NO ES APTO PARA CONDUCIR VEHICULOS INDICAR LOS MOTIVOS.'){
    $pdf->SetTextColor(255, 0, 0);
  }
    //evaluacion de psicosensometirca
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+4); // asegura posición

  ////////////
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3); // asegura posición
  $pdf->Cell(60, 5, "", $margen, 0, 'C'); // SALTO DE LINEA ANCHO
  $pdf->MultiCell(80, 5, 'eavulacio psicometrica'.trim($data->requiere_evaluacion_psicosensometria), $margen, 'L');
   
  ////////////

  switch (trim($data->requiere_evaluacion_psicosensometria)) {
    case 'SI':
        $pdf->Cell(78, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
    case 'NO':
        $pdf->Cell(133, 5, "", $margen, 0, 'C');
        $pdf->Cell(8, 5, "X", $margen, 1, 'C');
        break;
  }
  //resultado de la evaluacion
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+28); // asegura posición
  $pdf->MultiCell(170, 5, utf8_decode($data->motivo_resultado), $margen, 'C');










  $pdf->SetFont('helvetica', '', 10);

$pdf->Output('movimiento_caja.pdf', 'I');
?>
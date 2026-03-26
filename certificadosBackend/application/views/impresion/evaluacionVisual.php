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
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('centromedico');
$pdf->SetTitle('evaluacion visual');
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(5);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$pdf->SetMargins(20, 20, 20);
$pdf->SetAutoPageBreak(TRUE, 10);

$pdf->AddPage();

// Logo and Header
$logoHeader = FCPATH . 'assets/logos/evaluacionvisual.png';
if (file_exists($logoHeader)) {
    $pdf->Image($logoHeader, 20, 10, 176, 0, '', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    $pdf->Ln(20);
} else {
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(0, 10, 'EVALUACIÓN VISUAL', 0, 1, 'C');
    $pdf->Ln(5);
}

$logoX = $pdf->GetX();
$logoY = $pdf->GetY();

// Photo logic
if (!empty($data->foto)) {
    $nombreArchivo = basename($data->foto);
    $rutaImagen = FCPATH . "assets/evaluacion_visual/" . $nombreArchivo;
    $maxWidth = 25;
    $maxHeight = 30;

    if (file_exists($rutaImagen)) {
        list($width, $height) = getimagesize($rutaImagen);
        $xRatio = $maxWidth / $width;
        $yRatio = $maxHeight / $height;
        $scale = min($xRatio, $yRatio);
        $newWidth = $width * $scale;
        $newHeight = $height * $scale;
        $pdf->Image($rutaImagen, $logoX + 150, $logoY, $newWidth, $newHeight, '', '', false, 300);
    }
}


// Names and Personal Info
$pdf->SetY($pdf->GetY()+10);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(60, 8, $data->ap_paterno, 0, 0, 'C');
$pdf->Cell(60, 8, $data->ap_materno, 0, 0, 'C');
$pdf->Cell(60, 8, $data->nombre, 0, 1, 'C');
//$pdf->Ln(2);
$pdf->SetY($pdf->GetY()-3);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(170, 2, '________________________                 ______________________                  __________________', 0, 1, 'C');
$pdf->Cell(60, 5, 'APELLIDO PATERNO', 0, 0, 'C');
$pdf->Cell(60, 5, 'APELLIDO MATERNO', 0, 0, 'C');
$pdf->Cell(60, 5, 'NOMBRES', 0, 1, 'C');


$pdf->SetY($pdf->GetY()+5);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(40, 8, $data->ci, 0, 0, 'C');
$pdf->Cell(30, 8, $data->edad . ' AÑOS', 0, 0, 'C');
$pdf->Cell(40, 8, $data->sexo, 0, 0, 'C');
$fecha = date("d-m-Y", strtotime($data->fecha_evaluacion));
$pdf->Cell(70, 8, "COCHABAMBA, " . $fecha, 0, 1, 'C');
//$pdf->Ln(5);

$pdf->SetY($pdf->GetY()-5);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(170, 2, '______________          ___________                        ______                      _________________________', 0, 1, 'C');
$pdf->Cell(40, 5, 'CI', 0, 0, 'C');
$pdf->Cell(30, 5, 'EDAD', 0, 0, 'C');
$pdf->Cell(40, 5, 'SEXO', 0, 0, 'C');
$pdf->Cell(70, 5, 'FECHA DEL EXAMEN', 0, 1, 'C');

// USA LENTES
$pdf->SetY($pdf->GetY()+3);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(40, 5, 'USA LENTES:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$usa_lentes = ($data->usa_lentes == 1) ? 'SI [X]  NO [ ]' : 'SI [ ]  NO [X]';
$pdf->Cell(50, 5, $usa_lentes, 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(50, 5, 'ULTIMO CONTROL VISUAL:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, $data->ultimo_control_visual, 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(40, 5, 'DIAGNOSTICO:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 8, $data->diagnostico, 0, 'L');
$pdf->Ln(5);

// Table for Vision Binocular and Ocular Health
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 8, 'VISION BINOCULAR', 1, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(120, 8, $data->vision_binocular, 1, 1, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 8, 'SALUD OCULAR', 1, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(120, 8, $data->salud_ocular, 1, 1, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 16, 'REFRACCION', 1, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$html_refraccion = "OD: " . $data->refraccion_od . "<br>OI: " . $data->refraccion_oi;
$pdf->writeHTMLCell(120, 16, '', '', $html_refraccion, 1, 1, false, true, 'L', true);
$pdf->Ln(5);

// Corrective Refraction Table
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 8, 'CORRECCIÓN REFRACTIVA LEJOS', 0, 1, 'L');

$pdf->Cell(30, 8, '', 1, 0, 'C');
$pdf->Cell(30, 8, 'Esfera', 1, 0, 'C');
$pdf->Cell(30, 8, 'Cilindro', 1, 0, 'C');
$pdf->Cell(30, 8, 'Eje', 1, 0, 'C');
$pdf->Cell(30, 8, 'A.V.', 1, 0, 'C');
$pdf->Cell(30, 8, 'DIP', 1, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
// OD
$pdf->Cell(30, 8, 'OD', 1, 0, 'C');
$pdf->Cell(30, 8, $data->esfera_od, 1, 0, 'C');
$pdf->Cell(30, 8, $data->cilindro_od, 1, 0, 'C');
$pdf->Cell(30, 8, $data->eje_od, 1, 0, 'C');
$pdf->Cell(30, 8, $data->av_od, 1, 0, 'C');
$pdf->Cell(30, 16, $data->dip_od, 1, 0, 'C'); // DIP spans 2 rows
$pdf->Ln(8); // Move down 8mm for OI row

// OI
$pdf->Cell(30, 8, 'OI', 1, 0, 'C');
$pdf->Cell(30, 8, $data->esfera_oi, 1, 0, 'C');
$pdf->Cell(30, 8, $data->cilindro_oi, 1, 0, 'C');
$pdf->Cell(30, 8, $data->eje_oi, 1, 0, 'C');
$pdf->Cell(30, 8, $data->av_oi, 1, 1, 'C'); // ln=1 moves cursor down 8mm

// ADD row
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY($pdf->GetX() + 120, $pdf->GetY());
$pdf->Cell(30, 8, 'ADD', 1, 0, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(30, 8, $data->add_field, 1, 1, 'C');
$pdf->Ln(5);

// Footer details
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(40, 5, 'RECOMENDACIONES:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 5, $data->recomendaciones, 0, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 5, 'USO:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, $data->uso, 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 5, 'MATERIAL:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, $data->material, 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(35, 5, 'OBSERVACIONES:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 5, $data->observaciones, 0, 'L');

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 5, 'RESULTADO FINAL DE EVALUACION VISUAL', 0, 1, 'L');
$pdf->Ln(2);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->MultiCell(0, 10, strtoupper($data->resultado_final), 0, 'C');

// Stamp area
//$pdf->SetXY($pdf->GetX()+30, $pdf->GetY()+80); // asegura posición
$pdf->SetY($pdf->GetY()+20);
$pdf->SetFont('helvetica', 'N', 8);
$pdf->Cell(0, 5, '..........................................................', 0, 1, 'C');
$pdf->Cell(0, 5, 'FIRMA Y SELLO MEDICO', 0, 1, 'C');

// QR Code Generation

$fechaFormat = date("d-m-Y", strtotime($data->fecha_evaluacion));
$qrText = "Paciente: {$data->nombre} {$data->ap_paterno} {$data->ap_materno} | ";
$qrText .= "Documento: {$data->ci} | ";
$qrText .= "Fecha: {$fechaFormat} | ";
$qrText .= "CENTRO MEDICO VIRGEN DEL CARMEN | OFTALMOLOGIA";

$style = array(
    'border' => 0,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false,
    'module_width' => 1,
    'module_height' => 1
);

// Position QR in bottom right corner
//$pdf->write2DBarcode($qrText, 'QRCODE,H', 170, 230, 30, 30, $style, 'N');

$pdf->Output('reporte_visual.pdf', 'I');

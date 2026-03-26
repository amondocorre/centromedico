<?php 
class ENT_PDF extends TCPDF {
    public function Header(){}
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
    }
}

$data = json_decode($json);
$pdf = new ENT_PDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
$pdf->SetMargins(20, 10, 20);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();

// Logo and Header
$logoHeader = FCPATH . 'assets/logos/evaluacionotorrinolarongologica.png';
if (file_exists($logoHeader)) {
    $pdf->Image($logoHeader, 20, 10, 176, 0, '', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    $pdf->Ln(20);
} else {
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(0, 10, 'EVALUACIÓN OTORRINOLARINGOLÓGICA', 0, 1, 'C');
    $pdf->Ln(5);
}

// Spacing
$pdf->SetY($pdf->GetY() + 10);

// Nombres y Apellidos
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(60, 8, $data->ap_paterno, 0, 0, 'C');
$pdf->Cell(60, 8, $data->ap_materno, 0, 0, 'C');
$pdf->Cell(60, 8, $data->nombre, 0, 1, 'C');

$pdf->SetY($pdf->GetY() - 3);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(170, 2, '________________________                 ______________________                  __________________', 0, 1, 'C');
$pdf->Cell(60, 5, 'APELLIDO PATERNO', 0, 0, 'C');
$pdf->Cell(60, 5, 'APELLIDO MATERNO', 0, 0, 'C');
$pdf->Cell(60, 5, 'NOMBRES', 0, 1, 'C');

$pdf->SetY($pdf->GetY() + 5);

// CI, EDAD, SEXO, FECHA
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(40, 8, $data->ci, 0, 0, 'C');
$pdf->Cell(30, 8, $data->edad . ' AÑOS', 0, 0, 'C');
$pdf->Cell(40, 8, $data->sexo, 0, 0, 'C');
$fecha_ex = date("d-m-Y", strtotime($data->fecha_evaluacion));
$pdf->Cell(70, 8, "COCHABAMBA, " . $fecha_ex, 0, 1, 'C');

$pdf->SetY($pdf->GetY() - 5);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(170, 2, '______________          ___________                        ______                      _________________________', 0, 1, 'C');
$pdf->Cell(40, 5, 'CI', 0, 0, 'C');
$pdf->Cell(30, 5, 'EDAD', 0, 0, 'C');
$pdf->Cell(40, 5, 'SEXO', 0, 0, 'C');
$pdf->Cell(70, 5, 'FECHA DEL EXAMEN', 0, 1, 'C');

$pdf->Ln(5);

// Lugar y Fecha de Nacimiento
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 5, 'LUGAR Y FECHA DE NACIMIENTO:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$fecha_nac = date("d-m-Y", strtotime($data->fecha_nacimiento));
$pdf->Cell(0, 5, $data->lugar_nacimiento . ', ' . $fecha_nac, 0, 1, 'L');

// Antecedentes (Audífonos)
$pdf->Ln(2);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(40, 5, 'USA AUDIFONOS:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$usa_audifono = ($data->usa_audifonos == 1) ? 'SI [X]  NO [ ]' : 'SI [ ]  NO [X]';
$pdf->Cell(50, 5, $usa_audifono, 0, 1, 'L');

$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, '2.- EXAMEN CLINICO:', 0, 1, 'L');

// Examen Clínico fields
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 5, 'Conductos Auditivos Externos:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 5, $data->conductos_auditivos_externos, 0, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 5, 'Otoscopía:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 5, $data->otoscopia, 0, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 5, 'Maniobra de Valsalva:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 5, $data->maniobra_valsalva, 0, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 5, 'Maniobra de Toynbee:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 5, $data->maniobra_toynbee, 0, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 5, 'Prueba de Rinne:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 5, $data->prueba_rinne, 0, 'L');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 5, 'Prueba de Weber:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 5, $data->prueba_weber, 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, '3.- DIAGNOSTICO DEL EXAMEN AUDITIVO:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 5, $data->diagnostico_examen_auditivo, 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, '4.- RESULTADO FINAL DE LA EVALUACION OTORRINOLARINGOLÓGICA:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 8, strtoupper($data->resultado_final), 0, 'L');

// Stamp area
$pdf->SetY($pdf->GetY() + 15);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(0, 5, '..........................................................', 0, 1, 'C');
$pdf->Cell(0, 5, 'FIRMA DEL OTORRINOLARINGOLOGO', 0, 1, 'C');

// QR Code Generation
$fechaFormat = date("d-m-Y", strtotime($data->fecha_evaluacion));
$qrText = "Paciente: {$data->nombre} {$data->ap_paterno} {$data->ap_materno} | ";
$qrText .= "Documento: {$data->ci} | ";
$qrText .= "Fecha: {$fechaFormat} | ";
$qrText .= "CENTRO MEDICO VIRGEN DEL CARMEN | OTORRINOLARINGOLOGIA";

$style = array(
    'border' => 0,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false,
    'module_width' => 1,
    'module_height' => 1
);

//$pdf->write2DBarcode($qrText, 'QRCODE,H', 170, 230, 30, 30, $style, 'N');

$pdf->Output('reporte_otorrino.pdf', 'I');

<?php
// Adiciona uma página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo do PDF com o conteúdo e identificadores dinâmicos
$html = '


// Adiciona o conteúdo na página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da segunda página do PDF
$html = '


// Adiciona o conteúdo na segunda página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da terceira página do PDF
$html = '


// Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $signature_file_patient . '" alt="Assinatura" height="70">
        </td>
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="70">
        </td>
    </tr>
</table>';


// Adiciona o conteúdo na terceira página
$pdf->writeHTML($html, true, false, true, false, '');

// Verifica se o consentimento de imagem foi removido
$remover_consentimento = isset($_POST["remover_consentimento_imagem"]) && $_POST["remover_consentimento_imagem"] == '1';

// Adiciona uma nova página para consentimento de imagem SE o consentimento NÃO foi removido
if (!$remover_consentimento) {
    // Adiciona uma nova página
    $pdf->AddPage();

    // Define a fonte
    $pdf->SetFont('helvetica', '', 8);

    // Conteúdo da quarta página (Consentimento de Imagem)
    $html = '
    

    // Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $signature_file_patient . '" alt="Assinatura" height="50">
        </td>';
        
if ($medico_signature_file) {
    $html .= '
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="50">
        </td>';
}

$html .= '
    </tr>
</table>';


    // Adiciona o conteúdo na quarta página
    $pdf->writeHTML($html, true, false, true, false, '');
}


?>

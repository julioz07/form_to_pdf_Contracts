<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclui o arquivo de configuração
require_once "config.php";

// Inclui a biblioteca TCPDF
require_once('tcpdf/tcpdf.php');

// Verifica se os dados do formulário estão na sessão
if (!isset($_SESSION["form_data"])) {
    die("Erro: Dados do formulário não encontrados na sessão.");
}
$form_data = $_SESSION["form_data"];

// Verifica se a assinatura está presente
if (!isset($_POST["signature"])) {
    die("Erro: Assinatura não encontrada.");
}
$signature = $_POST["signature"];

// Salva a imagem da assinatura do paciente
list($type, $data) = explode(';', $signature);
list(, $data) = explode(',', $data);
$data = base64_decode($data);
$signature_file_patient = 'signatures/signature_' . time() . '.png';
if (!file_put_contents($signature_file_patient, $data)) {
    die("Erro ao salvar a assinatura do paciente.");
}

if (isset($_POST['remover_consentimento_imagem']) && $_POST['remover_consentimento_imagem'] == '1') {
    // Código para remover o consentimento de imagem
    $remover_consentimento = true;
} else {
    $remover_consentimento = false;
}



// Define valores padrão para campos opcionais
$procedimento = isset($form_data["procedimento"]) ? $form_data["procedimento"] : "";
$zona_tratada = isset($form_data["zona_tratada"]) ? implode(", ", $form_data["zona_tratada"]) : "";
$numero_sessoes = isset($form_data["numero_sessoes"]) ? $form_data["numero_sessoes"] : "";
$duracao_prevista = isset($form_data["duracao_prevista"]) ? $form_data["duracao_prevista"] : "";
$inclui = isset($form_data["inclui"]) ? $form_data["inclui"] : "";
$valor_previsto = isset($form_data["valor_previsto"]) ? $form_data["valor_previsto"] : "";

// Salva os dados do formulário no banco de dados
$sql = "INSERT INTO formulario (nome_completo, procedimento, medico, data, numero_documento, validade_documento, zona_tratada, numero_sessoes, duracao_prevista, inclui, valor_previsto, form_type, signature, consentimento_imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "sssssssssssssi", 
        $form_data["nome_completo"], 
        $procedimento, 
        $form_data["medico"], 
        $form_data["data"], 
        $form_data["numero_documento"], 
        $form_data["validade_documento"], 
        $zona_tratada, 
        $numero_sessoes, 
        $duracao_prevista, 
        $inclui, 
        $valor_previsto, 
        $form_data["form_type"], 
        $signature_file_patient,
        $form_data["consentimento_imagem"]
    );
    if (!mysqli_stmt_execute($stmt)) {
        die("Erro ao salvar os dados no banco de dados: " . mysqli_stmt_error($stmt));
    }
    $form_id = mysqli_insert_id($link);
    mysqli_stmt_close($stmt);
} else {
    die("Erro ao preparar a declaração SQL: " . mysqli_error($link));
}

// Define o nome do arquivo PDF
$pdf_filename = $form_data["nome_completo"] . '_' . $form_data["data"] . '.pdf';

class MYPDF extends TCPDF {
    // Cabeçalho da página
    public function Header() {
        // Definir o logo
        $image_file = 'assets/img/logo.png'; // Caminho do logo
        $this->Image($image_file, 10, 10, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        // Definir menor espaçamento superior
        $this->SetTopMargin(20);
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Company');
$pdf->SetTitle('Documento');
$pdf->SetSubject('Documento');
$pdf->SetKeywords('TCPDF, PDF, documento');

// Define os dados do cabeçalho padrão
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Define as fontes do cabeçalho e do rodapé
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Define a fonte monoespaçada padrão
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Define as margens
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Define quebras automáticas de página
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Define o fator de escala de imagem
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Mapeia os nomes dos médicos para os arquivos de assinatura
$medico_signature_map = [
    'Dr. Vinicio' => 'signatures/Dr_Vinicio.png',
    'Dr. Ricardo' => 'signatures/Dr_Ricardo.png',
    'Dra. Iara' => 'signatures/Dra_Iara.png',
];

// Obtém a assinatura do médico selecionado
$medico_signature_file = isset($medico_signature_map[$form_data['medico']]) ? $medico_signature_map[$form_data['medico']] : '';

// Adiciona o conteúdo baseado no form_type
switch ($form_data["form_type"]) {
    
    case 'planos-de-tratamentos':
        // Adiciona uma página
        $pdf->AddPage();
        
        // Define a fonte
        $pdf->SetFont('helvetica', '', 9);
        
        // Conteúdo dinâmico em HTML
        $html = '
            <span style="text-align:right;font-size:13px;line-height: normal;">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, 
                when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            </span> ';
        
        // Escreve o conteúdo no PDF
        $pdf->writeHTML($html, true, false, true, false, '');
        break;

    case 'contrato-de-servicos':
        // Adiciona uma página
        $pdf->AddPage();
        
        // Define a fonte
        $pdf->SetFont('helvetica', '', 9);
        
        // Conteúdo dinâmico em HTML
        $html = '
            <span style="text-align:right;font-size:13px;line-height: normal;">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, 
                when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            </span> ';
        
        // Escreve o conteúdo no PDF
        $pdf->writeHTML($html, true, false, true, false, '');
        break;

    case 'consentimento':
        // Adiciona uma página
        $pdf->AddPage();
        
        // Define a fonte
        $pdf->SetFont('helvetica', '', 9);
        
        // Conteúdo dinâmico em HTML
        $html = '
            <span style="text-align:right;font-size:13px;line-height: normal;">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, 
                when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            </span> ';
        
        // Escreve o conteúdo no PDF
        $pdf->writeHTML($html, true, false, true, false, '');
        break;
        
    default:
    // Caso padrão (fallback)
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    $html = '
    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, 
                when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>';
    $pdf->writeHTML($html, true, false, true, false, '');
                break;

}
        
        // Gera o PDF e força o download
        $pdf->Output($pdf_filename, 'D');
        
        // Limpa os dados do formulário da sessão
        unset($_SESSION["form_data"]);
        
        // Fecha a conexão
        mysqli_close($link);
        
        // Redireciona para a página inicial
        header("location: welcome.php");
        exit;
        ?>

<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclui o arquivo de configuração
require_once "config.php";

// Obtém o ID do formulário da URL
$form_id = $_GET["id"];

// Busca os dados do formulário
$sql = "SELECT * FROM formulario WHERE id = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $form_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $form_data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Inclui a biblioteca TCPDF
require_once('tcpdf/tcpdf.php');

// Função para gerar o PDF e visualizar
function gerarPDF($form_data) {
    // Salva a imagem da assinatura
    $signature_file = $form_data['signature'];

    // Define o nome do arquivo PDF
    $pdf_filename = $form_data["nome_completo"] . '_' . $form_data["data"] . '.pdf';

    // Gera o PDF
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

    // Mapeia os nomes dos médicos para os arquivos de assinatura
$medico_signature_map = [
    'Dr. 1' => 'signatures/Dr_1.png',
    'Dr. 2' => 'signatures/Dr_2.png',
    'Dra. 3' => 'signatures/Dra_3.png',
];

// Obtém a assinatura do médico selecionado
$medico_signature_file = isset($form_data['medico']) && isset($medico_signature_map[$form_data['medico']])
    ? $medico_signature_map[$form_data['medico']]
    : '';



    // Adiciona o conteúdo baseado no form_type
switch ($form_data["form_type"]) {
    case 'planos-de-tratamentos':
        // Conteudo
                break;

    default:
    // Caso padrão (fallback)
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    $html = '
    <h4>Documento de <b>' . htmlspecialchars($form_data["nome_completo"]) . '</b></h4>
    <p>Este é um documento genérico para o tipo de formulário não identificado: <b>' . htmlspecialchars($form_data["form_type"]) . '</b>.</p>';
    $pdf->writeHTML($html, true, false, true, false, '');
                break;
    }

    $pdf->writeHTML($html, true, false, true, false, '');

    // Gera o PDF
    $pdf->Output($pdf_filename, 'D');
}

// Gera o PDF se solicitado
if (isset($_GET['generate_pdf'])) {
    gerarPDF($form_data);
    exit;
}

// Fecha a conexão
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Formulário</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <style>
        body {
            background-color: #f1dbb1;;
        }
        .logo {
            width: 150px;
            margin-bottom: 20px;
        }
        .card-body {
            text-align: center;
        }
        .btn-custom {
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header">
                        <img src="assets/img/logo.png" alt="Company Logo" class="logo">
                        <h3 class="text-center font-weight-light my-4">Detalhes do Formulário</h3>
                    </div>
                    <div class="card-body">
                        <h4>Documento de <b><?php echo htmlspecialchars($form_data["nome_completo"]); ?></b></h4>
                        <p>
                            No dia <strong><?php echo htmlspecialchars($form_data["data"]); ?>, <?php echo htmlspecialchars($form_data["nome_completo"]); ?></strong>, Assinou o documento: <strong><?php echo htmlspecialchars($form_data["form_type"]); ?></strong>.<br>
                            Realizado pelo médico <?php echo htmlspecialchars($form_data["medico"]); ?>. O número do documento de identificação é <?php echo htmlspecialchars($form_data["numero_documento"]); ?>, com validade até <?php echo htmlspecialchars($form_data["validade_documento"]); ?>.
                        </p>
                        <p>Assinatura:</p>
                        <img src="<?php echo htmlspecialchars($form_data["signature"]); ?>" alt="Assinatura" height="100"><br><br>
                        
                        <a href="view_forms.php" class="btn btn-primary">Voltar para a Lista</a><br><br>
                        <a href="view_form.php?id=<?php echo $form_id; ?>&generate_pdf=1" class="btn btn-secondary">Gerar PDF novamente</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/jquery/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>

<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Captura os dados do formulário e armazena na sessão
$_SESSION["form_data"] = $_POST;

// Adicione essa linha para capturar o consentimento de imagem
$_SESSION["form_data"]["consentimento_imagem"] = isset($_POST["consentimento_imagem"]) ? 1 : 0;


// Redireciona para a página de template específica
$form_type = $_POST['form_type'];

switch ($form_type) {
    case 'planos-de-tratamentos':
        header("location: template_planos-de-tratamentos.php");
        break;
    case 'cedencia-imagem':
        header("location: template_cedencia-imagem.php");
        break;
    case 'CI-Acido-Hiaulorico':
            header("location: template_CI-Acido-Hiaulorico.php");
            break;
    case 'CI-bodyglam':
            header("location: template_CI-bodyglam.php");
            break;
    case 'CI-beauty-flash':
         header("location: template_CI-Beauty-Flash.php");
            break;

    case 'CI-hialoestrutura':
         header("location: template_CI-Hialoestrutura.php");
            break;
     case 'CI-lipolise':
         header("location: template_CI-Lipolise.php");
                   break;
    case 'CI-mesoestimulacao':
        header("location: template_CI-Mesoestimulacao.php");
                       break;
    case 'CI-peeling':
         header("location: template_CI-Peeling.php");
                           break;
     case 'CI-Superfomer':
        header("location: template_CI-Superfomer.php");
                               break;
    // Adicione outros cases para outros tipos de formulário
    default:
        header("location: welcome.php");
        break;
}
exit;
?>

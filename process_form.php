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
        header("location: template_1.php");
        break;
    
    // Adicione outros cases para outros tipos de formulário
    default:
        header("location: welcome.php");
        break;
}
exit;
?>

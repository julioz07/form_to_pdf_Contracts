<?php
session_start();

// Simular os dados do formulário
$form_data = [
    "nome_completo" => "João Silva",
    "procedimento" => "Preenchimento Facial",
    "medico" => "Dr. Ricardo",
    "data" => date('Y-m-d'),
    "numero_documento" => "123456789",
    // Outros campos podem ser adicionados aqui
];

// Carregar o template a ser visualizado
$template = isset($_GET['template']) ? $_GET['template'] : null;
$template_path = __DIR__ . '/templates_C/' . $template;

// Certifique-se de que o template existe
if (!$template || !file_exists($template_path)) {
    die("Template não encontrado.");
}

// Captura o conteúdo do template
ob_start();
include $template_path; // Carrega o template PHP
$conteudo_template = ob_get_clean();

// Exibe o template com os dados fictícios
echo $conteudo_template;
?>

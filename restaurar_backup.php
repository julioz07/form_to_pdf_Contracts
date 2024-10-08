<?php
session_start();

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("location: login_admin.php");
    exit;
}

$template = isset($_GET['template']) ? $_GET['template'] : null;
$backup = isset($_GET['backup']) ? $_GET['backup'] : null;

if (!$template || !$backup) {
    header("location: gerir_templates.php");
    exit;
}

$template_path = __DIR__ . '/templates_C/' . $template;
$backup_path = __DIR__ . '/backup_templates/' . $template . '/' . $backup;

// Verifica se o backup existe
if (file_exists($backup_path)) {
    // Restaura o backup, copiando-o de volta para o template atual
    copy($backup_path, $template_path);
    $_SESSION['mensagem'] = "Backup restaurado com sucesso!";
} else {
    $_SESSION['mensagem'] = "Erro ao restaurar backup.";
}

header("location: editar_template.php?template=" . urlencode($template));
exit;
?>

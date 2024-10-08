<?php
session_start();

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("location: login_admin.php");
    exit;
}

// Verifica se o template foi selecionado
$template = isset($_GET['template']) ? $_GET['template'] : null;
if (!$template) {
    header("location: gerir_templates.php");
    exit;
}

$template_path = __DIR__ . '/templates_C/' . $template;
$backup_dir = __DIR__ . '/backup_templates/' . $template;

// Função para armazenar backups (mantém apenas 2 versões)
function armazenar_backup($template_path, $backup_dir) {
    if (!is_dir($backup_dir)) {
        mkdir($backup_dir, 0777, true);
    }

    // Verifica se já existem backups
    $backup_files = glob($backup_dir . '/*');
    usort($backup_files, function($a, $b) {
        return filemtime($b) - filemtime($a); // Ordena por data (mais recente primeiro)
    });

    // Se já houver 2 backups, remover o mais antigo
    if (count($backup_files) >= 2) {
        unlink(end($backup_files)); // Remove o backup mais antigo
    }

    // Cria um novo backup (com timestamp para distinguir)
    $backup_name = $backup_dir . '/backup_' . date('Y-m-d_H-i-s') . '.php';
    copy($template_path, $backup_name);
}

// Processa a edição do template
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conteudo_template = $_POST["conteudo_template"];

    // Antes de gravar o novo conteúdo, faça o backup do conteúdo atual
    if (file_exists($template_path)) {
        armazenar_backup($template_path, $backup_dir);
    }

    // Salva o conteúdo novo
    file_put_contents($template_path, $conteudo_template);
    $mensagem = "Template atualizado com sucesso!";
}

// Lê o conteúdo atual do template
$conteudo_atual = file_get_contents($template_path);

// Lê os backups existentes
$backups = glob($backup_dir . '/*');
usort($backups, function($a, $b) {
    return filemtime($b) - filemtime($a); // Ordena por data (mais recente primeiro)
});
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Template - <?php echo htmlspecialchars($template); ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/bpazoq6wplsg6xwecgjdurp5gyffmtnc0smtivyhhxil03eb/tinymce/5/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            height: 500,
            plugins: 'code',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code | placeholders'
        });
    </script>
</head>
<body>
    <div class="container mt-4">
        <h2>Editar Template - <?php echo htmlspecialchars($template); ?></h2>

        <!-- Mostra a mensagem de sucesso -->
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-success">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <!-- Formulário para editar o conteúdo do template -->
        <form action="editar_template.php?template=<?php echo urlencode($template); ?>" method="post">
            <div class="form-group">
                <textarea name="conteudo_template" class="form-control"><?php echo htmlspecialchars($conteudo_atual); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Template</button>
        </form>

        <!-- Botão de pré-visualizar template -->
        <a href="preview_template.php?template=<?php echo urlencode($template); ?>" target="_blank" class="btn btn-info mt-3">Pré-visualizar Template</a>

        <a href="gerir_templates.php" class="btn btn-secondary mt-3">Voltar à Lista de Templates</a>

        <!-- Exibir versões anteriores (backups) -->
        <h3 class="mt-5">Versões Anteriores</h3>
        <?php if (empty($backups)): ?>
            <p>Nenhum backup disponível.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($backups as $backup): ?>
                    <li class="list-group-item">
                        Backup criado em: <?php echo date('d/m/Y H:i:s', filemtime($backup)); ?>
                        <a href="restaurar_backup.php?template=<?php echo urlencode($template); ?>&backup=<?php echo urlencode(basename($backup)); ?>" class="btn btn-warning btn-sm float-right">Restaurar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>

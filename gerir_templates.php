<?php
session_start();

// Verifica se o usuário já fez login como admin
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("location: login_admin.php");
    exit;
}

// Define o diretório onde estão os templates
$templates_dir = __DIR__ . '/templates_C/';
$templates = array_diff(scandir($templates_dir), array('..', '.')); // Ignora diretórios "." e ".."

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Templates de Contrato</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Gerenciar Templates de Contrato</h2>
        <a href="logout_admin.php" class="btn btn-danger">Logout</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome do Template</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($templates as $template): ?>
                    <tr>
                        <td><?php echo $template; ?></td>
                        <td>
                            <a href="editar_template.php?template=<?php echo urlencode($template); ?>" class="btn btn-primary btn-sm">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Voltar ao Painel de Administração</a>
    </div>
</body>
</html>

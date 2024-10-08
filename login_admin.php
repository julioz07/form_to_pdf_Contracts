<?php
session_start();

// Define a senha (você pode alterar essa senha)
$admin_password = "admin123";

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_password = $_POST["password"];
    
    // Verifica se a senha está correta
    if ($input_password === $admin_password) {
        // Se a senha estiver correta, armazena a informação na sessão
        $_SESSION["is_admin"] = true;
        header("location: gerir_templates.php"); // Redireciona para a página de templates
        exit;
    } else {
        $error_message = "Senha incorreta!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Área Administrativa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Área Administrativa - Insira a senha</h2>
        
        <!-- Exibe mensagem de erro se a senha estiver incorreta -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Formulário para entrada da senha -->
        <form action="login_admin.php" method="post">
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>
</body>
</html>

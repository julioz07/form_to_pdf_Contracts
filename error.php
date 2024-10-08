<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Erro</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <style>
        body {
            background-color: #f1dbb1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .error-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .error-title {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
        }
        .error-message {
            font-size: 18px;
            margin-top: 10px;
            color: #333;
        }
        .btn-home {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-home:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-title">Erro</h1>
        <p class="error-message">Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.</p>
        <a href="welcome.php" class="btn-home">Voltar para a Página Inicial</a>
    </div>
</body>
</html>

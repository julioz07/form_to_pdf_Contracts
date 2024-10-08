<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <style>
        body {
            background-color: #f1dbb1; /* Light background color */
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

        .btn-img {
            Background-color: #f0be8b;
            border-color: #f0be8b;
            color: #fff;
        }

        .btn-img:hover {
            Background-color: #c08d72;
            border-color: #c08d72;
        }

        .btn-warning:hover {
            Background-color: #dfcaa3;
            border-color: #dfcaa3; 
        }

        .btn-warning {
            Background-color: #607675;
            border-color: #607675; 
            color: #fff;
        }
        
        .btn-logout {
            margin: 15px 5px 5px 5px;
            
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
                        <h3 class="text-center font-weight-light my-4">Documentação AG</h3>
                    </div>
                    <div class="card-body">
                        <h4>Olá, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Escolhe que tipo de documento deve ser criado.</h4>
                        <p>Utiliza os butões a baixo para excolher uma opção:</p>
                        <a href="cedencia-imagem.php" class="btn btn-img btn-custom">Cedência de Imagem</a>
                        <a href="planos-de-tratamentos.php" class="btn btn-secondary btn-custom">Plano de tratamentos base</a>
                        <a href="CI-bodyglam.php" class="btn btn-warning btn-custom">CI-Bodyglam&#174;</a>
                        <a href="CI-Hialoestrutura.php" class="btn btn-warning btn-custom">CI-Hialoestrutura&#174;</a>
                        <a href="CI-Mesoestimulacao.php" class="btn btn-warning btn-custom">CI-Mesoestimulação&#174;</a>
                        <a href="CI-Beauty-Flash.php" class="btn btn-warning btn-custom">CI-Beauty Flash&#174;</a>
                        <a href="CI-Superfomer.php" class="btn btn-warning btn-custom">CI-Superfomer&#174;</a>
                        <a href="CI-Lipolise.php" class="btn btn-warning btn-custom">CI-Lipólise</a>
                        <a href="CI-Acido-Hiaulorico.php" class="btn btn-warning btn-custom">CI-Ácido Hiaulórico</a>
                        <a href="CI-Peeling.php" class="btn btn-warning btn-custom">CI-Peeling</a>
                        
                        <p>
                       <a href="view_forms.php" class="btn btn-secondary btn-custom"style="margin-top:25px;">Ver Registos</a> <br>
                        <a href="logout.php" class="btn btn-danger btn-logout">Logout - Sair</a>
                            
                            
                        </p>
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

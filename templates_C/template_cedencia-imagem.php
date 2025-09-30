<?php
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Obtém os dados do formulário da sessão
$form_data = $_SESSION["form_data"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Cedência de Imagem</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <style>
        body {
            background-color: #f1dbb1;
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
                        <h3 class="text-center font-weight-light my-4">Cedência de Imagem</h3>
                    </div>
                    <div class="card-body">
                        <!-- Footer CI (Concentimento de imagem + btn assinar) -->

 <!-- Consentimento de imagem (visível por padrão) -->
 <div class="form-group">
                            <label for="consentimento_imagem">Consentimento de Imagem</label>
                            <p style="margin:6.0pt 0cm 0cm;text-align:justify;"><span style="font-size:10.0pt;"><?php echo htmlspecialchars($form_data["nome_completo"]); ?>, expressamente <strong>DECLARA</strong>, para os devidos efeitos:</span></p>
<p style="line-height:12.0pt;margin:0cm 0cm 0cm 21.3pt;text-align:justify;text-indent:-21.3pt;"><span style="font-size:12pt;"><span style="font-size:10.0pt;"></span></span></p>
<ol style="padding-left:28.4px;">
    //Conteudo do seu concentimento
<p style="margin:0cm;text-align:justify;">&nbsp;</p>

<!-- Footer (dados do cliente) -->
<span style="text-align:left"> 
<p style="margin:0cm;">&nbsp;</p>
<p><span style="font-size:14px;"><strong>Data</strong>:&nbsp;<?php echo htmlspecialchars($form_data["data"]); ?></span></p>
<p><span style="font-size:14px;"><strong>Nome da(o) paciente</strong>:&nbsp;<u><?php echo htmlspecialchars($form_data["nome_completo"]); ?></u></span></p>
<p><span style="font-size:14px;"><strong>B.I/C.C./Passaporte Nº</strong>: &nbsp;<u><?php echo htmlspecialchars($form_data["numero_documento"]); ?></u> &nbsp; &nbsp;&nbsp;<strong>Validade</strong>: &nbsp;<u><?php echo htmlspecialchars($form_data["validade_documento"]); ?></u></span></p>
<p><span style="font-size:14px;"><strong>Nome do Médico</strong>:&nbsp; <?php echo htmlspecialchars($form_data["medico"]); ?></span></p>
<p>&nbsp;</p></span>
<!-- Fim Footer (dados do cliente) -->
<p style="margin:0cm;text-align:justify;">&nbsp;</p>
                            <input type="hidden" name="consentimento_imagem" value="1">
                        </div>
                        <form action="signature.php" method="post">
                            <input type="hidden" name="form_type" value="<?php echo htmlspecialchars($form_data["form_type"]); ?>">
                            <button type="submit" class="btn btn-success">Assinar</button>
                        </form>
                        <a href="welcome.php" class="btn btn-primary">Voltar para a Página Inicial</a>
<!-- Fim - Footer CI (Concentimento de imagem + btn assinar) -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

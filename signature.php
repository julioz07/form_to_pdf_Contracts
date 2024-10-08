<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Verifica se os dados do formulário estão na sessão
if (!isset($_SESSION["form_data"])) {
    die("Erro: Dados do formulário não encontrados na sessão.");
}

// Captura o tipo de formulário
$form_type = isset($_POST['form_type']) ? $_POST['form_type'] : '';

// Redireciona para a página de erro se o tipo de formulário não estiver definido
if (empty($form_type)) {
    header("location: error.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assinatura</title>
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
    .signature-pad {
        border: 1px solid #000;
        margin-top: 10px;
        margin-bottom: 10px;
        width: 100%; /* Garante que o canvas ocupe 100% da largura disponível */
        height: 200px; /* Ajusta conforme necessário */
    }
    .signature-pad canvas {
        width: 100%; /* Garante que o canvas ocupe 100% da largura da div */
        height: 100%; /* Garante que o canvas ocupe 100% da altura da div */
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
                        <h3 class="text-center font-weight-light my-4">Assinatura</h3>
                    </div>
                    <div class="card-body">
                        <form action="save_form.php" method="post" id="signature-form">
                            <div class="form-group">
                                <label for="signature">Assinatura do Paciente</label>
                                <div id="signature-pad" class="signature-pad">
                                    <canvas></canvas>
                                </div>
                                <button type="button" id="clear" class="btn btn-secondary">Limpar</button>
                                <textarea name="signature" id="signature" style="display: none;"></textarea>

                                <!-- Inclui o valor do consentimento de imagem como campo oculto -->
                                <input type="hidden" name="remover_consentimento_imagem" value="<?php echo isset($_POST['remover_consentimento_imagem']) ? htmlspecialchars($_POST['remover_consentimento_imagem']) : ''; ?>">

                                <!-- Campo oculto para o tipo de formulário -->
                                <input type="hidden" name="form_type" value="<?php echo htmlspecialchars($form_type); ?>">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" id="submit-button">Concluir</button>
                            </div>
                        </form>

                        <a href="welcome.php" class="btn btn-primary">Voltar para a Página Inicial</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/jquery/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script>
        var canvas = document.querySelector("canvas");
            var signaturePad = new SignaturePad(canvas);
            var clearButton = document.getElementById('clear');
            var form = document.getElementById('signature-form');
            var submitButton = document.getElementById('submit-button');
            var signatureTextarea = document.getElementById('signature');

            clearButton.addEventListener('click', function (event) {
                signaturePad.clear();
            });

            form.addEventListener('submit', function (event) {
                if (signaturePad.isEmpty()) {
                    alert("Por favor, forneça uma assinatura primeiro.");
                    event.preventDefault();
                } else {
                    var dataURL = signaturePad.toDataURL();
                    signatureTextarea.value = dataURL;

                    // Oculta o botão "Concluir" após o clique
                    submitButton.style.display = 'none';
                }
            });

            // Função para redimensionar o canvas corretamente
            function resizeCanvas() {
                var canvasContainer = document.querySelector('.signature-pad');
                var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvasContainer.offsetWidth * ratio;
                canvas.height = canvasContainer.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePad.clear(); // limpa a assinatura após redimensionar
            }

            window.addEventListener("resize", resizeCanvas);
            resizeCanvas(); // Garante que o canvas é redimensionado quando a página é carregada

    </script>
</body>
</html>


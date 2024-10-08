<?php
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cedência de Imagem</title>
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
        .hidden-field {
            display: none;
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
                        <form action="process_form.php" method="post">
                            <div class="form-group">
                                <label for="nome_completo">Nome Completo</label>
                                <input type="text" name="nome_completo" class="form-control" required>
                            </div>
                            <div class="form-group hidden-field">
                                <label for="procedimento">Procedimento</label>
                                <select name="procedimento" class="form-control">
                                    <option value="Procedimento 1">Procedimento 1</option>
                                    <option value="Procedimento 2">Procedimento 2</option>
                                    <option value="Procedimento 3">Procedimento 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="medico">Médico</label>
                                <select name="medico" class="form-control" required>
                                    <option value="Dr. Vitor Figueiredo">Dr. Vitor Figueiredo</option>
                                    <option value="Dr. Ricardo">Dr. Ricardo</option>
                                    <option value="Dra. Lara Graça">Dra. Lara Graça</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="data">Data</label>
                                <input type="date" name="data" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="numero_documento">Nº do Documento de Identificação</label>
                                <input type="text" name="numero_documento" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="validade_documento">Validade do Documento de Identificação</label>
                                <input type="date" name="validade_documento" class="form-control" required>
                            </div>
                            <div class="form-group hidden-field">
                                <label for="zona_tratada">Zona do Corpo a Ser Tratada</label>
                                <div>
                                    <input type="radio" name="zona_tratada" value="Zona 1"> Zona 1
                                    <input type="radio" name="zona_tratada" value="Zona 2"> Zona 2
                                    <input type="radio" name="zona_tratada" value="Zona 3"> Zona 3
                                </div>
                            </div>
                            <div class="form-group hidden-field">
                                <label for="numero_sessoes">Nº de Sessões</label>
                                <input type="number" name="numero_sessoes" class="form-control">
                            </div>
                            <div class="form-group hidden-field">
                                <label for="duracao_prevista">Duração Prevista</label>
                                <input type="text" name="duracao_prevista" class="form-control">
                            </div>
                            <div class="form-group hidden-field">
                                <label for="inclui">Inclui</label>
                                <textarea name="inclui" class="form-control"></textarea>
                            </div>
                            <div class="form-group hidden-field">
                                <label for="valor_previsto">Valor Previsto</label>
                                <input type="number" step="0.01" name="valor_previsto" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Próximo</button>
                            </div>
                            <input type="hidden" name="form_type" value="cedencia-imagem">
                            <input type="hidden" name="procedimento_type" value="Cedencia de Imagem">
                        </form>
                        <a href="welcome.php" class="btn btn-primary">Voltar para a Página Inicial</a>
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

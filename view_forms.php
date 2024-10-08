<?php
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclui o arquivo de configuração
require_once "config.php";

// Inicializa variáveis de pesquisa e paginação
$search = isset($_POST['search']) ? $_POST['search'] : '';
$filter_form_type = isset($_POST['filter_form_type']) ? $_POST['filter_form_type'] : '';
$limit = 10; // Limite de formulários por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página atual
$offset = ($page - 1) * $limit; // Calcula o offset para a paginação

// Prepara a consulta SQL para obter os registros com paginação
$sql = "SELECT id, nome_completo, data, form_type FROM formulario WHERE nome_completo LIKE ? ";
$params = ["%$search%"];

if ($filter_form_type) {
    $sql .= "AND form_type = ? ";
    $params[] = $filter_form_type;
}

$sql .= "ORDER BY id DESC LIMIT ? OFFSET ?"; // Ordena pelo ID de forma descendente (mais recente primeiro)

$stmt = mysqli_prepare($link, $sql);

if ($filter_form_type) {
    mysqli_stmt_bind_param($stmt, "ssii", $params[0], $params[1], $limit, $offset);
} else {
    mysqli_stmt_bind_param($stmt, "sii", $params[0], $limit, $offset);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Consulta para obter o número total de formulários (sem limites para paginação)
$total_sql = "SELECT COUNT(*) FROM formulario WHERE nome_completo LIKE ?";
if ($filter_form_type) {
    $total_sql .= " AND form_type = ?";
    $total_stmt = mysqli_prepare($link, $total_sql);
    mysqli_stmt_bind_param($total_stmt, "ss", $params[0], $params[1]);
} else {
    $total_stmt = mysqli_prepare($link, $total_sql);
    mysqli_stmt_bind_param($total_stmt, "s", $params[0]);
}
mysqli_stmt_execute($total_stmt);
$total_result = mysqli_stmt_get_result($total_stmt);
$total_rows = mysqli_fetch_array($total_result)[0]; // Número total de formulários
$total_pages = ceil($total_rows / $limit); // Calcula o número total de páginas
mysqli_stmt_close($total_stmt);

// Função de exportação
if (isset($_POST['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="formularios.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Nome Completo', 'Data', 'Form Type'));
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Fecha a conexão
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ver Formulários</title>
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
            margin: 15px;
        }
        .btn-secondary {
            margin-top: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; 
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .pagination {
            display: inline-block;
            margin-top: 20px;
        }
        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header">
                        <img src="assets/img/logo.png" alt="Company Logo" class="logo">
                        <h3 class="text-center font-weight-light my-4">Formulários Submetidos</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Pesquisar por nome">
                            <select name="filter_form_type">
                                <option value="">Todos os Tipos</option>
                                <option value="cedencia-imagem" <?php if ($filter_form_type == "cedencia-imagem") echo 'selected'; ?>>Cedência de Imagem</option>
                                <option value="planos-de-tratamentos" <?php if ($filter_form_type == "planos-de-tratamentos") echo 'selected'; ?>>Plano de Tratamentos</option>
                                <!-- Adicione mais opções conforme necessário -->
                            </select>
                            <button type="submit" class="btn btn-primary">Pesquisar</button>
                            <button type="submit" name="export" class="btn btn-secondary">Exportar</button>
                        </form>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Nome Completo</th>
                                <th>Data</th>
                                <th>Tipo de Formulário</th>
                            </tr>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row["id"] . "</td>";
                                    echo "<td><a href='view_form.php?id=" . $row["id"] . "'>" . $row["nome_completo"] . "</a></td>";
                                    echo "<td>" . $row["data"] . "</td>";
                                    echo "<td>" . $row["form_type"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>Nenhum formulário encontrado</td></tr>";
                            }
                            ?>
                        </table>

                        <!-- Paginação -->
                        <div class="pagination">
                            <?php
                            $max_links = 3; // Número máximo de links de paginação a serem exibidos
                            $start = max(1, $page - 1); // Página inicial do range de links
                            $end = min($total_pages, $start + $max_links - 1); // Página final do range de links

                            // Link para a página anterior
                            if ($page > 1) {
                                echo "<a href='view_forms.php?page=" . ($page - 1) . "'>&laquo; Anterior</a>";
                            }

                            // Links de páginas
                            for ($i = $start; $i <= $end; $i++) {
                                if ($i == $page) {
                                    echo "<a class='active'>$i</a>";
                                } else {
                                    echo "<a href='view_forms.php?page=$i'>$i</a>";
                                }
                            }

                            // Link para a página seguinte
                            if ($page < $total_pages) {
                                echo "<a href='view_forms.php?page=" . ($page + 1) . "'>Seguinte &raquo;</a>";
                            }
                            ?>
                        </div>

                        <a href="welcome.php" class="btn btn-primary btn-custom">Voltar para a Página Inicial</a>
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

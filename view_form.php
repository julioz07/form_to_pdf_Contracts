<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclui o arquivo de configuração
require_once "config.php";

// Obtém o ID do formulário da URL
$form_id = $_GET["id"];

// Busca os dados do formulário
$sql = "SELECT * FROM formulario WHERE id = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $form_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $form_data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Inclui a biblioteca TCPDF
require_once('tcpdf/tcpdf.php');

// Função para gerar o PDF e visualizar
function gerarPDF($form_data) {
    // Salva a imagem da assinatura
    $signature_file = $form_data['signature'];

    // Define o nome do arquivo PDF
    $pdf_filename = $form_data["nome_completo"] . '_' . $form_data["data"] . '.pdf';

    // Gera o PDF
    class MYPDF extends TCPDF {
        // Cabeçalho da página
        public function Header() {
            // Definir o logo
            $image_file = 'assets/img/logo.png'; // Caminho do logo
            $this->Image($image_file, 10, 10, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    
            // Definir menor espaçamento superior
            $this->SetTopMargin(20);
        }
    }

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Company');
    $pdf->SetTitle('Documento');
    $pdf->SetSubject('Documento');
    $pdf->SetKeywords('TCPDF, PDF, documento');

    // Define os dados do cabeçalho padrão
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

    // Define as fontes do cabeçalho e do rodapé
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // Mapeia os nomes dos médicos para os arquivos de assinatura
$medico_signature_map = [
    'Dr. Vitor Figueiredo' => 'signatures/Dr_Vitor_Figueiredo.png',
    'Dr. Ricardo' => 'signatures/Dr_Ricardo.png',
    'Dra. Lara Graça' => 'signatures/Dra_Lara_Graça.png',
];

// Obtém a assinatura do médico selecionado
$medico_signature_file = isset($form_data['medico']) && isset($medico_signature_map[$form_data['medico']])
    ? $medico_signature_map[$form_data['medico']]
    : '';



    // Adiciona o conteúdo baseado no form_type
switch ($form_data["form_type"]) {
    case 'planos-de-tratamentos':
        // Adiciona uma página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo do PDF com o conteúdo e identificadores dinâmicos
$html = '
<span style="text-align:right;font-size:13px;line-height: normal;">
                    <p><strong>PLANO DE TRATAMENTOS</strong></p>
                    <p>' . htmlspecialchars($form_data["nome_completo"]) . '</p>
                    <p>' . htmlspecialchars($form_data["data"]) . '</p>
                    </span>
<p>&nbsp;</p>
<ol style="text-align:left;list-style-type: upper-roman;">
    <li>Apresentação da Equipa Médica e da Unidade Clínica .......... 2</li>
    <li>Previsão de Encargos ........................................................................... 4</li>
    <li>Condições de Pagamento .................................................................. 5</li>
</ol>
<p>&nbsp;</p>
<br>
<span style="text-align:justify;">
<p><em>Este é um documento elaborado para ajudar a informar o paciente sobre o procedimento médico proposto, seus riscos e tratamentos alternativos.</em></p>
<p><em>Não deverá considerar que este documento inclui todos os aspetos relativos ao procedimento médico planeado, e deve considerar que o seu médico pode fornecer informações adicionais ou diferentes, com base em todos os fatos do seu caso particular e do estado do conhecimento médico.</em></p>
<p><em>Não deixe de ler esta informação com cuidado e na íntegra.</em></p>
<p><em>Aceitando o plano de tratamentos proposto, <u>deverá rubricar todas as páginas e assinar a última</u></em></p>
</span>
';

// Adiciona o conteúdo na página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da segunda página do PDF
$html = '
<span style="text-align:right;font-size:13px;line-height: normal;">
                    <p><strong>PLANO DE TRATAMENTOS</strong></p>
                    <p>' . htmlspecialchars($form_data["nome_completo"]) . '</p>
                    <p>' . htmlspecialchars($form_data["data"]) . '</p>
                    </span>';

$html .= '<ol style="list-style-type: upper-roman;">';
$html .= '<li><strong><h1 style="font-size:16px;font-weight:bold;">APRESENTAÇÃO DA EQUIPA MÉDICA E DA UNIDADE CLÍNICA</h1> </li>';
$html .= '</ol>';
$html .= '<br>
<p style="text-align:left;">(Considere que não estarão presentes nos procedimentos propostos todos os profissionais de saúde da Ageless)</p>
<p>&nbsp;</p>
<table style="height: 241px; width: 663px;">
<tbody>
<tr>
<td style="width: 555.117px;"><span style="color: #008080;"><strong>Equipa Médica:</strong></span></td>
<td style="width: 93.8833px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 555.117px;"><strong>Dr. Vitor Figueiredo,</strong><strong>Especialidade</strong>: Diretor clínico e Medicina Estética</td>
<td style="width: 93.8833px;"><strong>OM:</strong>38505</td>
</tr>
<tr>
<td style="width: 555.117px;"><strong>Dr. Ricardo Areal, Especialidade:</strong> Medicina Estética</td>
<td style="width: 93.8833px;"><strong>OM:</strong>52258</td>
</tr>
<tr>
<td style="width: 555.117px;"><strong>Dra. Inês Carpinteiro, Especialidade:</strong> Medicina Estética</td>
<td style="width: 93.8833px;"><strong>OMD:</strong>6801</td>
</tr>
<tr>
<td style="width: 555.117px;"><strong>Dra. Renata Morais, Especialidade:</strong> Medicina Estética</td>
<td style="width: 93.8833px;"><strong>OM:</strong>50298</td>
</tr>
<tr>
<td style="width: 555.117px;"><strong>Dra. Mariana Morais, Especialidade:</strong> Medicina Estética</td>
<td style="width: 93.8833px;"><strong>OM:</strong>40412</td>
</tr>
<tr>
<td style="width: 555.117px;"><strong>Dra. Ana Rita Victor, Especialidade:</strong> Smart Aging</td>
<td style="width: 93.8833px;"><strong>OM:</strong>37593</td>
</tr>
<tr>
<td style="width: 555.117px;"><strong>Dr. João Abel Amaro, Especialidade:</strong> Dermatologia</td>
<td style="width: 93.8833px;"><strong>OM:</strong>13858</td>
</tr>
<tr>
<td style="width: 555.117px;"><strong>Dra. Ana Amaro, Especialidade:</strong> Oftalmologia</td>
<td style="width: 93.8833px;"><strong>OM:</strong>41006</td>
</tr>
<tr>
<td style="width: 555.117px;"><strong>Dra. Mara Ferreira, Especialidade:</strong> Oftalmologia</td>
<td style="width: 93.8833px;"><strong>OM:</strong>40036</td>
</tr>
<tr>
<td style="width: 555.117px;"><strong>Dr. Ricardo Pereira, Especialidade:</strong> Otorrinolaringologia</td>
<td style="width: 93.8833px;"><strong>OM:</strong>38557</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<table style="height: 101px; width: 663px;">
<tbody>
<tr style="height: 16px;">
<td style="width: 266.017px; height: 16px;"><span style="color: #008080;"><strong>Equipa Enfermagem:</strong></span></td>
<td style="width: 256.983px; height: 16px;">&nbsp;</td>
</tr>
<tr style="height: 16px;">
<td style="width: 266.017px; height: 16px;"><strong>Margarita Bushenkova</strong></td>
<td style="width: 256.983px; height: 16px;"><strong>Cédula Profissional:</strong>68909</td>
</tr>
<tr style="height: 16.2667px;">
<td style="width: 266.017px; height: 16.2667px;"><strong>Antoaneta Petkova</strong></td>
<td style="width: 256.983px; height: 16.2667px;"><strong>Cédula Profissional:</strong>N60955</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<table style="height: 241px; width: 663px;">
<tbody>
<tr>
<td style="width: 256.983px;"><span style="color: #008080;"><strong>Diretora Operacional:</strong></span></td>
<td style="width: 356.983px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 256.983px;"><strong>Susana Correia</strong></td>
<td style="width: 356.983px;"><strong>C&eacute;dula Profissional:</strong>C-031761011</td>
</tr>
<tr>
<td style="width: 256.983px;">&nbsp;</td>
<td style="width: 356.983px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 256.983px;"><span style="color: #008080;"><strong>Esteticista e Cosmetologista</strong></span></td>
<td style="width: 356.983px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 256.983px;"><strong>Marta Sousa </strong></td>
<td style="width: 356.983px;"><strong>Certificado Profissional:</strong>117/L-EFLI/2017</td>
</tr>
<tr>
<td style="width: 256.983px;"><strong>Tatiana Pereira</strong></td>
<td style="width: 356.983px;"><strong>Certificado Profissional:</strong>14/2020</td>
</tr>
<tr>
<td style="width: 256.983px;">&nbsp;</td>
<td style="width: 356.983px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 256.983px;"><span style="color: #008080;"><strong>Equipa Fisioterapia</strong></span></td>
<td style="width: 356.983px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 256.983px;"><strong>Joana Passarinho</strong></td>
<td style="width: 356.983px;"><strong> C&eacute;dula Profissional:</strong>C-041279077</td>
</tr>
<tr>
<td style="width: 256.983px;">&nbsp;</td>
<td style="width: 356.983px;">&nbsp;</td>
</tr>
</tbody>
</table>
';

// Adiciona o conteúdo na segunda página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da terceira página do PDF
$html = '
<span style="text-align:right;font-size:13px;line-height: normal;">
                    <p><strong>PLANO DE TRATAMENTOS</strong></p>
                    <p>' . htmlspecialchars($form_data["nome_completo"]) . '
                    <br>' . htmlspecialchars($form_data["data"]) . '</p>
                    </span>';
$html .= '<ol style="list-style-type: upper-roman;" start="2">';
$html .= '<li><strong style="font-size:16px;">PREVIS&Atilde;O DE ENCARGOS</strong></li>';
$html .= '</ol>';
for ($i = 0; $i < count($form_data["procedimento"]); $i++) {
    $html .= '<span style="display: inline-block; border-left: 2px solid #000080; border-bottom: 2px solid #000080; line-height: normal;">
        <p><strong>PROCEDIMENTO MÉDICO:</strong> ' . htmlspecialchars($form_data["procedimento"][$i]) . '</p>
        <p><strong>- Nº de sessões:</strong> ' . htmlspecialchars($form_data["numero_sessoes"][$i]) . ' &nbsp;&nbsp;&nbsp;<strong>- Zona do Corpo a Ser Tratada:</strong> ' . htmlspecialchars($form_data["zona_tratada"][$i]) . '</p>
        <p><strong>- Duração prevista:</strong> ' . htmlspecialchars($form_data["duracao_prevista"][$i]) . '</p>
        <p><strong>- Inclui:</strong> ' . nl2br(htmlspecialchars($form_data["inclui"][$i])) . '</p>
        <p style="text-align:right;"><strong>Valor Previsto: ' . htmlspecialchars($form_data["valor_previsto"][$i]) . '</strong></p>
        <p style="text-align:right;font-size:13px;">- IVA incluído à taxa legal em Vigor</p>
        </span>';
}

// Adiciona o conteúdo na terceira página
$pdf->writeHTML($html, true, false, true, false, '');

 // Adiciona o conteúdo na quarta página
 $pdf->AddPage();

 // Define a fonte
 $pdf->SetFont('helvetica', '', 9);
 
 // Conteúdo do PDF com o conteúdo e identificadores dinâmicos
 $html = '
 <span style="text-align:right;font-size:13px;line-height: normal;">
                    <p><strong>PLANO DE TRATAMENTOS</strong></p>
                    <p>' . htmlspecialchars($form_data["nome_completo"]) . '</p>
                    <p>' . htmlspecialchars($form_data["data"]) . '</p>
                    </span>
<br>
<p><strong>Observações:</strong></p>
<p>Estes valores são referentes aos honorários e despesas clínicas, honorários médicos e materiais descritos e consideram o acompanhamento médico posterior ao tratamento. Estes valores são baseados em casos semelhantes podendo ser alterados caso haja variação nos serviços a prestar, nomeadamente alterações na duração da utilização da sala de tratamentos ou variações nos produtos aplicados, farmácia e internamento.</p>
<p>Não considere incluídos os seguintes itens:</p>
<ul>
    <li>Despesas com especialidades farmacêuticas extraordinárias ao procedimento (medicamentos)</li>
    <li>Consultas com outras especialidades médicas</li>
    <li>Tratamentos complementares</li>
    <li>Despesas extraordinárias associadas a "retoques" ou revisões</li>
</ul>
<br>';

$html .= '<ol style="list-style-type: upper-roman;" start="2">';
$html .= '<li><strong style="font-size:16px;">CONDIÇÕES DE PAGAMENTO</strong></li>';
$html .= '<li><strong style="font-size:12px;">O pagamento, <u>em numerário ou em cartão de débito nacional</u>, deverá ser realizado<br> na totalidade no dia e previamente à realização do procedimento médico.</strong></li>';
$html .= '</ol>';

$html .= '
<p><strong>Não serão aceites pagamentos por cheque.</strong></p>
<p>Este documento tem a validade de 180 dias, sempre condicionado a nova avaliação em consulta.</p>
<p>&nbsp;</p>
<table style="height: 72px; width: 554px;">
<tbody>
<tr>
<td style="width: 150.467px;"><strong>Ready Point, Lda</strong>:</td>
<td style="width: 414.533px;">IBAN PT50 0018 0003 5620 2302 0201 0</td>
</tr>
<tr>
<td style="width: 150.467px;">&nbsp;</td>
<td style="width: 414.533px;"><strong>&nbsp;</strong>BIC/SWIFT: TOTAPTPL</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>';

// Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="70">
        </td>
    </tr>
</table> ';

// Adiciona o conteúdo na quarta página
$pdf->writeHTML($html, true, false, true, false, '');

        break;
        case 'cedencia-imagem':
            // Adiciona a primeira página
            $pdf->AddPage();
        
            // Define a fonte
            $pdf->SetFont('helvetica', '', 8);
        
            // Conteúdo da primeira página
            $html = '
            <label style="font-size:18px;" for="consentimento_imagem"><u>Consentimento de Imagem</u></label>
                            <p>' . htmlspecialchars($form_data["nome_completo"]) . '  <strong>DECLARA</strong>, para os devidos efeitos:</p>
<ol>
<li>Que, no &acirc;mbito do procedimento m&eacute;dico ' . htmlspecialchars($form_data["procedimento_type"]) . '<sup>&reg;</sup> realizado com Dr Vitor Figueiredo ou com outro m&eacute;dico sob a dire&ccedil;&atilde;o t&eacute;cnica e cl&iacute;nica daquele, existe capta&ccedil;&atilde;o de imagens (fotografia e/ou v&iacute;deo), antes e depois de ocorrer o procedimento m&eacute;dico, capta&ccedil;&atilde;o essa promovida pelo Dr,Vitor Figueiredo e da sua responsabilidade exclusiva, com a qual concorda e na qual expressamente consente.</li>
<li>Ter sido informado que quaisquer materiais resultantes da capta&ccedil;&atilde;o de imagens (doravante designados apenas por &ldquo;Materiais&rdquo;) ser&atilde;o utilizados, sem identifica&ccedil;&atilde;o e sob reserva de confidencialidade a todo o tempo, para fins did&aacute;ticos, formativos e cient&iacute;ficos, bem como para fins publicit&aacute;rios ou comerciais, em qualquer meio de difus&atilde;o e comunica&ccedil;&atilde;o interno ou externo, a n&iacute;vel nacional ou internacional, atrav&eacute;s de quaisquer canais (incluindo digitais), nomeadamente televis&atilde;o, imprensa escrita, internet, redes sociais e outros existentes ou que venham a existir.</li>
<li>Conceder ao Dr. Vitor Manuel Figueiredo uma autoriza&ccedil;&atilde;o expressa, gratuita e por 10 (dez) anos para utiliza&ccedil;&atilde;o, por qualquer forma, e comunica&ccedil;&atilde;o ao p&uacute;blico, dos Materiais para os fins e atrav&eacute;s dos meios acima referidos, com vista &agrave; sua explora&ccedil;&atilde;o a n&iacute;vel mundial, sem limita&ccedil;&otilde;es de nenhum tipo e sem necessidade de obter nenhum consentimento ou autoriza&ccedil;&atilde;o posterior em rela&ccedil;&atilde;o ao uso que se fa&ccedil;a dos mesmos.</li>
<li>Conceder &agrave; Ready Point, Lda, que usa comercialmente a designa&ccedil;&atilde;o &ldquo;Ageless&ndash;Anti Aging Center&rdquo; (doravante &ldquo;Ageless&rdquo;), uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., a qual caducar&aacute; necess&aacute;ria e automaticamente se e quando o Dr. Vitor Manuel Figueiredo deixar de ser s&oacute;cio ou de integrar profissionalmente (o que primeiro ocorrer) tal sociedade, a qual deixar&aacute; de poder utilizar os Materiais. Para clarifica&ccedil;&atilde;o, a refer&ecirc;ncia feita neste documento a &ldquo;Ageless&rdquo; reporta-se exclusivamente &agrave; Ready Point, Lda.</li>
<li>Os Materiais podem ser utilizados no &acirc;mbito de qualquer parceria que a Ready Point Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Conceder &agrave; Global Metik Lda Lda. uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., podendo os Materiais ser utilizados no &acirc;mbito de qualquer parceria que a Global Metik, Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Que est&aacute; ciente do seu direito, nos termos do C&oacute;digo Civil, de revogar esta autoriza&ccedil;&atilde;o a todo o tempo, ainda que com obriga&ccedil;&atilde;o de indemnizar os danos e preju&iacute;zos causados a qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, tendo em conta os investimentos efetuados por estes, neles se incluindo, nomeadamente e sem excluir, os custos de remo&ccedil;&atilde;o, se poss&iacute;vel, da sua imagem dos Materiais ou de destrui&ccedil;&atilde;o dos Materiais;</li>
<li>N&atilde;o ceder futuramente, total ou parcialmente, os direitos aqui mencionados a qualquer outra pessoa f&iacute;sica ou jur&iacute;dica, de modo impeditivo ou que, de qualquer forma, interfira com os direitos acima concedidos;</li>
<li>Eximir qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, de todas as responsabilidades que possam resultar do exerc&iacute;cio dos direitos aqui concedidos e indemniz&aacute;-los por todos os danos resultantes do incumprimento dos compromissos aqui obtidos ou da inexatid&atilde;o das declara&ccedil;&otilde;es efetuadas;</li>
<li>Relativamente ao tratamento do seu dado pessoal imagem, ter sido informado que tal tratamento ser&aacute; da responsabilidade do Dr. Vitor Manuel Figueiredo, da Ageless ou da Global Metik, Lda., cada um enquanto respons&aacute;vel independente pelo tratamento de dados, ser&aacute; realizado para as finalidades acima indicadas e, ainda, que:</li>
<li>O tratamento do dado pessoal imagem n&atilde;o constitui uma obriga&ccedil;&atilde;o legal ou contratual, n&atilde;o estando o paciente obrigado a fornecer a sua imagem e n&atilde;o havendo quaisquer consequ&ecirc;ncias caso n&atilde;o o forne&ccedil;a;</li>
<li>As imagens do paciente ser&atilde;o mantidas pelo per&iacute;odo que se revelar estritamente necess&aacute;rio tendo em considera&ccedil;&atilde;o as finalidades supra indicadas.</li>
<li>Os dados pessoais utilizados no &acirc;mbito das parcerias poder&atilde;o ser transmitidos aos parceiros. Sem preju&iacute;zo, os dados poder&atilde;o ser acedidos por terceiros no &acirc;mbito de presta&ccedil;&otilde;es de servi&ccedil;os de tecnologias de informa&ccedil;&atilde;o ou outras, sendo que tais terceiros tratar&atilde;o os dados em nome do respons&aacute;vel pelo tratamento e de acordo com instru&ccedil;&otilde;es do mesmo.</li>
<li>Pode, a qualquer momento, retirar o seu consentimento para a capta&ccedil;&atilde;o e utiliza&ccedil;&atilde;o de imagens, sem que tal comprometa, no entanto, a licitude do tratamento realizado com base no consentimento previamente prestado.</li>
<li>Tem o direito de acesso, retifica&ccedil;&atilde;o, apagamento, portabilidade, limita&ccedil;&atilde;o e oposi&ccedil;&atilde;o ao tratamento dos seus dados pessoais e de retirar o consentimento, podendo exercer qualquer destes direitos mediante pedido escrito para Ageless, Via do Oriente, Lote 8, 5.03.01C, Escrit&oacute;rios 1,2,3,4, Edif&iacute;cio Tibre, 1990 - 514 Lisboa. Tem igualmente direito de apresentar uma reclama&ccedil;&atilde;o &agrave; Comiss&atilde;o Nacional de Prote&ccedil;&atilde;o de Dados (www.cnpd.pt).</li>
</ol>
            <p><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
            <p><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
            <p><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
            <p><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
            ';
        
            // Assinaturas do paciente e médico, se aplicável
            $html .= '
            <table style="width: 100%; text-align:center;">
                <tr>
                    <td>
                        <h4>Assinatura do Paciente:</h4>
                        <img src="' . $form_data['signature'] . '" alt="Assinatura" height="50">
                    </td>';
                    
            if ($medico_signature_file) {
                $html .= '
                    <td>
                        <h4>Assinatura do Médico:</h4>
                        <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="50">
                    </td>';
            }
        
            $html .= '
                </tr>
            </table>';
            
            // Adiciona o conteúdo na primeira página
            $pdf->writeHTML($html, true, false, true, false, '');
        
            // Não adiciona mais páginas
            break;
        
        case 'CI-Acido-Hiaulorico':
                // Adiciona uma página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo do PDF com o conteúdo e identificadores dinâmicos
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">CONSENTIMENTO INFORMADO PARA A APLICAÇÃO DE ÁCIDO HIALURÓNICO</span></h1>
<p style="margin:0cm;text-align:justify;"><u>O que é a ácido hialurónico</u>?
<br>O ácido hialurónico é um polissacarídeo natural que faz parte da estrutura da pele e do tecido conjuntivo. É um produto não animal sintético e purificado que se apresenta sob a forma de um gel estéril e transparente. 
Atua aumentando o volume do tecido onde é colocado ficando naturalmente integrado no mesmo. A seu tempo irá desaparecer por uma degradação isovolémica. A durabilidade é muito variável. Existem casos em que o ácido hialurónico é completamente degradado desaparecendo o efeito em poucos dias. Após o primeiro tratamento podem ser necessárias injecções adicionais e periódicas para obter o grau de correcção desejado.
Não foi testado em mulheres grávidas ou a amamentar.
</p><br>
<p style="margin:0cm;text-align:justify;"><u>Efeitos secundários</u><br>
Hematomas dependendo da zona a tratar, da toma de certos medicamentos (por exemplo aspirina e derivados) e das características de cada pessoa. Podem demorar vários dias a desaparecer. <br>
Dor ou desconforto espontâneos ou ao toque nas zonas tratadas que podem permanecer alguns dias e necessitar de analgesia oral.<br>
Edema das regiões tratadas que pode surgir subitamente ou nos dias seguintes e necessitar de corticoterapia oral ou sistêmica.<br>
Rubor (vermelhidão) que pode permanecer alguns dias e necessitar de medicação tópica.<br>
Infeção dos locais tratados que pode necessitar de ciclos prolongados de antibióticos sistémicos.<br>
Nódulos e endurecimentos permanentes podem ocorrer e necessitar de procedimentos cirúrgicos para completa resolução.<br>
Assimetrias que podem ser mais notórias em determinadas zonas da face e que poderão necessitar de meses para estabilizar ou mesmo de tratamentos adicionais.<br>
Alteração da pigmentação na zona do tratamento em casos raros.<br>
Uma reação alérgica ou de hipersensibilidade é sempre uma possibilidade, embora extremamente rara, tal como em qualquer outra forma de administração de medicamentos. Poderá necessitar de intervenções médicas emergentes e de evacuação hospitalar.<br>
Reativação de herpes simples ou de herpes zóster sendo necessário intervenção anti-herpética.<br>
Ausência de efeito dos fármacos utilizados que se pode dever a circunstâncias individuais inerentes à especificidade do corpo humano e que é impossível de prever.
&nbsp;</p>
<p style="margin:0cm;text-align:justify;"><u>Autorização</u><br>
Fui informada(o) e entendi que a aplicação de ácido hialurónico implica riscos. Se surgir alguma complicação imediata dou o meu consentimento para que se faça o que seja mais conveniente.<br>
Fui informada(o) da necessidade de evitar praia, sauna, piscina e qualquer outro tipo de exposição solar até 48 horas depois do tratamento, assim como da necessidade de evitar exercício físico nas primeiras 24 horas.<br> 
Fui informada(o) da necessidade de dar sempre conhecimento ao Médico da medicação que faço e da mera possibilidade de em qualquer altura estar grávida.<br>
Fui informada(o) de que a possibilidade de surgirem reações alérgicas ou de hipersensibilidade é a mesma que existe em qualquer outra via de administração de fármacos.<br>
Fui informada(o) do direito que tenho de aceitar ou não o procedimento, bem como do direito de anular a aceitação prévia das possibilidades de êxito do tratamento. Reconheço que não me podem ser dadas garantias ou segurança absoluta acerca do resultado do tratamento e que as minhas perguntas neste sentido foram satisfatoriamente respondidas. Sei que posso colocar reservas ou condições particulares em relação ao tratamento e foi-me dada oportunidade para tal.<br>
Autorizo o médico a administrar os fármacos necessários para o meu tratamento assumindo todas as consequências daí resultantes.<br>
Autorizo a obtenção de documentos fotográficos necessários para o adequado cumprimento didático e científico sendo preservada a sua identidade e privacidade.<br>
Tudo o exposto me foi claramente explicado e aceito o tratamento proposto, estando consciente das possibilidades de êxito e das possíveis complicações pelo que assino, em sinal de acordo, de aceitação e de entendimento, este documento.<br>
Fui informada (o) que em caso de necessidade ou dúvida posso contactar o médico 24 horas por dia através de um contacto que me foi fornecido.<br>
Dou o meu consentimento para que me seja aplicado ácido hialurónico.<br>
Dou o meu consentimento para que me seja realizada a Ácido Hialurónico.
</p><br>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da segunda página do PDF
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">AUTORIZAÇÃO PARA A APLICAÇÃO DE ÁCIDO HIALURÓNICO</span></h1>
<p style="margin-left:0cm;">&nbsp;</p>
<p style="margin:0cm;text-align:justify;">Declaro que foi por minha iniciativa que recorri à consulta médica por sentir necessidade de diagnosticar e tratar uma condição física e psicológica que me perturba.<br>
Reconheço que a intervenção acima designada me foi proposta após me ter sido realizado um diagnóstico médico detalhado, concreto e rigoroso, com o qual concordo e no qual me revejo.<br>
Reconheço que a intervenção acima designada se destina a tratar uma situação física e psíquica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá ajudar a prevenir o agravamento da situação física e psicológica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá contribuir para a cura da situação física e psicológica de que padeço e me foi diagnosticada.<br>
Reconheço que a intervenção se destina a melhorar a minha autoconfiança e restabelecer o meu bem-estar físico, mental e social.<br>
Reconheço que a intervenção proposta se destina, portanto, a restabelecer, proteger e manter a minha saúde física e mental.<br>
Reconheço que a intervenção acima designada é efetuada numa clínica médica com todas as condições, por um licenciado em medicina inscrito na ordem dos médicos portugueses, que me certifiquei que possui preparação e formação para a executar.
</p>
<br>
<p>&nbsp;</p>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na segunda página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da terceira página do PDF
$html = '
<p style="margin:0cm;text-align:right;"><span style="font-size:16px;">DIAGNÓSTICO MÉDICO PARA O TRATAMENTO Ácido Hialurónico</span></p>
<p style="margin:0cm;text-align:right;">&nbsp;</p>
<span style="text-align:left">
<p>Diagn&oacute;stico m&eacute;dico conforme ICD 10(Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de)</p>
<ul>
<li>H02 - Outras perturba&ccedil;&otilde;es da p&aacute;lpebra</li>
<li>H0230 - Blefarocal&aacute;sia em olho n&atilde;o especificado, p&aacute;lpebra n&atilde;o especificada</li>
<li>H024 &ndash; Ptose da p&aacute;lpebra</li>
<li>L568 &ndash; Outras altera&ccedil;&otilde;es agudas especificadas da pele devidas a radia&ccedil;&atilde;o ultravioleta</li>
<li>L574 &ndash; Cutis laxa associada &aacute; idade</li>
<li>L814 &ndash; Outras formas de hiperpigmenta&ccedil;&atilde;o pela melanina</li>
<li>L85 &ndash; Outro espessamento epid&eacute;rmico</li>
<li>L853 &ndash; Xerose cut&acirc;nea</li>
<li>L90 &ndash; Perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L908 &ndash; Outras perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L909 &ndash; Perturba&ccedil;&atilde;o atr&oacute;ficas da pele sem outra especifica&ccedil;&atilde;o</li>
<li>L987 &ndash; Pele e tecido subcut&acirc;neo excessivo e redundante</li>
<li>L989 &ndash; Afe&ccedil;&otilde;es da pele e do tecido subcut&acirc;neo, n&atilde;o especificadas</li>
</ul>
<p>Que originam e causam</p>
<ul>
<li>Sintomas depressivos</li>
<li>Ansiedade</li>
<li>Ins&oacute;nia e/ou perturba&ccedil;&otilde;es do sono</li>
<li>Irritabilidade</li>
<li>Dificuldade de desempenhar as atividades habituais</li>
<li>Baixa vitalidade, energia e tranquilidade</li>
<li>Estado de infelicidade</li>
<li>Incapacidade de responder &agrave;s adversidades</li>
<li>Perturba&ccedil;&atilde;o das rela&ccedil;&otilde;es com os demais</li>
<li>Dificuldade em sentir-se bem consigo pr&oacute;prio</li>
<li>Aus&ecirc;ncia de um estado de completo bem-estar f&iacute;sico, mental e social</li>
</ul>
<p>O que conforme a ICD 10 (Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de) :</p>
<ul>
<li>F39 &ndash; Perturba&ccedil;&atilde;o do humor [afetivo], sem outra especifica&ccedil;&atilde;o</li>
<li>F419 &ndash; Estado de ansiedade, sem outra especifica&ccedil;&atilde;o</li>
<li>F51 &ndash; Transtornos n&atilde;o-org&acirc;nicos do sono devidos a fatores emocionais</li>
<li>F518 &ndash; Outros transtornos do sono devidos a fatores n&atilde;o-org&acirc;nicos</li>
</ul>
    </span>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
';

// Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="50">
        </td>';
        
if ($medico_signature_file) {
    $html .= '
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="50">
        </td>';
}

$html .= '
    </tr>
</table>';

// Adiciona o conteúdo na terceira página
$pdf->writeHTML($html, true, false, true, false, '');

// Verifica se o consentimento de imagem foi removido
$remover_consentimento = isset($_POST["remover_consentimento_imagem"]) && $_POST["remover_consentimento_imagem"] == '1';

// Adiciona uma nova página para consentimento de imagem SE o consentimento NÃO foi removido
if (!$remover_consentimento) {
    // Adiciona uma nova página
    $pdf->AddPage();

    // Define a fonte
    $pdf->SetFont('helvetica', '', 8);

    // Conteúdo da quarta página (Consentimento de Imagem)
    $html = '
    <label style="font-size:18px;" for="consentimento_imagem"><u>Consentimento de Imagem</u></label>
                            <p>' . htmlspecialchars($form_data["nome_completo"]) . '  <strong>DECLARA</strong>, para os devidos efeitos:</p>
<p>&nbsp;</p>
<ol>
<li>Que, no &acirc;mbito do procedimento m&eacute;dico ' . htmlspecialchars($form_data["procedimento_type"]) . '<sup>&reg;</sup> realizado com Dr Vitor Figueiredo ou com outro m&eacute;dico sob a dire&ccedil;&atilde;o t&eacute;cnica e cl&iacute;nica daquele, existe capta&ccedil;&atilde;o de imagens (fotografia e/ou v&iacute;deo), antes e depois de ocorrer o procedimento m&eacute;dico, capta&ccedil;&atilde;o essa promovida pelo Dr,Vitor Figueiredo e da sua responsabilidade exclusiva, com a qual concorda e na qual expressamente consente.</li>
<li>Ter sido informado que quaisquer materiais resultantes da capta&ccedil;&atilde;o de imagens (doravante designados apenas por &ldquo;Materiais&rdquo;) ser&atilde;o utilizados, sem identifica&ccedil;&atilde;o e sob reserva de confidencialidade a todo o tempo, para fins did&aacute;ticos, formativos e cient&iacute;ficos, bem como para fins publicit&aacute;rios ou comerciais, em qualquer meio de difus&atilde;o e comunica&ccedil;&atilde;o interno ou externo, a n&iacute;vel nacional ou internacional, atrav&eacute;s de quaisquer canais (incluindo digitais), nomeadamente televis&atilde;o, imprensa escrita, internet, redes sociais e outros existentes ou que venham a existir.</li>
<li>Conceder ao Dr. Vitor Manuel Figueiredo uma autoriza&ccedil;&atilde;o expressa, gratuita e por 10 (dez) anos para utiliza&ccedil;&atilde;o, por qualquer forma, e comunica&ccedil;&atilde;o ao p&uacute;blico, dos Materiais para os fins e atrav&eacute;s dos meios acima referidos, com vista &agrave; sua explora&ccedil;&atilde;o a n&iacute;vel mundial, sem limita&ccedil;&otilde;es de nenhum tipo e sem necessidade de obter nenhum consentimento ou autoriza&ccedil;&atilde;o posterior em rela&ccedil;&atilde;o ao uso que se fa&ccedil;a dos mesmos.</li>
<li>Conceder &agrave; Ready Point, Lda, que usa comercialmente a designa&ccedil;&atilde;o &ldquo;Ageless&ndash;Anti Aging Center&rdquo; (doravante &ldquo;Ageless&rdquo;), uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., a qual caducar&aacute; necess&aacute;ria e automaticamente se e quando o Dr. Vitor Manuel Figueiredo deixar de ser s&oacute;cio ou de integrar profissionalmente (o que primeiro ocorrer) tal sociedade, a qual deixar&aacute; de poder utilizar os Materiais. Para clarifica&ccedil;&atilde;o, a refer&ecirc;ncia feita neste documento a &ldquo;Ageless&rdquo; reporta-se exclusivamente &agrave; Ready Point, Lda.</li>
<li>Os Materiais podem ser utilizados no &acirc;mbito de qualquer parceria que a Ready Point Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Conceder &agrave; Global Metik Lda Lda. uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., podendo os Materiais ser utilizados no &acirc;mbito de qualquer parceria que a Global Metik, Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Que est&aacute; ciente do seu direito, nos termos do C&oacute;digo Civil, de revogar esta autoriza&ccedil;&atilde;o a todo o tempo, ainda que com obriga&ccedil;&atilde;o de indemnizar os danos e preju&iacute;zos causados a qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, tendo em conta os investimentos efetuados por estes, neles se incluindo, nomeadamente e sem excluir, os custos de remo&ccedil;&atilde;o, se poss&iacute;vel, da sua imagem dos Materiais ou de destrui&ccedil;&atilde;o dos Materiais;</li>
<li>N&atilde;o ceder futuramente, total ou parcialmente, os direitos aqui mencionados a qualquer outra pessoa f&iacute;sica ou jur&iacute;dica, de modo impeditivo ou que, de qualquer forma, interfira com os direitos acima concedidos;</li>
<li>Eximir qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, de todas as responsabilidades que possam resultar do exerc&iacute;cio dos direitos aqui concedidos e indemniz&aacute;-los por todos os danos resultantes do incumprimento dos compromissos aqui obtidos ou da inexatid&atilde;o das declara&ccedil;&otilde;es efetuadas;</li>
<li>Relativamente ao tratamento do seu dado pessoal imagem, ter sido informado que tal tratamento ser&aacute; da responsabilidade do Dr. Vitor Manuel Figueiredo, da Ageless ou da Global Metik, Lda., cada um enquanto respons&aacute;vel independente pelo tratamento de dados, ser&aacute; realizado para as finalidades acima indicadas e, ainda, que:</li>
<li>O tratamento do dado pessoal imagem n&atilde;o constitui uma obriga&ccedil;&atilde;o legal ou contratual, n&atilde;o estando o paciente obrigado a fornecer a sua imagem e n&atilde;o havendo quaisquer consequ&ecirc;ncias caso n&atilde;o o forne&ccedil;a;</li>
<li>As imagens do paciente ser&atilde;o mantidas pelo per&iacute;odo que se revelar estritamente necess&aacute;rio tendo em considera&ccedil;&atilde;o as finalidades supra indicadas.</li>
<li>Os dados pessoais utilizados no &acirc;mbito das parcerias poder&atilde;o ser transmitidos aos parceiros. Sem preju&iacute;zo, os dados poder&atilde;o ser acedidos por terceiros no &acirc;mbito de presta&ccedil;&otilde;es de servi&ccedil;os de tecnologias de informa&ccedil;&atilde;o ou outras, sendo que tais terceiros tratar&atilde;o os dados em nome do respons&aacute;vel pelo tratamento e de acordo com instru&ccedil;&otilde;es do mesmo.</li>
<li>Pode, a qualquer momento, retirar o seu consentimento para a capta&ccedil;&atilde;o e utiliza&ccedil;&atilde;o de imagens, sem que tal comprometa, no entanto, a licitude do tratamento realizado com base no consentimento previamente prestado.</li>
<li>Tem o direito de acesso, retifica&ccedil;&atilde;o, apagamento, portabilidade, limita&ccedil;&atilde;o e oposi&ccedil;&atilde;o ao tratamento dos seus dados pessoais e de retirar o consentimento, podendo exercer qualquer destes direitos mediante pedido escrito para Ageless, Via do Oriente, Lote 8, 5.03.01C, Escrit&oacute;rios 1,2,3,4, Edif&iacute;cio Tibre, 1990 - 514 Lisboa. Tem igualmente direito de apresentar uma reclama&ccedil;&atilde;o &agrave; Comiss&atilde;o Nacional de Prote&ccedil;&atilde;o de Dados (www.cnpd.pt).</li>
</ol>
    <p><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
    <p><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
    <p><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
    <p><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
    ';

    // Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="50">
        </td>';
        
if ($medico_signature_file) {
    $html .= '
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="50">
        </td>';
}

$html .= '
    </tr>
</table>';

    // Adiciona o conteúdo na quarta página
    $pdf->writeHTML($html, true, false, true, false, '');
}


                break;

case 'CI-bodyglam':
 // Adiciona uma página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo do PDF com o conteúdo e identificadores dinâmicos
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">Consentimento informado para o tratamento Bodyglam<sup>®</sup></span></h1>
<p style="margin-left:0cm;">&nbsp;</p>
<p style="margin:0cm;text-align:justify;"><u>O que é o tratamento Bodyglam®?</u>
<br>O Bodyglam® é um procedimento de rejuvenescimento do rosto, pescoço, e/ou corpo com resultados progressivos. Consiste na aplicação local, na superfície da pele, de duas fontes de energia à base de ultrassons e radiofrequência. Por vezes é necessário seguir um plano alimentar rigoroso, adequado e adaptado às circunstâncias individuais de cada pessoa. Estes ultrassons e radiofrequência atuam nos tecidos de forma intensa mas não penetram para além da camada adiposa ao contrário dos ultrassons usados nas ecografias «normais». Destinam-se a tratar flacidez e/ou gordura localizada em excesso. Pode ser feito por qualquer pessoa em qualquer altura do ano, excepto durante a gravidez ou aleitamento. São necessárias várias sessões de tratamento normalmente espaçadas por uma semana.&nbsp;</p>
<p style="margin:0cm;text-align:justify;">&nbsp;</p>
<p style="margin:0cm;text-align:justify;"><u>Efeitos secundários</u><br>Hematomas dependendo da zona a tratar, da toma de certos medicamentos (por exemplo aspirina e derivados) e das características de cada pessoa.&nbsp;<br>Podem demorar vários dias a desaparecer. Dor ou desconforto espontâneos ou ao toque nas zonas tratadas que podem permanecer alguns dias e necessitar de analgesia oral. Edema das regiões tratadas que pode surgir subitamente ou nos dias seguintes e necessitar de corticoterapia oral ou sistémica. Rubor (vermelhidão) que pode permanecer alguns dias e necessitar de medicação tópica. Infeção dos locais tratados que pode necessitar de ciclos prolongados de antibióticos sistémicos. Nódulos e endurecimentos permanentes podem ocorrer e necessitar de procedimentos cirúrgicos para completa resolução. Assimetrias, que podem ser mais notórias em determinadas zonas da face, e que poderão necessitar de meses para estabilizar ou mesmo de tratamentos adicionais. Alteração da pigmentação na zona do tratamento em casos raros. Uma reação alérgica ou de hipersensibilidade ao anestésico tópico por vezes utilizado é sempre uma possibilidade, embora extremamente rara, tal como em qualquer outra forma de administração de medicamentos. Poderá necessitar de intervenções médicas emergentes e de evacuação hospitalar. Reativação de herpes simples ou de herpes zóster sendo necessário intervenção anti-herpética. Ausência de efeito dos ultrassons e radiofrequência utilizados que se pode dever a circunstâncias individuais inerentes à especificidade do corpo humano e que é impossível de prever.&nbsp;</p>
<p style="margin:0cm;text-align:justify;">&nbsp;</p>
<p style="margin:0cm;text-align:justify;"><u>Autorização</u></p>
<p style="margin:0cm;text-align:justify;">Fui informado(a) e entendi que o tratamento Bodyglam® implica riscos. Se surgir alguma complicação imediata dou o meu consentimento para que se faça o que for mais conveniente. Fui informado(a) da necessidade de evitar praia, sauna, piscina e qualquer outro tipo de exposição solar até 48 horas depois do tratamento, assim como da necessidade de evitar exercício físico nas primeiras 24 horas. Fui informado(a) da necessidade de dar sempre conhecimento ao médico e à equipa da medicação que faço e da mera possibilidade de em qualquer altura estar grávida. Fui informado(a) de que a possibilidade de surgirem reações alérgicas ou de hipersensibilidade é a mesma que existe em qualquer outra via de administração de fármacos. Fui informado(a) do direito que tenho de aceitar ou não o procedimento, bem como do direito de anular a aceitação prévia das possibilidades de êxito do tratamento. Reconheço que não me podem ser dadas garantias ou segurança absoluta acerca do resultado do tratamento e que as minhas perguntas neste sentido foram satisfatoriamente respondidas. Sei que posso colocar reservas ou condições particulares em relação ao tratamento e foi-me dada oportunidade para tal. Autorizo o médico a administrar os fármacos necessários para o meu tratamento assumindo todas as consequências daí resultante. Autorizo a obtenção de documentos fotográficos necessários para o adequado cumprimento didático e científico sendo preservada a sua identidade e privacidade. Tudo o exposto me foi claramente explicado e aceito o tratamento proposto, estando consciente das possibilidades de êxito e das possíveis complicações pelo que assino, em sinal de acordo, de aceitação e de entendimento, este documento. Fui informado (a) que em caso de necessidade ou dúvida posso contactar o médico e equipa 24 horas por dia através de um contacto que me foi fornecido. Dou o meu consentimento a que me seja realizado o tratamento Ultrassom</p>
<br>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da segunda página do PDF
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">AUTORIZAÇÃO PARA O TRATAMENTO BODYGLAM<sup>®</sup></span></h1>
<p style="margin-left:0cm;">&nbsp;</p>
<p style="margin:0cm;text-align:justify;">Declaro que foi por minha iniciativa que recorri à consulta médica por sentir necessidade de diagnosticar e tratar uma condição física e psicológica que me perturba.</p>
<p style="margin:0cm;text-align:justify;">Reconheço que a intervenção acima designada me foi proposta após me ter sido realizado um diagnóstico médico detalhado, concreto e rigoroso, com o qual concordo e no qual me revejo.&nbsp;</p>
<p style="margin:0cm;text-align:justify;">Reconheço que a intervenção acima designada se destina a tratar uma situação física e psíquica de que padeço e que me foi diagnosticada.&nbsp;</p>
<p style="margin:0cm;text-align:justify;">Reconheço que a intervenção acima designada poderá ajudar a prevenir o agravamento da situação física e psicológica de que padeço e que me foi diagnosticada.&nbsp;</p>
<p style="margin:0cm;text-align:justify;">Reconheço que a intervenção acima designada poderá contribuir para a cura da situação física e psicológica de que padeço e me foi diagnosticada.&nbsp;</p>
<p style="margin:0cm;text-align:justify;">Reconheço que a intervenção se destina a melhorar a minha autoconfiança e restabelecer o meu bem-estar físico, mental e social.&nbsp;</p>
<p style="margin:0cm;text-align:justify;">Reconheço que a intervenção proposta se destina, portanto, a restabelecer, proteger e manter a minha saúde física e mental.&nbsp;</p>
<p style="margin:0cm;text-align:justify;">Reconheço que a intervenção acima designada é efetuada numa clínica médica com todas as condições, por um licenciado em medicina inscrito na ordem dos médicos portugueses, que me certifiquei que possui preparação e formação para a executar.</p>
<br>
<p>&nbsp;</p>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na segunda página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da terceira página do PDF
$html = '
<p style="margin:0cm;text-align:right;"><span style="font-size:16px;font-weight:bold;">DIAGNÓSTICO MÉDICO PARA O TRATAMENTO BODYGLAM<sup>®</sup></span></p>
<span style="text-align:left">
<p>Diagn&oacute;stico m&eacute;dico conforme ICD 10 (Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de)</p>
<ul>
<li>H02 - Outras perturba&ccedil;&otilde;es da p&aacute;lpebra</li>
<li>H0230 - Blefarocal&aacute;sia em olho n&atilde;o especificado, p&aacute;lpebra n&atilde;o especificada</li>
<li>H024 &ndash; Ptose da p&aacute;lpebra</li>
<li>L568 &ndash; Outras altera&ccedil;&otilde;es agudas especificadas da pele devidas a radia&ccedil;&atilde;o ultravioleta</li>
<li>L574 &ndash; Cutis laxa associada &aacute; idade</li>
<li>L814 &ndash; Outras formas de hiperpigmenta&ccedil;&atilde;o pela melanina</li>
<li>L85 &ndash; Outro espessamento epid&eacute;rmico</li>
<li>L853 &ndash; Xerose cut&acirc;nea</li>
<li>L90 &ndash; Perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L908 &ndash; Outras perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L909 &ndash; Perturba&ccedil;&atilde;o atr&oacute;ficas da pele sem outra especifica&ccedil;&atilde;o</li>
<li>L987 &ndash; Pele e tecido subcut&acirc;neo excessivo e redundante</li>
<li>L989 &ndash; Afe&ccedil;&otilde;es da pele e do tecido subcut&acirc;neo, n&atilde;o especificadas</li>
</ul>
<p>&nbsp;</p>
<p>Que originam e causam</p>
<ul>
<li>Sintomas depressivos</li>
<li>Ansiedade</li>
<li>Ins&oacute;nia e/ou perturba&ccedil;&otilde;es do sono</li>
<li>Irritabilidade</li>
<li>Dificuldade de desempenhar as atividades habituais</li>
<li>Baixa vitalidade, energia e tranquilidade</li>
<li>Estado de infelicidade</li>
<li>Incapacidade de responder &agrave;s adversidades</li>
<li>Perturba&ccedil;&atilde;o das rela&ccedil;&otilde;es com os demais</li>
<li>Dificuldade em sentir-se bem consigo pr&oacute;prio</li>
<li>Aus&ecirc;ncia de um estado de completo bem-estar f&iacute;sico, mental e social</li>
</ul>
<p>O que conforme a ICD 10 (Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de) :</p>
<ul>
<li>F39 &ndash; Perturba&ccedil;&atilde;o do humor [afetivo], sem outra especifica&ccedil;&atilde;o</li>
<li>F419 &ndash; Estado de ansiedade, sem outra especifica&ccedil;&atilde;o</li>
<li>F51 &ndash; Transtornos n&atilde;o-org&acirc;nicos do sono devidos a fatores emocionais</li>
<li>F518 &ndash; Outros transtornos do sono devidos a fatores n&atilde;o-org&acirc;nicos</li>
</ul>
    </span>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
';

// Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="70">
        </td>
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="70">
        </td>
    </tr>
</table>';


// Adiciona o conteúdo na terceira página
$pdf->writeHTML($html, true, false, true, false, '');

// Verifica se o consentimento de imagem foi removido
$remover_consentimento = isset($_POST["remover_consentimento_imagem"]) && $_POST["remover_consentimento_imagem"] == '1';

// Adiciona uma nova página para consentimento de imagem SE o consentimento NÃO foi removido
if (!$remover_consentimento) {
    // Adiciona uma nova página
    $pdf->AddPage();

    // Define a fonte
    $pdf->SetFont('helvetica', '', 8);

    // Conteúdo da quarta página (Consentimento de Imagem)
    $html = '
    <label style="font-size:18px;" for="consentimento_imagem"><u>Consentimento de Imagem</u></label>
                            <p>' . htmlspecialchars($form_data["nome_completo"]) . '  <strong>DECLARA</strong>, para os devidos efeitos:</p>
<p>&nbsp;</p>
<ol>
<li>Que, no &acirc;mbito do procedimento m&eacute;dico ' . htmlspecialchars($form_data["procedimento_type"]) . '<sup>&reg;</sup> realizado com Dr Vitor Figueiredo ou com outro m&eacute;dico sob a dire&ccedil;&atilde;o t&eacute;cnica e cl&iacute;nica daquele, existe capta&ccedil;&atilde;o de imagens (fotografia e/ou v&iacute;deo), antes e depois de ocorrer o procedimento m&eacute;dico, capta&ccedil;&atilde;o essa promovida pelo Dr,Vitor Figueiredo e da sua responsabilidade exclusiva, com a qual concorda e na qual expressamente consente.</li>
<li>Ter sido informado que quaisquer materiais resultantes da capta&ccedil;&atilde;o de imagens (doravante designados apenas por &ldquo;Materiais&rdquo;) ser&atilde;o utilizados, sem identifica&ccedil;&atilde;o e sob reserva de confidencialidade a todo o tempo, para fins did&aacute;ticos, formativos e cient&iacute;ficos, bem como para fins publicit&aacute;rios ou comerciais, em qualquer meio de difus&atilde;o e comunica&ccedil;&atilde;o interno ou externo, a n&iacute;vel nacional ou internacional, atrav&eacute;s de quaisquer canais (incluindo digitais), nomeadamente televis&atilde;o, imprensa escrita, internet, redes sociais e outros existentes ou que venham a existir.</li>
<li>Conceder ao Dr. Vitor Manuel Figueiredo uma autoriza&ccedil;&atilde;o expressa, gratuita e por 10 (dez) anos para utiliza&ccedil;&atilde;o, por qualquer forma, e comunica&ccedil;&atilde;o ao p&uacute;blico, dos Materiais para os fins e atrav&eacute;s dos meios acima referidos, com vista &agrave; sua explora&ccedil;&atilde;o a n&iacute;vel mundial, sem limita&ccedil;&otilde;es de nenhum tipo e sem necessidade de obter nenhum consentimento ou autoriza&ccedil;&atilde;o posterior em rela&ccedil;&atilde;o ao uso que se fa&ccedil;a dos mesmos.</li>
<li>Conceder &agrave; Ready Point, Lda, que usa comercialmente a designa&ccedil;&atilde;o &ldquo;Ageless&ndash;Anti Aging Center&rdquo; (doravante &ldquo;Ageless&rdquo;), uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., a qual caducar&aacute; necess&aacute;ria e automaticamente se e quando o Dr. Vitor Manuel Figueiredo deixar de ser s&oacute;cio ou de integrar profissionalmente (o que primeiro ocorrer) tal sociedade, a qual deixar&aacute; de poder utilizar os Materiais. Para clarifica&ccedil;&atilde;o, a refer&ecirc;ncia feita neste documento a &ldquo;Ageless&rdquo; reporta-se exclusivamente &agrave; Ready Point, Lda.</li>
<li>Os Materiais podem ser utilizados no &acirc;mbito de qualquer parceria que a Ready Point Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Conceder &agrave; Global Metik Lda Lda. uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., podendo os Materiais ser utilizados no &acirc;mbito de qualquer parceria que a Global Metik, Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Que est&aacute; ciente do seu direito, nos termos do C&oacute;digo Civil, de revogar esta autoriza&ccedil;&atilde;o a todo o tempo, ainda que com obriga&ccedil;&atilde;o de indemnizar os danos e preju&iacute;zos causados a qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, tendo em conta os investimentos efetuados por estes, neles se incluindo, nomeadamente e sem excluir, os custos de remo&ccedil;&atilde;o, se poss&iacute;vel, da sua imagem dos Materiais ou de destrui&ccedil;&atilde;o dos Materiais;</li>
<li>N&atilde;o ceder futuramente, total ou parcialmente, os direitos aqui mencionados a qualquer outra pessoa f&iacute;sica ou jur&iacute;dica, de modo impeditivo ou que, de qualquer forma, interfira com os direitos acima concedidos;</li>
<li>Eximir qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, de todas as responsabilidades que possam resultar do exerc&iacute;cio dos direitos aqui concedidos e indemniz&aacute;-los por todos os danos resultantes do incumprimento dos compromissos aqui obtidos ou da inexatid&atilde;o das declara&ccedil;&otilde;es efetuadas;</li>
<li>Relativamente ao tratamento do seu dado pessoal imagem, ter sido informado que tal tratamento ser&aacute; da responsabilidade do Dr. Vitor Manuel Figueiredo, da Ageless ou da Global Metik, Lda., cada um enquanto respons&aacute;vel independente pelo tratamento de dados, ser&aacute; realizado para as finalidades acima indicadas e, ainda, que:</li>
<li>O tratamento do dado pessoal imagem n&atilde;o constitui uma obriga&ccedil;&atilde;o legal ou contratual, n&atilde;o estando o paciente obrigado a fornecer a sua imagem e n&atilde;o havendo quaisquer consequ&ecirc;ncias caso n&atilde;o o forne&ccedil;a;</li>
<li>As imagens do paciente ser&atilde;o mantidas pelo per&iacute;odo que se revelar estritamente necess&aacute;rio tendo em considera&ccedil;&atilde;o as finalidades supra indicadas.</li>
<li>Os dados pessoais utilizados no &acirc;mbito das parcerias poder&atilde;o ser transmitidos aos parceiros. Sem preju&iacute;zo, os dados poder&atilde;o ser acedidos por terceiros no &acirc;mbito de presta&ccedil;&otilde;es de servi&ccedil;os de tecnologias de informa&ccedil;&atilde;o ou outras, sendo que tais terceiros tratar&atilde;o os dados em nome do respons&aacute;vel pelo tratamento e de acordo com instru&ccedil;&otilde;es do mesmo.</li>
<li>Pode, a qualquer momento, retirar o seu consentimento para a capta&ccedil;&atilde;o e utiliza&ccedil;&atilde;o de imagens, sem que tal comprometa, no entanto, a licitude do tratamento realizado com base no consentimento previamente prestado.</li>
<li>Tem o direito de acesso, retifica&ccedil;&atilde;o, apagamento, portabilidade, limita&ccedil;&atilde;o e oposi&ccedil;&atilde;o ao tratamento dos seus dados pessoais e de retirar o consentimento, podendo exercer qualquer destes direitos mediante pedido escrito para Ageless, Via do Oriente, Lote 8, 5.03.01C, Escrit&oacute;rios 1,2,3,4, Edif&iacute;cio Tibre, 1990 - 514 Lisboa. Tem igualmente direito de apresentar uma reclama&ccedil;&atilde;o &agrave; Comiss&atilde;o Nacional de Prote&ccedil;&atilde;o de Dados (www.cnpd.pt).</li>
</ol>
    <p><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
    <p><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
    <p><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
    <p><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
    ';

    // Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="50">
        </td>';
        
if ($medico_signature_file) {
    $html .= '
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="50">
        </td>';
}

$html .= '
    </tr>
</table>';


    // Adiciona o conteúdo na quarta página
    $pdf->writeHTML($html, true, false, true, false, '');
}
                break;
                
case 'CI-beauty-flash':
                 // Adiciona uma página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo do PDF com o conteúdo e identificadores dinâmicos
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">Consentimento informado para o tratamento Beauty Flash<sup>®</sup></span></h1>
<br>
<p style="margin:0cm;text-align:justify;"><u>O que é o tratamento Beauty Flash</u><sup>®</sup>?
<br>O Beauty Flash<sup>®</sup> é um procedimento de rejuvenescimento do rosto, pescoço, decote e/ou mãos com resultados imediatos. Consiste na aplicação local, no interior da pele, de uma mistura de princípios activos, através de mesoterapia e microneedling, permitindo que cheguem aos locais onde são necessários. Este «cocktail» é constituído por ácido hialurónico, colagénio, vitaminas, antioxidantes, aminoácidos, ácidos nucleicos, minerais e enzimas, todos produtos aprovados e certificados que não causam alergias. 
Pode ser feito por qualquer pessoa em qualquer altura do ano, excepto durante a gravidez ou aleitamento.
</p><br>
<p style="margin:0cm;text-align:justify;"><u>Efeitos secundários</u><br>
Hematomas dependendo da zona a tratar, da toma de certos medicamentos (por exemplo aspirina e derivados) e das características de cada pessoa. Podem demorar vários dias a desaparecer. <br>
Dor ou desconforto espontâneos ou ao toque nas zonas tratadas que podem permanecer alguns dias e necessitar de analgesia oral.<br>
Edema das regiões tratadas que pode surgir subitamente ou nos dias seguintes e necessitar de corticoterapia oral ou sistémica.<br>
Rubor (vermelhidão) que pode permanecer alguns dias e necessitar de medicação tópica.<br>
Infeção dos locais tratados que pode necessitar de ciclos prolongados de antibióticos sistémicos.<br>
Nódulos e endurecimentos permanentes podem ocorrer e necessitar de procedimentos cirúrgicos para completa resolução.<br>
Assimetrias, que podem ser mais notórias em determinadas zonas da face, e que poderão necessitar de meses para estabilizar ou mesmo de tratamentos adicionais.<br>
Alteração da pigmentação na zona do tratamento em casos raros.<br>
Uma reação alérgica ou de hipersensibilidade é sempre uma possibilidade, embora extremamente rara, tal como em qualquer outra forma de administração de medicamentos. Poderá necessitar de intervenções médicas emergentes e de evacuação hospitalar.<br>
Reativação de herpes simples ou de herpes zóster sendo necessário intervenção anti-herpética.<br>
Ausência de efeito dos fármacos utilizados que se pode dever a circunstâncias individuais inerentes à especificidade do corpo humano e que é impossível de prever.
&nbsp;</p>
<p style="margin:0cm;text-align:justify;"><u>Autorização</u><br>
Fui informado(a) e entendi que o tratamento Beauty Flash® implica riscos. Se surgir alguma complicação imediata dou o meu consentimento para que se faça o que for mais conveniente.<br>
Fui informado(a) da necessidade de evitar praia, sauna, piscina e qualquer outro tipo de exposição solar até 48 horas depois do tratamento, assim como da necessidade de evitar exercício físico nas primeiras 24 horas. <br>
Fui informado(a) da necessidade de dar sempre conhecimento ao médico da medicação que faço e da mera possibilidade de em qualquer altura estar grávida.<br>
Fui informado(a) de que a possibilidade de surgirem reações alérgicas ou de hipersensibilidade é a mesma que existe em qualquer outra via de administração de fármacos.<br>
Fui informado(a) do direito que tenho de aceitar ou não o procedimento, bem como do direito de anular a aceitação prévia das possibilidades de êxito do tratamento.<br>
Reconheço que não me podem ser dadas garantias ou segurança absoluta acerca do resultado do tratamento e que as minhas perguntas neste sentido foram satisfatoriamente respondidas. Sei que posso colocar reservas ou condições particulares em relação ao tratamento e foi-me dada oportunidade para tal.<br>
Autorizo o médico a administrar os fármacos necessários para o meu tratamento assumindo todas as consequências daí resultante.<br>
Autorizo a obtenção de documentos fotográficos necessários para o adequado cumprimento didático e científico sendo preservada a sua identidade e privacidade.<br>
Tudo o exposto me foi claramente explicado e aceito o tratamento proposto, estando consciente das possibilidades de êxito e das possíveis complicações pelo que assino, em sinal de acordo, de aceitação e de entendimento, este documento.<br>
Fui informado (a) que em caso de necessidade ou dúvida posso contactar o médico 24 horas por dia através de um contacto que me foi fornecido.<br> 
Dou o meu consentimento a que me seja realizado o tratamento Beauty Flash<sup>®</sup>
</p>
<br>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da segunda página do PDF
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">AUTORIZAÇÃO PARA O TRATAMENTO BEAUTY FLASH<sup>®</sup></span></h1>
<p style="margin:0cm;text-align:justify;">Declaro que foi por minha iniciativa que recorri à consulta médica por sentir necessidade de diagnosticar e tratar uma condição física e psicológica que me perturba.<br>
Reconheço que a intervenção acima designada me foi proposta após me ter sido realizado um diagnóstico médico detalhado, concreto e rigoroso, com o qual concordo e no qual me revejo.<br>
Reconheço que a intervenção acima designada se destina a tratar uma situação física e psíquica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá ajudar a prevenir o agravamento da situação física e psicológica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá contribuir para a cura da situação física e psicológica de que padeço e me foi diagnosticada.<br>
Reconheço que a intervenção se destina a melhorar a minha autoconfiança e restabelecer o meu bem-estar físico, mental e social.<br>
Reconheço que a intervenção proposta se destina, portanto, a restabelecer, proteger e manter a minha saúde física e mental.<br>
Reconheço que a intervenção acima designada é efetuada numa clínica médica com todas as condições, por um licenciado em medicina inscrito na ordem dos médicos portugueses, que me certifiquei que possui preparação e formação para a executar.<br>
</p><br>
<p>&nbsp;</p>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na segunda página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da terceira página do PDF
$html = '
<p style="margin:0cm;text-align:right;"><span style="font-size:16px;font-weight:bold;">DIAGNÓSTICO MÉDICO PARA O TRATAMENTO BEAUTY FLASH<sup>®</sup></span></p>
<span style="text-align:left">
<p>Diagn&oacute;stico m&eacute;dico conforme ICD 10 (Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de)</p>
<ul>
<li>H02 - Outras perturba&ccedil;&otilde;es da p&aacute;lpebra</li>
<li>H0230 - Blefarocal&aacute;sia em olho n&atilde;o especificado, p&aacute;lpebra n&atilde;o especificada</li>
<li>H024 &ndash; Ptose da p&aacute;lpebra</li>
<li>L568 &ndash; Outras altera&ccedil;&otilde;es agudas especificadas da pele devidas a radia&ccedil;&atilde;o ultravioleta</li>
<li>L574 &ndash; Cutis laxa associada &aacute; idade</li>
<li>L814 &ndash; Outras formas de hiperpigmenta&ccedil;&atilde;o pela melanina</li>
<li>L85 &ndash; Outro espessamento epid&eacute;rmico</li>
<li>L853 &ndash; Xerose cut&acirc;nea</li>
<li>L90 &ndash; Perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L908 &ndash; Outras perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L909 &ndash; Perturba&ccedil;&atilde;o atr&oacute;ficas da pele sem outra especifica&ccedil;&atilde;o</li>
<li>L987 &ndash; Pele e tecido subcut&acirc;neo excessivo e redundante</li>
<li>L989 &ndash; Afe&ccedil;&otilde;es da pele e do tecido subcut&acirc;neo, n&atilde;o especificadas</li>
</ul>
<p>Que originam e causam</p>
<ul>
<li>Sintomas depressivos</li>
<li>Ansiedade</li>
<li>Ins&oacute;nia e/ou perturba&ccedil;&otilde;es do sono</li>
<li>Irritabilidade</li>
<li>Dificuldade de desempenhar as atividades habituais</li>
<li>Baixa vitalidade, energia e tranquilidade</li>
<li>Estado de infelicidade</li>
<li>Incapacidade de responder &agrave;s adversidades</li>
<li>Perturba&ccedil;&atilde;o das rela&ccedil;&otilde;es com os demais</li>
<li>Dificuldade em sentir-se bem consigo pr&oacute;prio</li>
<li>Aus&ecirc;ncia de um estado de completo bem-estar f&iacute;sico, mental e social</li>
</ul>
<p>O que conforme a ICD 10(Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de) :</p>
<ul>
<li>F39 &ndash; Perturba&ccedil;&atilde;o do humor [afetivo], sem outra especifica&ccedil;&atilde;o</li>
<li>F419 &ndash; Estado de ansiedade, sem outra especifica&ccedil;&atilde;o</li>
<li>F51 &ndash; Transtornos n&atilde;o-org&acirc;nicos do sono devidos a fatores emocionais</li>
<li>F518 &ndash; Outros transtornos do sono devidos a fatores n&atilde;o-org&acirc;nicos</li>
</ul>
    </span>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
';

// Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="70">
        </td>
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="70">
        </td>
    </tr>
</table>';


// Adiciona o conteúdo na terceira página
$pdf->writeHTML($html, true, false, true, false, '');

// Verifica se o consentimento de imagem foi removido
$remover_consentimento = isset($_POST["remover_consentimento_imagem"]) && $_POST["remover_consentimento_imagem"] == '1';

// Adiciona uma nova página para consentimento de imagem SE o consentimento NÃO foi removido
if (!$remover_consentimento) {
    // Adiciona uma nova página
    $pdf->AddPage();

    // Define a fonte
    $pdf->SetFont('helvetica', '', 8);

    // Conteúdo da quarta página (Consentimento de Imagem)
    $html = '
    <label style="font-size:18px;" for="consentimento_imagem"><u>Consentimento de Imagem</u></label>
                            <p>' . htmlspecialchars($form_data["nome_completo"]) . '  <strong>DECLARA</strong>, para os devidos efeitos:</p>

<ol>
<li>Que, no &acirc;mbito do procedimento m&eacute;dico ' . htmlspecialchars($form_data["procedimento_type"]) . '<sup>&reg;</sup> realizado com Dr Vitor Figueiredo ou com outro m&eacute;dico sob a dire&ccedil;&atilde;o t&eacute;cnica e cl&iacute;nica daquele, existe capta&ccedil;&atilde;o de imagens (fotografia e/ou v&iacute;deo), antes e depois de ocorrer o procedimento m&eacute;dico, capta&ccedil;&atilde;o essa promovida pelo Dr,Vitor Figueiredo e da sua responsabilidade exclusiva, com a qual concorda e na qual expressamente consente.</li>
<li>Ter sido informado que quaisquer materiais resultantes da capta&ccedil;&atilde;o de imagens (doravante designados apenas por &ldquo;Materiais&rdquo;) ser&atilde;o utilizados, sem identifica&ccedil;&atilde;o e sob reserva de confidencialidade a todo o tempo, para fins did&aacute;ticos, formativos e cient&iacute;ficos, bem como para fins publicit&aacute;rios ou comerciais, em qualquer meio de difus&atilde;o e comunica&ccedil;&atilde;o interno ou externo, a n&iacute;vel nacional ou internacional, atrav&eacute;s de quaisquer canais (incluindo digitais), nomeadamente televis&atilde;o, imprensa escrita, internet, redes sociais e outros existentes ou que venham a existir.</li>
<li>Conceder ao Dr. Vitor Manuel Figueiredo uma autoriza&ccedil;&atilde;o expressa, gratuita e por 10 (dez) anos para utiliza&ccedil;&atilde;o, por qualquer forma, e comunica&ccedil;&atilde;o ao p&uacute;blico, dos Materiais para os fins e atrav&eacute;s dos meios acima referidos, com vista &agrave; sua explora&ccedil;&atilde;o a n&iacute;vel mundial, sem limita&ccedil;&otilde;es de nenhum tipo e sem necessidade de obter nenhum consentimento ou autoriza&ccedil;&atilde;o posterior em rela&ccedil;&atilde;o ao uso que se fa&ccedil;a dos mesmos.</li>
<li>Conceder &agrave; Ready Point, Lda, que usa comercialmente a designa&ccedil;&atilde;o &ldquo;Ageless&ndash;Anti Aging Center&rdquo; (doravante &ldquo;Ageless&rdquo;), uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., a qual caducar&aacute; necess&aacute;ria e automaticamente se e quando o Dr. Vitor Manuel Figueiredo deixar de ser s&oacute;cio ou de integrar profissionalmente (o que primeiro ocorrer) tal sociedade, a qual deixar&aacute; de poder utilizar os Materiais. Para clarifica&ccedil;&atilde;o, a refer&ecirc;ncia feita neste documento a &ldquo;Ageless&rdquo; reporta-se exclusivamente &agrave; Ready Point, Lda.</li>
<li>Os Materiais podem ser utilizados no &acirc;mbito de qualquer parceria que a Ready Point Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Conceder &agrave; Global Metik Lda Lda. uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., podendo os Materiais ser utilizados no &acirc;mbito de qualquer parceria que a Global Metik, Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Que est&aacute; ciente do seu direito, nos termos do C&oacute;digo Civil, de revogar esta autoriza&ccedil;&atilde;o a todo o tempo, ainda que com obriga&ccedil;&atilde;o de indemnizar os danos e preju&iacute;zos causados a qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, tendo em conta os investimentos efetuados por estes, neles se incluindo, nomeadamente e sem excluir, os custos de remo&ccedil;&atilde;o, se poss&iacute;vel, da sua imagem dos Materiais ou de destrui&ccedil;&atilde;o dos Materiais;</li>
<li>N&atilde;o ceder futuramente, total ou parcialmente, os direitos aqui mencionados a qualquer outra pessoa f&iacute;sica ou jur&iacute;dica, de modo impeditivo ou que, de qualquer forma, interfira com os direitos acima concedidos;</li>
<li>Eximir qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, de todas as responsabilidades que possam resultar do exerc&iacute;cio dos direitos aqui concedidos e indemniz&aacute;-los por todos os danos resultantes do incumprimento dos compromissos aqui obtidos ou da inexatid&atilde;o das declara&ccedil;&otilde;es efetuadas;</li>
<li>Relativamente ao tratamento do seu dado pessoal imagem, ter sido informado que tal tratamento ser&aacute; da responsabilidade do Dr. Vitor Manuel Figueiredo, da Ageless ou da Global Metik, Lda., cada um enquanto respons&aacute;vel independente pelo tratamento de dados, ser&aacute; realizado para as finalidades acima indicadas e, ainda, que:</li>
<li>O tratamento do dado pessoal imagem n&atilde;o constitui uma obriga&ccedil;&atilde;o legal ou contratual, n&atilde;o estando o paciente obrigado a fornecer a sua imagem e n&atilde;o havendo quaisquer consequ&ecirc;ncias caso n&atilde;o o forne&ccedil;a;</li>
<li>As imagens do paciente ser&atilde;o mantidas pelo per&iacute;odo que se revelar estritamente necess&aacute;rio tendo em considera&ccedil;&atilde;o as finalidades supra indicadas.</li>
<li>Os dados pessoais utilizados no &acirc;mbito das parcerias poder&atilde;o ser transmitidos aos parceiros. Sem preju&iacute;zo, os dados poder&atilde;o ser acedidos por terceiros no &acirc;mbito de presta&ccedil;&otilde;es de servi&ccedil;os de tecnologias de informa&ccedil;&atilde;o ou outras, sendo que tais terceiros tratar&atilde;o os dados em nome do respons&aacute;vel pelo tratamento e de acordo com instru&ccedil;&otilde;es do mesmo.</li>
<li>Pode, a qualquer momento, retirar o seu consentimento para a capta&ccedil;&atilde;o e utiliza&ccedil;&atilde;o de imagens, sem que tal comprometa, no entanto, a licitude do tratamento realizado com base no consentimento previamente prestado.</li>
<li>Tem o direito de acesso, retifica&ccedil;&atilde;o, apagamento, portabilidade, limita&ccedil;&atilde;o e oposi&ccedil;&atilde;o ao tratamento dos seus dados pessoais e de retirar o consentimento, podendo exercer qualquer destes direitos mediante pedido escrito para Ageless, Via do Oriente, Lote 8, 5.03.01C, Escrit&oacute;rios 1,2,3,4, Edif&iacute;cio Tibre, 1990 - 514 Lisboa. Tem igualmente direito de apresentar uma reclama&ccedil;&atilde;o &agrave; Comiss&atilde;o Nacional de Prote&ccedil;&atilde;o de Dados (www.cnpd.pt).</li>
</ol>
<p>&nbsp;</p>
    <p><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
    <p><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
    <p><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
    <p><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
    ';

    // Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="50">
        </td>';
        
if ($medico_signature_file) {
    $html .= '
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="50">
        </td>';
}

$html .= '
    </tr>
</table>';


    // Adiciona o conteúdo na quarta página
    $pdf->writeHTML($html, true, false, true, false, '');
}
                break;
        
case 'CI-hialoestrutura':
                 // Adiciona uma página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo do PDF com o conteúdo e identificadores dinâmicos
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">Consentimento informado para o tratamento HIALOESTRUTURA<sup>®</sup></span></h1>
<p style="margin-left:0cm;">&nbsp;</p>
<p style="margin:0cm;text-align:justify;"><u>O que é o tratamento HIALOESTRUTURA</u><sup>®</sup>?
<br>A HIALOESTRUTURA<sup>®</sup> consiste na aplicação local, no interior da pele, com recurso a agulhas e outros dispositivos, de uma mistura de princípios ativos, permitindo que cheguem diretamente aos locais onde são necessários. 
Pode ser feito por qualquer pessoa em qualquer altura do ano, exceto durante a gravidez ou aleitamento.
</p><br>
<p style="margin:0cm;text-align:justify;"><u>Efeitos secundários</u><br>
Hematomas dependendo da zona a tratar, da toma de certos medicamentos (por exemplo aspirina e derivados) e das características de cada pessoa. Podem demorar vários dias a desaparecer. <br>
Dor ou desconforto espontâneos ou ao toque nas zonas tratadas que podem permanecer alguns dias e necessitar de analgesia oral.<br>
Edema das regiões tratadas que pode surgir subitamente ou nos dias seguintes e necessitar de corticoterapia oral ou sistêmica.<br>
Rubor (vermelhidão) que pode permanecer alguns dias e necessitar de medicação tópica.<br>
Infeção dos locais tratados que pode necessitar de ciclos prolongados de antibióticos sistémicos.<br>
Nódulos e endurecimentos permanentes podem ocorrer e necessitar de procedimentos cirúrgicos para completa resolução.<br>
Assimetrias que podem ser mais notórias em determinadas zonas da face e que poderão necessitar de meses para estabilizar ou mesmo de tratamentos adicionais.<br>
Alteração da pigmentação na zona do tratamento em casos raros.<br>
Uma reação alérgica ou de hipersensibilidade é sempre uma possibilidade, embora extremamente rara, tal como em qualquer outra forma de administração de medicamentos.<br>
Poderá necessitar de intervenções médicas emergentes e de evacuação hospitalar.<br>
Reativação de herpes simples ou de herpes zóster sendo necessário intervenção anti-herpética.<br>
Ausência de efeito dos fármacos utilizados que se pode dever a circunstâncias individuais inerentes à especificidade do corpo humano e que é impossível de prever.
&nbsp;</p>
<p style="margin:0cm;text-align:justify;">&nbsp;</p>
<p style="margin:0cm;text-align:justify;"><u>Autorização</u><br>
Fui informada(o) e entendi que a HIALOESTRUTURA<sup>®</sup> implica riscos. Se surgir alguma complicação imediata dou o meu consentimento para que se faça o que seja mais conveniente.<br>
Fui informada(o) da necessidade de evitar praia, sauna, piscina e qualquer outro tipo de exposição solar até 48 horas depois do tratamento, assim como da necessidade de evitar exercício físico nas primeiras 24 horas. <br>
Fui informada(o) da necessidade de dar sempre conhecimento ao Médico da medicação que faço e da mera possibilidade de em qualquer altura estar grávida.<br>
Fui informada(o) de que a possibilidade de surgirem reações alérgicas ou de hipersensibilidade é a mesma que existe em qualquer outra via de administração de fármacos.<br>
Fui informada(o) do direito que tenho de aceitar ou não o procedimento, bem como do direito de anular a aceitação prévia das possibilidades de êxito do tratamento. <br>
Reconheço que não me podem ser dadas garantias ou segurança absoluta acerca do resultado do tratamento e que as minhas perguntas neste sentido foram satisfatoriamente respondidas.<br> 
Sei que posso colocar reservas ou condições particulares em relação ao tratamento e foi-me dada oportunidade para tal.<br>
Autorizo o médico a administrar os fármacos necessários para o meu tratamento assumindo todas as consequências daí resultantes.<br>
Autorizo a obtenção de documentos fotográficos necessários para o adequado cumprimento didático e científico sendo preservada a sua identidade e privacidade.<br>
Tudo o exposto me foi claramente explicado e aceito o tratamento proposto, estando consciente das possibilidades de êxito e das possíveis complicações pelo que assino, em sinal de acordo, de aceitação e de entendimento, este documento.<br>
Fui informada (o) que em caso de necessidade ou dúvida posso contactar o médico 24 horas por dia através de um contacto que me foi fornecido.<br>
Dou o meu consentimento para que me seja realizada a HIALOESTRUTURA<sup>®</sup>
</p>
<br>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da segunda página do PDF
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">AUTORIZAÇÃO PARA A REALIZAÇÃO DE HIALOESTRUTURA<sup>®</sup></span></h1>
<p style="margin-left:0cm;">&nbsp;</p>
<p style="margin:0cm;text-align:justify;">Declaro que foi por minha iniciativa que recorri à consulta médica por sentir necessidade de diagnosticar e tratar uma condição física e psicológica que me perturba.<br>
Reconheço que a intervenção acima designada me foi proposta após me ter sido realizado um diagnóstico médico detalhado, concreto e rigoroso, com o qual concordo e no qual me revejo.<br>
Reconheço que a intervenção acima designada se destina a tratar uma situação física e psíquica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá ajudar a prevenir o agravamento da situação física e psicológica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá contribuir para a cura da situação física e psicológica de que padeço e me foi diagnosticada.<br>
Reconheço que a intervenção se destina a melhorar a minha autoconfiança e restabelecer o meu bem-estar físico, mental e social.<br>
Reconheço que a intervenção proposta se destina, portanto, a restabelecer, proteger e manter a minha saúde física e mental.<br>
Reconheço que a intervenção acima designada é efetuada numa clínica médica com todas as condições, por um licenciado em medicina inscrito na ordem dos médicos portugueses, que me certifiquei que possui preparação e formação para a executar.<br>
</p>
<br>
<p>&nbsp;</p>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na segunda página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da terceira página do PDF
$html = '
<p style="margin:0cm;text-align:right;"><span style="font-size:16px;font-weight:bold;">DIAGNÓSTICO MÉDICO PARA O TRATAMENTO HIALOESTRUTURA<sup>®</sup></span></p>
<span style="text-align:left">
<p>Diagn&oacute;stico m&eacute;dico conforme ICD 10 (Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de)</p>
<ul>
<li>H02 - Outras perturba&ccedil;&otilde;es da p&aacute;lpebra</li>
<li>H0230 - Blefarocal&aacute;sia em olho n&atilde;o especificado, p&aacute;lpebra n&atilde;o especificada</li>
<li>H024 &ndash; Ptose da p&aacute;lpebra</li>
<li>L568 &ndash; Outras altera&ccedil;&otilde;es agudas especificadas da pele devidas a radia&ccedil;&atilde;o ultravioleta</li>
<li>L574 &ndash; Cutis laxa associada &aacute; idade</li>
<li>L814 &ndash; Outras formas de hiperpigmenta&ccedil;&atilde;o pela melanina</li>
<li>L85 &ndash; Outro espessamento epid&eacute;rmico</li>
<li>L853 &ndash; Xerose cut&acirc;nea</li>
<li>L90 &ndash; Perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L908 &ndash; Outras perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L909 &ndash; Perturba&ccedil;&atilde;o atr&oacute;ficas da pele sem outra especifica&ccedil;&atilde;o</li>
<li>L987 &ndash; Pele e tecido subcut&acirc;neo excessivo e redundante</li>
<li>L989 &ndash; Afe&ccedil;&otilde;es da pele e do tecido subcut&acirc;neo, n&atilde;o especificadas</li>
</ul>

<p>Que originam e causam</p>
<ul>
<li>Sintomas depressivos</li>
<li>Ansiedade</li>
<li>Ins&oacute;nia e/ou perturba&ccedil;&otilde;es do sono</li>
<li>Irritabilidade</li>
<li>Dificuldade de desempenhar as atividades habituais</li>
<li>Baixa vitalidade, energia e tranquilidade</li>
<li>Estado de infelicidade</li>
<li>Incapacidade de responder &agrave;s adversidades</li>
<li>Perturba&ccedil;&atilde;o das rela&ccedil;&otilde;es com os demais</li>
<li>Dificuldade em sentir-se bem consigo pr&oacute;prio</li>
<li>Aus&ecirc;ncia de um estado de completo bem-estar f&iacute;sico, mental e social</li>
</ul>
<p>O que conforme a ICD 10(Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de) :</p>
<ul>
<li>F39 &ndash; Perturba&ccedil;&atilde;o do humor [afetivo], sem outra especifica&ccedil;&atilde;o</li>
<li>F419 &ndash; Estado de ansiedade, sem outra especifica&ccedil;&atilde;o</li>
<li>F51 &ndash; Transtornos n&atilde;o-org&acirc;nicos do sono devidos a fatores emocionais</li>
<li>F518 &ndash; Outros transtornos do sono devidos a fatores n&atilde;o-org&acirc;nicos</li>
</ul>
    </span>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
';

// Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="70">
        </td>
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="70">
        </td>
    </tr>
</table>';


// Adiciona o conteúdo na terceira página
$pdf->writeHTML($html, true, false, true, false, '');

// Verifica se o consentimento de imagem foi removido
$remover_consentimento = isset($_POST["remover_consentimento_imagem"]) && $_POST["remover_consentimento_imagem"] == '1';

// Adiciona uma nova página para consentimento de imagem SE o consentimento NÃO foi removido
if (!$remover_consentimento) {
    // Adiciona uma nova página
    $pdf->AddPage();

    // Define a fonte
    $pdf->SetFont('helvetica', '', 8);

    // Conteúdo da quarta página (Consentimento de Imagem)
    $html = '
    <label style="font-size:18px;" for="consentimento_imagem"><u>Consentimento de Imagem</u></label>
                            <p>' . htmlspecialchars($form_data["nome_completo"]) . '  <strong>DECLARA</strong>, para os devidos efeitos:</p>
<p>&nbsp;</p>
<ol>
<li>Que, no &acirc;mbito do procedimento m&eacute;dico ' . htmlspecialchars($form_data["procedimento_type"]) . '<sup>&reg;</sup> realizado com Dr Vitor Figueiredo ou com outro m&eacute;dico sob a dire&ccedil;&atilde;o t&eacute;cnica e cl&iacute;nica daquele, existe capta&ccedil;&atilde;o de imagens (fotografia e/ou v&iacute;deo), antes e depois de ocorrer o procedimento m&eacute;dico, capta&ccedil;&atilde;o essa promovida pelo Dr,Vitor Figueiredo e da sua responsabilidade exclusiva, com a qual concorda e na qual expressamente consente.</li>
<li>Ter sido informado que quaisquer materiais resultantes da capta&ccedil;&atilde;o de imagens (doravante designados apenas por &ldquo;Materiais&rdquo;) ser&atilde;o utilizados, sem identifica&ccedil;&atilde;o e sob reserva de confidencialidade a todo o tempo, para fins did&aacute;ticos, formativos e cient&iacute;ficos, bem como para fins publicit&aacute;rios ou comerciais, em qualquer meio de difus&atilde;o e comunica&ccedil;&atilde;o interno ou externo, a n&iacute;vel nacional ou internacional, atrav&eacute;s de quaisquer canais (incluindo digitais), nomeadamente televis&atilde;o, imprensa escrita, internet, redes sociais e outros existentes ou que venham a existir.</li>
<li>Conceder ao Dr. Vitor Manuel Figueiredo uma autoriza&ccedil;&atilde;o expressa, gratuita e por 10 (dez) anos para utiliza&ccedil;&atilde;o, por qualquer forma, e comunica&ccedil;&atilde;o ao p&uacute;blico, dos Materiais para os fins e atrav&eacute;s dos meios acima referidos, com vista &agrave; sua explora&ccedil;&atilde;o a n&iacute;vel mundial, sem limita&ccedil;&otilde;es de nenhum tipo e sem necessidade de obter nenhum consentimento ou autoriza&ccedil;&atilde;o posterior em rela&ccedil;&atilde;o ao uso que se fa&ccedil;a dos mesmos.</li>
<li>Conceder &agrave; Ready Point, Lda, que usa comercialmente a designa&ccedil;&atilde;o &ldquo;Ageless&ndash;Anti Aging Center&rdquo; (doravante &ldquo;Ageless&rdquo;), uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., a qual caducar&aacute; necess&aacute;ria e automaticamente se e quando o Dr. Vitor Manuel Figueiredo deixar de ser s&oacute;cio ou de integrar profissionalmente (o que primeiro ocorrer) tal sociedade, a qual deixar&aacute; de poder utilizar os Materiais. Para clarifica&ccedil;&atilde;o, a refer&ecirc;ncia feita neste documento a &ldquo;Ageless&rdquo; reporta-se exclusivamente &agrave; Ready Point, Lda.</li>
<li>Os Materiais podem ser utilizados no &acirc;mbito de qualquer parceria que a Ready Point Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Conceder &agrave; Global Metik Lda Lda. uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., podendo os Materiais ser utilizados no &acirc;mbito de qualquer parceria que a Global Metik, Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Que est&aacute; ciente do seu direito, nos termos do C&oacute;digo Civil, de revogar esta autoriza&ccedil;&atilde;o a todo o tempo, ainda que com obriga&ccedil;&atilde;o de indemnizar os danos e preju&iacute;zos causados a qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, tendo em conta os investimentos efetuados por estes, neles se incluindo, nomeadamente e sem excluir, os custos de remo&ccedil;&atilde;o, se poss&iacute;vel, da sua imagem dos Materiais ou de destrui&ccedil;&atilde;o dos Materiais;</li>
<li>N&atilde;o ceder futuramente, total ou parcialmente, os direitos aqui mencionados a qualquer outra pessoa f&iacute;sica ou jur&iacute;dica, de modo impeditivo ou que, de qualquer forma, interfira com os direitos acima concedidos;</li>
<li>Eximir qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, de todas as responsabilidades que possam resultar do exerc&iacute;cio dos direitos aqui concedidos e indemniz&aacute;-los por todos os danos resultantes do incumprimento dos compromissos aqui obtidos ou da inexatid&atilde;o das declara&ccedil;&otilde;es efetuadas;</li>
<li>Relativamente ao tratamento do seu dado pessoal imagem, ter sido informado que tal tratamento ser&aacute; da responsabilidade do Dr. Vitor Manuel Figueiredo, da Ageless ou da Global Metik, Lda., cada um enquanto respons&aacute;vel independente pelo tratamento de dados, ser&aacute; realizado para as finalidades acima indicadas e, ainda, que:</li>
<li>O tratamento do dado pessoal imagem n&atilde;o constitui uma obriga&ccedil;&atilde;o legal ou contratual, n&atilde;o estando o paciente obrigado a fornecer a sua imagem e n&atilde;o havendo quaisquer consequ&ecirc;ncias caso n&atilde;o o forne&ccedil;a;</li>
<li>As imagens do paciente ser&atilde;o mantidas pelo per&iacute;odo que se revelar estritamente necess&aacute;rio tendo em considera&ccedil;&atilde;o as finalidades supra indicadas.</li>
<li>Os dados pessoais utilizados no &acirc;mbito das parcerias poder&atilde;o ser transmitidos aos parceiros. Sem preju&iacute;zo, os dados poder&atilde;o ser acedidos por terceiros no &acirc;mbito de presta&ccedil;&otilde;es de servi&ccedil;os de tecnologias de informa&ccedil;&atilde;o ou outras, sendo que tais terceiros tratar&atilde;o os dados em nome do respons&aacute;vel pelo tratamento e de acordo com instru&ccedil;&otilde;es do mesmo.</li>
<li>Pode, a qualquer momento, retirar o seu consentimento para a capta&ccedil;&atilde;o e utiliza&ccedil;&atilde;o de imagens, sem que tal comprometa, no entanto, a licitude do tratamento realizado com base no consentimento previamente prestado.</li>
<li>Tem o direito de acesso, retifica&ccedil;&atilde;o, apagamento, portabilidade, limita&ccedil;&atilde;o e oposi&ccedil;&atilde;o ao tratamento dos seus dados pessoais e de retirar o consentimento, podendo exercer qualquer destes direitos mediante pedido escrito para Ageless, Via do Oriente, Lote 8, 5.03.01C, Escrit&oacute;rios 1,2,3,4, Edif&iacute;cio Tibre, 1990 - 514 Lisboa. Tem igualmente direito de apresentar uma reclama&ccedil;&atilde;o &agrave; Comiss&atilde;o Nacional de Prote&ccedil;&atilde;o de Dados (www.cnpd.pt).</li>
</ol>
    <p><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
    <p><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
    <p><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
    <p><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
    ';

    // Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="50">
        </td>';
        
if ($medico_signature_file) {
    $html .= '
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="50">
        </td>';
}

$html .= '
    </tr>
</table>';


    // Adiciona o conteúdo na quarta página
    $pdf->writeHTML($html, true, false, true, false, '');
}
                break;
case 'CI-lipolise':
                 // Adiciona uma página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo do PDF com o conteúdo e identificadores dinâmicos
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;font-weight:bold;">Consentimento informado para o tratamento Lipólise</span></h1>
<p style="margin:0cm;text-align:justify;"><u>O que é o tratamento Lipólise </u>?
<br>A Lipólise consiste na aplicação local, no interior dos tecidos, com recurso a agulhas e outros dispositivos, de uma mistura de princípios ativos, permitindo que cheguem, assim, diretamente aos locais onde são necessários. Atuam, pois, localmente e com diferentes finalidades como a redução da gordura, a melhoria da celulite e/ou ainda o controlo da flacidez. 
São necessárias várias sessões e os resultados são progressivos. Pode ser necessário seguir um plano alimentar adaptado.
Pode ser feito por qualquer pessoa, se não tiver contra-indicações e em qualquer altura do ano, exceto durante a gravidez ou aleitamento.
</p><br>
<p style="margin:0cm;text-align:justify;"><u>Efeitos secundários</u><br>
Hematomas dependendo da zona a tratar, da toma de certos medicamentos (por exemplo aspirina e derivados) e das características de cada pessoa. Podem demorar vários dias a desaparecer. <br>
Dor ou desconforto espontâneos ou ao toque nas zonas tratadas que podem permanecer alguns dias e necessitar de analgesia oral.<br>
Edema das regiões tratadas que pode surgir subitamente ou nos dias seguintes e necessitar de corticoterapia oral ou sistêmica.<br>
Rubor (vermelhidão) que pode permanecer alguns dias e necessitar de medicação tópica.<br>
Infeção dos locais tratados que pode necessitar de ciclos prolongados de antibióticos sistémicos.<br>
Nódulos e endurecimentos permanentes podem ocorrer e necessitar de procedimentos cirúrgicos para completa resolução.<br>
Assimetrias que podem ser mais notórias em determinadas zonas da face e que poderão necessitar de meses para estabilizar ou mesmo de tratamentos adicionais.<br>
Alteração da pigmentação na zona do tratamento em casos raros.<br>
Uma reação alérgica ou de hipersensibilidade é sempre uma possibilidade, embora extremamente rara, tal como em qualquer outra forma de administração de medicamentos. Poderá necessitar de intervenções médicas emergentes e de evacuação hospitalar.<br>
Reativação de herpes simples ou de herpes zóster sendo necessário intervenção anti-herpética.<br>
Ausência completa de efeito dos fármacos utilizados que se pode dever a circunstâncias individuais inerentes à especificidade do corpo humano e que é impossível de prever
&nbsp;</p>
<p style="margin:0cm;text-align:justify;"><u>Autorização</u><br>
Fui informada(o) e entendi que a lipólise implica riscos. Se surgir alguma complicação imediata dou o meu consentimento para que se faça o que seja mais conveniente.<br>
Fui informada(o) da necessidade de evitar praia, sauna, piscina e qualquer outro tipo de exposição solar até 48 horas depois do tratamento, assim como da necessidade de evitar exercício físico nas primeiras 24 horas. <br>
Fui informada(o) da necessidade de dar sempre conhecimento ao Médico da medicação que faço e da mera possibilidade de em qualquer altura estar grávida.<br>
Fui informada(o) de que a possibilidade de surgirem reações alérgicas ou de hipersensibilidade é a mesma que existe em qualquer outra via de administração de fármacos.<br>
Fui informada(o) do direito que tenho de aceitar ou não o procedimento, bem como do direito de anular a aceitação prévia das possibilidades de êxito do tratamento. Reconheço que não me podem ser dadas garantias ou segurança absoluta acerca do resultado do tratamento e que as minhas perguntas neste sentido foram satisfatoriamente respondidas. Sei que posso colocar reservas ou condições particulares em relação ao tratamento e foi-me dada oportunidade para tal.<br>
Autorizo o médico a administrar os fármacos necessários para o meu tratamento assumindo todas as consequências daí resultantes.<br>
Autorizo a obtenção de documentos fotográficos necessários para o adequado cumprimento didático e científico sendo preservada a sua identidade e privacidade.<br>
Tudo o exposto me foi claramente explicado e aceito o tratamento proposto, estando consciente das possibilidades de êxito e das possíveis complicações pelo que assino, em sinal de acordo, de aceitação e de entendimento, este documento.<br>
Fui informada (o) que em caso de necessidade ou dúvida posso contactar o médico 24 horas por dia através de um contacto que me foi fornecido.<br>
Dou o meu consentimento para que me seja realizada a lipólise.
</p>
<br>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da segunda página do PDF
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">AUTORIZAÇÃO PARA A REALIZAÇÃO DE LIPÓLISE</span></h1>
<p style="margin:0cm;text-align:justify;">Declaro que foi por minha iniciativa que recorri à consulta médica por sentir necessidade de diagnosticar e tratar uma condição física e psicológica que me perturba.<br>
Reconheço que a intervenção acima designada me foi proposta após me ter sido realizado um diagnóstico médico detalhado, concreto e rigoroso, com o qual concordo e no qual me revejo.<br>
Reconheço que a intervenção acima designada se destina a tratar uma situação física e psíquica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá ajudar a prevenir o agravamento da situação física e psicológica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá contribuir para a cura da situação física e psicológica de que padeço e me foi diagnosticada.<br>
Reconheço que a intervenção se destina a melhorar a minha autoconfiança e restabelecer o meu bem-estar físico, mental e social.<br>
Reconheço que a intervenção proposta se destina, portanto, a restabelecer, proteger e manter a minha saúde física e mental.<br>
Reconheço que a intervenção acima designada é efetuada numa clínica médica com todas as condições, por um licenciado em medicina inscrito na ordem dos médicos portugueses, que me certifiquei que possui preparação e formação para a executar.<br>
</p><br>
<p>&nbsp;</p>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na segunda página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da terceira página do PDF
$html = '
<p style="margin:0cm;text-align:right;"><span style="font-size:16px;font-weight:bold;">DIAGNÓSTICO MÉDICO PARA O TRATAMENTO LIPÓLISE</span></p>
<span style="text-align:left">
<p>Diagn&oacute;stico m&eacute;dico conforme ICD 10 (Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de)</p>
<ul>
<li>H02 - Outras perturba&ccedil;&otilde;es da p&aacute;lpebra</li>
<li>H0230 - Blefarocal&aacute;sia em olho n&atilde;o especificado, p&aacute;lpebra n&atilde;o especificada</li>
<li>H024 &ndash; Ptose da p&aacute;lpebra</li>
<li>L568 &ndash; Outras altera&ccedil;&otilde;es agudas especificadas da pele devidas a radia&ccedil;&atilde;o ultravioleta</li>
<li>L574 &ndash; Cutis laxa associada &aacute; idade</li>
<li>L814 &ndash; Outras formas de hiperpigmenta&ccedil;&atilde;o pela melanina</li>
<li>L85 &ndash; Outro espessamento epid&eacute;rmico</li>
<li>L853 &ndash; Xerose cut&acirc;nea</li>
<li>L90 &ndash; Perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L908 &ndash; Outras perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L909 &ndash; Perturba&ccedil;&atilde;o atr&oacute;ficas da pele sem outra especifica&ccedil;&atilde;o</li>
<li>L987 &ndash; Pele e tecido subcut&acirc;neo excessivo e redundante</li>
<li>L989 &ndash; Afe&ccedil;&otilde;es da pele e do tecido subcut&acirc;neo, n&atilde;o especificadas</li>
</ul>
<p>&nbsp;</p>
<p>Que originam e causam</p>
<ul>
<li>Sintomas depressivos</li>
<li>Ansiedade</li>
<li>Ins&oacute;nia e/ou perturba&ccedil;&otilde;es do sono</li>
<li>Irritabilidade</li>
<li>Dificuldade de desempenhar as atividades habituais</li>
<li>Baixa vitalidade, energia e tranquilidade</li>
<li>Estado de infelicidade</li>
<li>Incapacidade de responder &agrave;s adversidades</li>
<li>Perturba&ccedil;&atilde;o das rela&ccedil;&otilde;es com os demais</li>
<li>Dificuldade em sentir-se bem consigo pr&oacute;prio</li>
<li>Aus&ecirc;ncia de um estado de completo bem-estar f&iacute;sico, mental e social</li>
</ul>
<p>O que conforme a ICD 10 (Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de) :</p>
<ul>
<li>F39 &ndash; Perturba&ccedil;&atilde;o do humor [afetivo], sem outra especifica&ccedil;&atilde;o</li>
<li>F419 &ndash; Estado de ansiedade, sem outra especifica&ccedil;&atilde;o</li>
<li>F51 &ndash; Transtornos n&atilde;o-org&acirc;nicos do sono devidos a fatores emocionais</li>
<li>F518 &ndash; Outros transtornos do sono devidos a fatores n&atilde;o-org&acirc;nicos</li>
</ul>
    </span>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
';

// Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="70">
        </td>
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="70">
        </td>
    </tr>
</table>';


// Adiciona o conteúdo na terceira página
$pdf->writeHTML($html, true, false, true, false, '');

// Verifica se o consentimento de imagem foi removido
$remover_consentimento = isset($_POST["remover_consentimento_imagem"]) && $_POST["remover_consentimento_imagem"] == '1';

// Adiciona uma nova página para consentimento de imagem SE o consentimento NÃO foi removido
if (!$remover_consentimento) {
    // Adiciona uma nova página
    $pdf->AddPage();

    // Define a fonte
    $pdf->SetFont('helvetica', '', 8);

    // Conteúdo da quarta página (Consentimento de Imagem)
    $html = '
    <label style="font-size:18px;" for="consentimento_imagem"><u>Consentimento de Imagem</u></label>
                            <p>' . htmlspecialchars($form_data["nome_completo"]) . '  <strong>DECLARA</strong>, para os devidos efeitos:</p>

<ol>
<li>Que, no &acirc;mbito do procedimento m&eacute;dico ' . htmlspecialchars($form_data["procedimento_type"]) . '<sup>&reg;</sup> realizado com Dr Vitor Figueiredo ou com outro m&eacute;dico sob a dire&ccedil;&atilde;o t&eacute;cnica e cl&iacute;nica daquele, existe capta&ccedil;&atilde;o de imagens (fotografia e/ou v&iacute;deo), antes e depois de ocorrer o procedimento m&eacute;dico, capta&ccedil;&atilde;o essa promovida pelo Dr,Vitor Figueiredo e da sua responsabilidade exclusiva, com a qual concorda e na qual expressamente consente.</li>
<li>Ter sido informado que quaisquer materiais resultantes da capta&ccedil;&atilde;o de imagens (doravante designados apenas por &ldquo;Materiais&rdquo;) ser&atilde;o utilizados, sem identifica&ccedil;&atilde;o e sob reserva de confidencialidade a todo o tempo, para fins did&aacute;ticos, formativos e cient&iacute;ficos, bem como para fins publicit&aacute;rios ou comerciais, em qualquer meio de difus&atilde;o e comunica&ccedil;&atilde;o interno ou externo, a n&iacute;vel nacional ou internacional, atrav&eacute;s de quaisquer canais (incluindo digitais), nomeadamente televis&atilde;o, imprensa escrita, internet, redes sociais e outros existentes ou que venham a existir.</li>
<li>Conceder ao Dr. Vitor Manuel Figueiredo uma autoriza&ccedil;&atilde;o expressa, gratuita e por 10 (dez) anos para utiliza&ccedil;&atilde;o, por qualquer forma, e comunica&ccedil;&atilde;o ao p&uacute;blico, dos Materiais para os fins e atrav&eacute;s dos meios acima referidos, com vista &agrave; sua explora&ccedil;&atilde;o a n&iacute;vel mundial, sem limita&ccedil;&otilde;es de nenhum tipo e sem necessidade de obter nenhum consentimento ou autoriza&ccedil;&atilde;o posterior em rela&ccedil;&atilde;o ao uso que se fa&ccedil;a dos mesmos.</li>
<li>Conceder &agrave; Ready Point, Lda, que usa comercialmente a designa&ccedil;&atilde;o &ldquo;Ageless&ndash;Anti Aging Center&rdquo; (doravante &ldquo;Ageless&rdquo;), uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., a qual caducar&aacute; necess&aacute;ria e automaticamente se e quando o Dr. Vitor Manuel Figueiredo deixar de ser s&oacute;cio ou de integrar profissionalmente (o que primeiro ocorrer) tal sociedade, a qual deixar&aacute; de poder utilizar os Materiais. Para clarifica&ccedil;&atilde;o, a refer&ecirc;ncia feita neste documento a &ldquo;Ageless&rdquo; reporta-se exclusivamente &agrave; Ready Point, Lda.</li>
<li>Os Materiais podem ser utilizados no &acirc;mbito de qualquer parceria que a Ready Point Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Conceder &agrave; Global Metik Lda Lda. uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., podendo os Materiais ser utilizados no &acirc;mbito de qualquer parceria que a Global Metik, Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Que est&aacute; ciente do seu direito, nos termos do C&oacute;digo Civil, de revogar esta autoriza&ccedil;&atilde;o a todo o tempo, ainda que com obriga&ccedil;&atilde;o de indemnizar os danos e preju&iacute;zos causados a qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, tendo em conta os investimentos efetuados por estes, neles se incluindo, nomeadamente e sem excluir, os custos de remo&ccedil;&atilde;o, se poss&iacute;vel, da sua imagem dos Materiais ou de destrui&ccedil;&atilde;o dos Materiais;</li>
<li>N&atilde;o ceder futuramente, total ou parcialmente, os direitos aqui mencionados a qualquer outra pessoa f&iacute;sica ou jur&iacute;dica, de modo impeditivo ou que, de qualquer forma, interfira com os direitos acima concedidos;</li>
<li>Eximir qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, de todas as responsabilidades que possam resultar do exerc&iacute;cio dos direitos aqui concedidos e indemniz&aacute;-los por todos os danos resultantes do incumprimento dos compromissos aqui obtidos ou da inexatid&atilde;o das declara&ccedil;&otilde;es efetuadas;</li>
<li>Relativamente ao tratamento do seu dado pessoal imagem, ter sido informado que tal tratamento ser&aacute; da responsabilidade do Dr. Vitor Manuel Figueiredo, da Ageless ou da Global Metik, Lda., cada um enquanto respons&aacute;vel independente pelo tratamento de dados, ser&aacute; realizado para as finalidades acima indicadas e, ainda, que:</li>
<li>O tratamento do dado pessoal imagem n&atilde;o constitui uma obriga&ccedil;&atilde;o legal ou contratual, n&atilde;o estando o paciente obrigado a fornecer a sua imagem e n&atilde;o havendo quaisquer consequ&ecirc;ncias caso n&atilde;o o forne&ccedil;a;</li>
<li>As imagens do paciente ser&atilde;o mantidas pelo per&iacute;odo que se revelar estritamente necess&aacute;rio tendo em considera&ccedil;&atilde;o as finalidades supra indicadas.</li>
<li>Os dados pessoais utilizados no &acirc;mbito das parcerias poder&atilde;o ser transmitidos aos parceiros. Sem preju&iacute;zo, os dados poder&atilde;o ser acedidos por terceiros no &acirc;mbito de presta&ccedil;&otilde;es de servi&ccedil;os de tecnologias de informa&ccedil;&atilde;o ou outras, sendo que tais terceiros tratar&atilde;o os dados em nome do respons&aacute;vel pelo tratamento e de acordo com instru&ccedil;&otilde;es do mesmo.</li>
<li>Pode, a qualquer momento, retirar o seu consentimento para a capta&ccedil;&atilde;o e utiliza&ccedil;&atilde;o de imagens, sem que tal comprometa, no entanto, a licitude do tratamento realizado com base no consentimento previamente prestado.</li>
<li>Tem o direito de acesso, retifica&ccedil;&atilde;o, apagamento, portabilidade, limita&ccedil;&atilde;o e oposi&ccedil;&atilde;o ao tratamento dos seus dados pessoais e de retirar o consentimento, podendo exercer qualquer destes direitos mediante pedido escrito para Ageless, Via do Oriente, Lote 8, 5.03.01C, Escrit&oacute;rios 1,2,3,4, Edif&iacute;cio Tibre, 1990 - 514 Lisboa. Tem igualmente direito de apresentar uma reclama&ccedil;&atilde;o &agrave; Comiss&atilde;o Nacional de Prote&ccedil;&atilde;o de Dados (www.cnpd.pt).</li>
</ol>
<p>&nbsp;</p>
    <p><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
    <p><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
    <p><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
    <p><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
    ';

    // Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="50">
        </td>';
        
if ($medico_signature_file) {
    $html .= '
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="50">
        </td>';
}

$html .= '
    </tr>
</table>';


    // Adiciona o conteúdo na quarta página
    $pdf->writeHTML($html, true, false, true, false, '');
}
                break;
case 'CI-mesoestimulacao':
                // Adiciona uma página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo do PDF com o conteúdo e identificadores dinâmicos
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">CONSENTIMENTO INFORMADO PARA MESOESTIMULAÇÃO<sup>®</sup> CAPILAR COM FATORES DE CRESCIMENTO PLASMÁTICOS</span></h1>
<p style="margin:0cm;text-align:justify;"><u>O que são os fatores de crescimento plasmáticos?</u>
<br>Os fatores de crescimento plasmáticos podem ser usados para o rejuvenescimento não cirúrgico do corpo. 
A base da técnica consiste na utilização de células e substâncias da própria pessoa que são usadas estimular e potenciar as funções biológicas normais dos tecidos que, com o passar do tempo, se foram degradando.<br>
A capacidade de síntese de novo colagénio pelo fibroblasto é potenciada e os mecanismos antioxidantes reparadores são eficazmente activados. É um processo eficaz de reparação, de estimulação, de revitalização e de tonificação da pele, particularmente útil no rosto, pescoço e decote.<br>
As consequências do crono e fotoenvelhecimento (discromias, rugas, flacidez, estrias, irregularidades) podem ser atenuadas e os processos degenerativos lentificados. O aspecto, a textura, o tónus e a cor da pele melhoram e o brilho, a luminosidade e o efeito tensor surgem natural e rapidamente. A técnica pode ser repetida as vezes necessárias, mas geralmente é suficiente um tratamento anual de manutenção. Os resultados podem ser melhores se for complementada com outros tratamentos médicos. 
Este procedimento não deve ser feito em mulheres grávidas ou a amamentar.
</p><br>
<p style="margin:0cm;text-align:justify;"><u>Efeitos secundários</u><br>
Hematomas dependendo da zona a tratar, da toma de certos medicamentos (por exemplo aspirina e derivados) e das características de cada pessoa. Podem demorar vários dias a desaparecer. <br>
Dor ou desconforto espontâneos ou ao toque nas zonas tratadas que podem permanecer alguns dias e necessitar de analgesia oral.<br>
Rubor (vermelhidão) que pode permanecer alguns dias e necessitar de medicação tópica.<br>
Infeção dos locais tratados que pode necessitar de ciclos prolongados de antibióticos sistémicos.<br>
Alteração da pigmentação na zona do tratamento em casos raros.<br>
Uma reação alérgica ou de hipersensibilidade é sempre uma possibilidade, embora extremamente rara, tal como em qualquer outra forma de administração de medicamentos. Poderá necessitar de intervenções médicas emergentes e de evacuação hospitalar.<br>
Reativação de herpes simples ou de herpes zóster sendo necessário intervenção anti-herpética.<br>
Ausência de efeito que se pode dever a circunstâncias individuais inerentes à especificidade do corpo humano e que é impossível de prever.
&nbsp;</p>
<p style="margin:0cm;text-align:justify;"><u>Autorização</u><br>
Fui informada(o) e entendi que a aplicação de fatores de crescimento plasmáticos implica riscos. Se surgir alguma complicação imediata dou o meu consentimento para que se faça o que seja mais conveniente.<br>
Fui informada(o) da necessidade de evitar praia, sauna, piscina e qualquer outro tipo de exposição solar até 48 horas depois do tratamento, assim como da necessidade de evitar exercício físico nas primeiras 24 horas.<br> 
Fui informada(o) da necessidade de dar sempre conhecimento ao Médico da medicação que faço e da mera possibilidade de em qualquer altura estar grávida.<br>
Fui informada(o) de que a possibilidade de surgirem reações alérgicas ou de hipersensibilidade é a mesma que existe em qualquer outra via de administração de fármacos.<br>
Fui informada(o) do direito que tenho de aceitar ou não o procedimento, bem como do direito de anular a aceitação prévia das possibilidades de êxito do tratamento. Reconheço que não me podem ser dadas garantias ou segurança absoluta acerca do resultado do tratamento e que as minhas perguntas neste sentido foram satisfatoriamente respondidas. Sei que posso colocar reservas ou condições particulares em relação ao tratamento e foi-me dada oportunidade para tal.<br>
Autorizo o médico a administrar os fármacos necessários para o meu tratamento assumindo todas as consequências daí resultantes.<br>
Autorizo a obtenção de documentos fotográficos necessários para o adequado cumprimento didático e científico sendo preservada a sua identidade e privacidade.<br>
Tudo o exposto me foi claramente explicado e aceito o tratamento proposto, estando consciente das possibilidades de êxito e das possíveis complicações pelo que assino, em sinal de acordo, de aceitação e de entendimento, este documento.<br>
Fui informada (o) que em caso de necessidade ou dúvida posso contactar o médico 24 horas por dia através de um contacto que me foi fornecido.<br>
Dou o meu consentimento para que me sejam aplicados os fatores de crescimento.
</p>
<br>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da segunda página do PDF
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">AUTORIZAÇÃO PARA A REALIZAÇÃO DE MESOESTIMULAÇÃO<sup>®</sup> COM FATORES DE CRESCIMENTO PLASMÁTICO</span></h1>
<p style="margin:0cm;text-align:justify;">
Declaro que foi por minha iniciativa que recorri à consulta médica por sentir necessidade de diagnosticar e tratar uma condição física e psicológica que me perturba.<br>
Reconheço que a intervenção acima designada me foi proposta após me ter sido realizado um diagnóstico médico detalhado, concreto e rigoroso, com o qual concordo e no qual me revejo.<br>
Reconheço que a intervenção acima designada se destina a tratar uma situação física e psíquica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá ajudar a prevenir o agravamento da situação física e psicológica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá contribuir para a cura da situação física e psicológica de que padeço e me foi diagnosticada.<br>
Reconheço que a intervenção se destina a melhorar a minha autoconfiança e restabelecer o meu bem-estar físico, mental e social.<br>
Reconheço que a intervenção proposta se destina, portanto, a restabelecer, proteger e manter a minha saúde física e mental.<br>
Reconheço que a intervenção acima designada é efetuada numa clínica médica com todas as condições, por um licenciado em medicina inscrito na ordem dos médicos portugueses, que me certifiquei que possui preparação e formação para a executar.<br>
</p><br>
<p>&nbsp;</p>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na segunda página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da terceira página do PDF
$html = '
<p style="margin:0cm;text-align:right;"><span style="font-size:16px;font-weight:bold;">DIAGNÓSTICO MÉDICO PARA A REALIZAÇÃO DE MESOESTIMULAÇÃO<sup>®</sup> COM FATORES DE CRESCIMENTO PLASMÁTICO</span></p>
<span style="text-align:left">
<p>Diagn&oacute;stico m&eacute;dico conforme ICD 10 (Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de)</p>
<ul>
<li>H02 - Outras perturba&ccedil;&otilde;es da p&aacute;lpebra</li>
<li>H0230 - Blefarocal&aacute;sia em olho n&atilde;o especificado, p&aacute;lpebra n&atilde;o especificada</li>
<li>H024 &ndash; Ptose da p&aacute;lpebra</li>
<li>L568 &ndash; Outras altera&ccedil;&otilde;es agudas especificadas da pele devidas a radia&ccedil;&atilde;o ultravioleta</li>
<li>L574 &ndash; Cutis laxa associada &aacute; idade</li>
<li>L814 &ndash; Outras formas de hiperpigmenta&ccedil;&atilde;o pela melanina</li>
<li>L85 &ndash; Outro espessamento epid&eacute;rmico</li>
<li>L853 &ndash; Xerose cut&acirc;nea</li>
<li>L90 &ndash; Perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L908 &ndash; Outras perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L909 &ndash; Perturba&ccedil;&atilde;o atr&oacute;ficas da pele sem outra especifica&ccedil;&atilde;o</li>
<li>L987 &ndash; Pele e tecido subcut&acirc;neo excessivo e redundante</li>
<li>L989 &ndash; Afe&ccedil;&otilde;es da pele e do tecido subcut&acirc;neo, n&atilde;o especificadas</li>
</ul>
<p>&nbsp;</p>
<p>Que originam e causam</p>
<ul>
<li>Sintomas depressivos</li>
<li>Ansiedade</li>
<li>Ins&oacute;nia e/ou perturba&ccedil;&otilde;es do sono</li>
<li>Irritabilidade</li>
<li>Dificuldade de desempenhar as atividades habituais</li>
<li>Baixa vitalidade, energia e tranquilidade</li>
<li>Estado de infelicidade</li>
<li>Incapacidade de responder &agrave;s adversidades</li>
<li>Perturba&ccedil;&atilde;o das rela&ccedil;&otilde;es com os demais</li>
<li>Dificuldade em sentir-se bem consigo pr&oacute;prio</li>
<li>Aus&ecirc;ncia de um estado de completo bem-estar f&iacute;sico, mental e social</li>
</ul>
<p>O que conforme a ICD 10(Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de) :</p>
<ul>
<li>F39 &ndash; Perturba&ccedil;&atilde;o do humor [afetivo], sem outra especifica&ccedil;&atilde;o</li>
<li>F419 &ndash; Estado de ansiedade, sem outra especifica&ccedil;&atilde;o</li>
<li>F51 &ndash; Transtornos n&atilde;o-org&acirc;nicos do sono devidos a fatores emocionais</li>
<li>F518 &ndash; Outros transtornos do sono devidos a fatores n&atilde;o-org&acirc;nicos</li>
</ul>
    </span>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
';

// Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="70">
        </td>
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="70">
        </td>
    </tr>
</table>';


// Adiciona o conteúdo na terceira página
$pdf->writeHTML($html, true, false, true, false, '');

// Verifica se o consentimento de imagem foi removido
$remover_consentimento = isset($_POST["remover_consentimento_imagem"]) && $_POST["remover_consentimento_imagem"] == '1';

// Adiciona uma nova página para consentimento de imagem SE o consentimento NÃO foi removido
if (!$remover_consentimento) {
    // Adiciona uma nova página
    $pdf->AddPage();

    // Define a fonte
    $pdf->SetFont('helvetica', '', 8);

    // Conteúdo da quarta página (Consentimento de Imagem)
    $html = '
    <label style="font-size:18px;" for="consentimento_imagem"><u>Consentimento de Imagem</u></label>
                            <p>' . htmlspecialchars($form_data["nome_completo"]) . '  <strong>DECLARA</strong>, para os devidos efeitos:</p>
<ol>
<li>Que, no &acirc;mbito do procedimento m&eacute;dico ' . htmlspecialchars($form_data["procedimento_type"]) . '<sup>&reg;</sup> realizado com Dr Vitor Figueiredo ou com outro m&eacute;dico sob a dire&ccedil;&atilde;o t&eacute;cnica e cl&iacute;nica daquele, existe capta&ccedil;&atilde;o de imagens (fotografia e/ou v&iacute;deo), antes e depois de ocorrer o procedimento m&eacute;dico, capta&ccedil;&atilde;o essa promovida pelo Dr,Vitor Figueiredo e da sua responsabilidade exclusiva, com a qual concorda e na qual expressamente consente.</li>
<li>Ter sido informado que quaisquer materiais resultantes da capta&ccedil;&atilde;o de imagens (doravante designados apenas por &ldquo;Materiais&rdquo;) ser&atilde;o utilizados, sem identifica&ccedil;&atilde;o e sob reserva de confidencialidade a todo o tempo, para fins did&aacute;ticos, formativos e cient&iacute;ficos, bem como para fins publicit&aacute;rios ou comerciais, em qualquer meio de difus&atilde;o e comunica&ccedil;&atilde;o interno ou externo, a n&iacute;vel nacional ou internacional, atrav&eacute;s de quaisquer canais (incluindo digitais), nomeadamente televis&atilde;o, imprensa escrita, internet, redes sociais e outros existentes ou que venham a existir.</li>
<li>Conceder ao Dr. Vitor Manuel Figueiredo uma autoriza&ccedil;&atilde;o expressa, gratuita e por 10 (dez) anos para utiliza&ccedil;&atilde;o, por qualquer forma, e comunica&ccedil;&atilde;o ao p&uacute;blico, dos Materiais para os fins e atrav&eacute;s dos meios acima referidos, com vista &agrave; sua explora&ccedil;&atilde;o a n&iacute;vel mundial, sem limita&ccedil;&otilde;es de nenhum tipo e sem necessidade de obter nenhum consentimento ou autoriza&ccedil;&atilde;o posterior em rela&ccedil;&atilde;o ao uso que se fa&ccedil;a dos mesmos.</li>
<li>Conceder &agrave; Ready Point, Lda, que usa comercialmente a designa&ccedil;&atilde;o &ldquo;Ageless&ndash;Anti Aging Center&rdquo; (doravante &ldquo;Ageless&rdquo;), uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., a qual caducar&aacute; necess&aacute;ria e automaticamente se e quando o Dr. Vitor Manuel Figueiredo deixar de ser s&oacute;cio ou de integrar profissionalmente (o que primeiro ocorrer) tal sociedade, a qual deixar&aacute; de poder utilizar os Materiais. Para clarifica&ccedil;&atilde;o, a refer&ecirc;ncia feita neste documento a &ldquo;Ageless&rdquo; reporta-se exclusivamente &agrave; Ready Point, Lda.</li>
<li>Os Materiais podem ser utilizados no &acirc;mbito de qualquer parceria que a Ready Point Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Conceder &agrave; Global Metik Lda Lda. uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., podendo os Materiais ser utilizados no &acirc;mbito de qualquer parceria que a Global Metik, Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Que est&aacute; ciente do seu direito, nos termos do C&oacute;digo Civil, de revogar esta autoriza&ccedil;&atilde;o a todo o tempo, ainda que com obriga&ccedil;&atilde;o de indemnizar os danos e preju&iacute;zos causados a qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, tendo em conta os investimentos efetuados por estes, neles se incluindo, nomeadamente e sem excluir, os custos de remo&ccedil;&atilde;o, se poss&iacute;vel, da sua imagem dos Materiais ou de destrui&ccedil;&atilde;o dos Materiais;</li>
<li>N&atilde;o ceder futuramente, total ou parcialmente, os direitos aqui mencionados a qualquer outra pessoa f&iacute;sica ou jur&iacute;dica, de modo impeditivo ou que, de qualquer forma, interfira com os direitos acima concedidos;</li>
<li>Eximir qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, de todas as responsabilidades que possam resultar do exerc&iacute;cio dos direitos aqui concedidos e indemniz&aacute;-los por todos os danos resultantes do incumprimento dos compromissos aqui obtidos ou da inexatid&atilde;o das declara&ccedil;&otilde;es efetuadas;</li>
<li>Relativamente ao tratamento do seu dado pessoal imagem, ter sido informado que tal tratamento ser&aacute; da responsabilidade do Dr. Vitor Manuel Figueiredo, da Ageless ou da Global Metik, Lda., cada um enquanto respons&aacute;vel independente pelo tratamento de dados, ser&aacute; realizado para as finalidades acima indicadas e, ainda, que:</li>
<li>O tratamento do dado pessoal imagem n&atilde;o constitui uma obriga&ccedil;&atilde;o legal ou contratual, n&atilde;o estando o paciente obrigado a fornecer a sua imagem e n&atilde;o havendo quaisquer consequ&ecirc;ncias caso n&atilde;o o forne&ccedil;a;</li>
<li>As imagens do paciente ser&atilde;o mantidas pelo per&iacute;odo que se revelar estritamente necess&aacute;rio tendo em considera&ccedil;&atilde;o as finalidades supra indicadas.</li>
<li>Os dados pessoais utilizados no &acirc;mbito das parcerias poder&atilde;o ser transmitidos aos parceiros. Sem preju&iacute;zo, os dados poder&atilde;o ser acedidos por terceiros no &acirc;mbito de presta&ccedil;&otilde;es de servi&ccedil;os de tecnologias de informa&ccedil;&atilde;o ou outras, sendo que tais terceiros tratar&atilde;o os dados em nome do respons&aacute;vel pelo tratamento e de acordo com instru&ccedil;&otilde;es do mesmo.</li>
<li>Pode, a qualquer momento, retirar o seu consentimento para a capta&ccedil;&atilde;o e utiliza&ccedil;&atilde;o de imagens, sem que tal comprometa, no entanto, a licitude do tratamento realizado com base no consentimento previamente prestado.</li>
<li>Tem o direito de acesso, retifica&ccedil;&atilde;o, apagamento, portabilidade, limita&ccedil;&atilde;o e oposi&ccedil;&atilde;o ao tratamento dos seus dados pessoais e de retirar o consentimento, podendo exercer qualquer destes direitos mediante pedido escrito para Ageless, Via do Oriente, Lote 8, 5.03.01C, Escrit&oacute;rios 1,2,3,4, Edif&iacute;cio Tibre, 1990 - 514 Lisboa. Tem igualmente direito de apresentar uma reclama&ccedil;&atilde;o &agrave; Comiss&atilde;o Nacional de Prote&ccedil;&atilde;o de Dados (www.cnpd.pt).</li>
</ol>
<p>&nbsp;</p>
    <p><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
    <p><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
    <p><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
    <p><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
    ';

    // Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="50">
        </td>';
        
if ($medico_signature_file) {
    $html .= '
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="50">
        </td>';
}

$html .= '
    </tr>
</table>';


    // Adiciona o conteúdo na quarta página
    $pdf->writeHTML($html, true, false, true, false, '');
}
                break;
case 'CI-peeling':
                 // Adiciona uma página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo do PDF com o conteúdo e identificadores dinâmicos
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;font-weight:bold;">CONSENTIMENTO INFORMADO PARA A REALIZAÇÃO DE PEELING</span></h1>

<p style="margin:0cm;text-align:justify;"><u>O que é um peeling</u>?<br>
Existem várias formas de provocar uma esfoliação (peeling) da pele. Numa quimioesfoliação da pele usam-se «medicamentos» para provocar a «descamação» cutânea que posteriormente dará lugar à renovação da mesma.
Existem vários tipos de peelings químicos conforme o problema que se pretende tratar - acne, «manchas», rugas, envelhecimento ou cicatrizes (de acne por exemplo) - e conforme o local - rosto, pescoço, decote ou mãos.
O número de peelings e a frequência dos mesmos varia em função da patologia a tratar e das características de cada paciente pelo que não pode ser previsto com exactidão. Os resultados podem ser melhores se forem complementados com outros tratamentos médicos. Não foi testado em mulheres grávidas ou a amamentar.
</p><br>
<p style="margin:0cm;text-align:justify;"><u>Efeitos secundários</u><br>
O facto de se «agredir» a pele não torna o procedimento obrigatoriamente desconfortável. A sensibilidade é muito variável de pessoa para pessoa. Ardor, prurido e sensação de calor será o que vai sentir durante o peeling. Dor ou desconforto espontâneos ou ao toque nas zonas tratadas que podem permanecer alguns dias e necessitar de analgesia oral.<br>
Rubor («vermelhidão») em todo o rosto, como se de uma queimadura solar ligeira se tratasse, mais evidente nas zonas sensíveis como o queixo, em torno do nariz e boca e na zona dos sulcos nasolabiais. É uma consequência normal do tratamento que desaparece em horas mas pode permanecer alguns dias.<br>
Descamação da pele. Normalmente é ligeira e não impede a vida normal. A pele não deve ser nunca arrancada. <br>
Existe a possibilidade de surgir alteração temporária ou mais raramente permanente da pigmentação da pele em algumas zonas principalmente se houver exposição ultravioleta depois do tratamento. A protecção solar adequada diária é obrigatória.<br>
Raramente podem ocorrer cicatrizes, lesões tipo acne ou reactivação de herpes nomeadamente com peelings profundos.<br>
Uma reacção alérgica ou de hipersensibilidade é sempre uma possibilidade, embora extremamente rara. Poderá necessitar de intervenções médicas emergentes e de evacuação hospitalar.<br>
Edema das regiões tratadas, súbitamente ou nos dias seguintes e pode necessitar de corticoterapia oral ou sistêmica.<br>
Infeção dos locais tratados que pode necessitar de ciclos prolongados de antibióticos orais e/ou sistémicos.<br>
Ausência de efeito dos fármacos utilizados que se pode dever a circunstâncias individuais inerentes à especificidade do corpo humano e que é impossível de prever.
&nbsp;</p>

<p style="margin:0cm;text-align:justify;"><u>Autorização</u><br>
Fui informada(o) e entendi que a realização de um peeling implica riscos. Se surgir alguma complicação imediata dou o meu consentimento para que se faça o que seja mais conveniente.<br>
Fui informada(o) da necessidade de evitar praia, sauna, piscina e qualquer outro tipo de exposição solar até 48 horas depois do tratamento, assim como da necessidade de evitar exercício físico nas primeiras 24 horas. <br>
Fui informada(o) da necessidade de dar sempre conhecimento ao Médico da medicação que faço e da mera possibilidade de em qualquer altura estar grávida.<br>
Fui informada(o) de que a possibilidade de surgirem reações alérgicas ou de hipersensibilidade é a mesma que existe em qualquer outra via de administração de fármacos.<br>
Fui informada(o) do direito que tenho de aceitar ou não o procedimento, bem como do direito de anular a aceitação prévia das possibilidades de êxito do tratamento. Reconheço que não me podem ser dadas garantias ou segurança absoluta acerca do resultado do tratamento e que as minhas perguntas neste sentido foram satisfatoriamente respondidas. Sei que posso colocar reservas ou condições particulares em relação ao tratamento e foi-me dada oportunidade para tal.<br>
Autorizo o médico a administrar os fármacos para o meu tratamento assumindo todas as consequências daí resultantes.<br>
Autorizo a obtenção de documentos fotográficos necessários para o adequado cumprimento didático e científico sendo preservada a sua identidade e privacidade.<br>
Tudo o exposto me foi claramente explicado e aceito o tratamento proposto, estando consciente das possibilidades de êxito e das possíveis complicações pelo que assino, em sinal de acordo, de aceitação e de entendimento, este documento.<br>
Fui informada (o) que em caso de necessidade ou dúvida posso contactar o médico 24 horas por dia através de um contacto que me foi fornecido.<br>
Dou o meu consentimento para que me seja realizado um peeling.
</p>
<br>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da segunda página do PDF
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">AUTORIZAÇÃO PARA A REALIZAÇÃO DE PEELING</span></h1>
<p style="margin:0cm;text-align:justify;">
Declaro que foi por minha iniciativa que recorri à consulta médica por sentir necessidade de diagnosticar e tratar uma condição física e psicológica que me perturba.<br>
Reconheço que a intervenção acima designada me foi proposta após me ter sido realizado um diagnóstico médico detalhado, concreto e rigoroso, com o qual concordo e no qual me revejo.<br>
Reconheço que a intervenção acima designada se destina a tratar uma situação física e psíquica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá ajudar a prevenir o agravamento da situação física e psicológica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá contribuir para a cura da situação física e psicológica de que padeço e me foi diagnosticada.<br>
Reconheço que a intervenção se destina a melhorar a minha autoconfiança e restabelecer o meu bem-estar físico, mental e social.<br>
Reconheço que a intervenção proposta se destina, portanto, a restabelecer, proteger e manter a minha saúde física e mental.<br>
Reconheço que a intervenção acima designada é efetuada numa clínica médica com todas as condições, por um licenciado em medicina inscrito na ordem dos médicos portugueses, que me certifiquei que possui preparação e formação para a executar.
</p><br>
<p>&nbsp;</p>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na segunda página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da terceira página do PDF
$html = '
<p style="margin:0cm;text-align:right;"><span style="font-size:16px;font-weight:bold;">DIAGNÓSTICO MÉDICO PARA A REALIZAÇÃO DE PEELING</span></p>
<span style="text-align:left">
<p>Diagn&oacute;stico m&eacute;dico conforme ICD 10 (Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de)</p>
<ul>
<li>H02 - Outras perturba&ccedil;&otilde;es da p&aacute;lpebra</li>
<li>H0230 - Blefarocal&aacute;sia em olho n&atilde;o especificado, p&aacute;lpebra n&atilde;o especificada</li>
<li>H024 &ndash; Ptose da p&aacute;lpebra</li>
<li>L568 &ndash; Outras altera&ccedil;&otilde;es agudas especificadas da pele devidas a radia&ccedil;&atilde;o ultravioleta</li>
<li>L574 &ndash; Cutis laxa associada &aacute; idade</li>
<li>L814 &ndash; Outras formas de hiperpigmenta&ccedil;&atilde;o pela melanina</li>
<li>L85 &ndash; Outro espessamento epid&eacute;rmico</li>
<li>L853 &ndash; Xerose cut&acirc;nea</li>
<li>L90 &ndash; Perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L908 &ndash; Outras perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L909 &ndash; Perturba&ccedil;&atilde;o atr&oacute;ficas da pele sem outra especifica&ccedil;&atilde;o</li>
<li>L987 &ndash; Pele e tecido subcut&acirc;neo excessivo e redundante</li>
<li>L989 &ndash; Afe&ccedil;&otilde;es da pele e do tecido subcut&acirc;neo, n&atilde;o especificadas</li>
</ul>
<p>&nbsp;</p>
<p>Que originam e causam</p>
<ul>
<li>Sintomas depressivos</li>
<li>Ansiedade</li>
<li>Ins&oacute;nia e/ou perturba&ccedil;&otilde;es do sono</li>
<li>Irritabilidade</li>
<li>Dificuldade de desempenhar as atividades habituais</li>
<li>Baixa vitalidade, energia e tranquilidade</li>
<li>Estado de infelicidade</li>
<li>Incapacidade de responder &agrave;s adversidades</li>
<li>Perturba&ccedil;&atilde;o das rela&ccedil;&otilde;es com os demais</li>
<li>Dificuldade em sentir-se bem consigo pr&oacute;prio</li>
<li>Aus&ecirc;ncia de um estado de completo bem-estar f&iacute;sico, mental e social</li>
</ul>
<p>O que conforme a ICD 10 (Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de) :</p>
<ul>
<li>F39 &ndash; Perturba&ccedil;&atilde;o do humor [afetivo], sem outra especifica&ccedil;&atilde;o</li>
<li>F419 &ndash; Estado de ansiedade, sem outra especifica&ccedil;&atilde;o</li>
<li>F51 &ndash; Transtornos n&atilde;o-org&acirc;nicos do sono devidos a fatores emocionais</li>
<li>F518 &ndash; Outros transtornos do sono devidos a fatores n&atilde;o-org&acirc;nicos</li>
</ul>
    </span>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
';

// Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="70">
        </td>
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="70">
        </td>
    </tr>
</table>';


// Adiciona o conteúdo na terceira página
$pdf->writeHTML($html, true, false, true, false, '');

// Verifica se o consentimento de imagem foi removido
$remover_consentimento = isset($_POST["remover_consentimento_imagem"]) && $_POST["remover_consentimento_imagem"] == '1';

// Adiciona uma nova página para consentimento de imagem SE o consentimento NÃO foi removido
if (!$remover_consentimento) {
    // Adiciona uma nova página
    $pdf->AddPage();

    // Define a fonte
    $pdf->SetFont('helvetica', '', 8);

    // Conteúdo da quarta página (Consentimento de Imagem)
    $html = '
    <label style="font-size:18px;" for="consentimento_imagem"><u>Consentimento de Imagem</u></label>
                            <p>' . htmlspecialchars($form_data["nome_completo"]) . '  <strong>DECLARA</strong>, para os devidos efeitos:</p>

<ol>
<li>Que, no &acirc;mbito do procedimento m&eacute;dico ' . htmlspecialchars($form_data["procedimento_type"]) . '<sup>&reg;</sup> realizado com Dr Vitor Figueiredo ou com outro m&eacute;dico sob a dire&ccedil;&atilde;o t&eacute;cnica e cl&iacute;nica daquele, existe capta&ccedil;&atilde;o de imagens (fotografia e/ou v&iacute;deo), antes e depois de ocorrer o procedimento m&eacute;dico, capta&ccedil;&atilde;o essa promovida pelo Dr,Vitor Figueiredo e da sua responsabilidade exclusiva, com a qual concorda e na qual expressamente consente.</li>
<li>Ter sido informado que quaisquer materiais resultantes da capta&ccedil;&atilde;o de imagens (doravante designados apenas por &ldquo;Materiais&rdquo;) ser&atilde;o utilizados, sem identifica&ccedil;&atilde;o e sob reserva de confidencialidade a todo o tempo, para fins did&aacute;ticos, formativos e cient&iacute;ficos, bem como para fins publicit&aacute;rios ou comerciais, em qualquer meio de difus&atilde;o e comunica&ccedil;&atilde;o interno ou externo, a n&iacute;vel nacional ou internacional, atrav&eacute;s de quaisquer canais (incluindo digitais), nomeadamente televis&atilde;o, imprensa escrita, internet, redes sociais e outros existentes ou que venham a existir.</li>
<li>Conceder ao Dr. Vitor Manuel Figueiredo uma autoriza&ccedil;&atilde;o expressa, gratuita e por 10 (dez) anos para utiliza&ccedil;&atilde;o, por qualquer forma, e comunica&ccedil;&atilde;o ao p&uacute;blico, dos Materiais para os fins e atrav&eacute;s dos meios acima referidos, com vista &agrave; sua explora&ccedil;&atilde;o a n&iacute;vel mundial, sem limita&ccedil;&otilde;es de nenhum tipo e sem necessidade de obter nenhum consentimento ou autoriza&ccedil;&atilde;o posterior em rela&ccedil;&atilde;o ao uso que se fa&ccedil;a dos mesmos.</li>
<li>Conceder &agrave; Ready Point, Lda, que usa comercialmente a designa&ccedil;&atilde;o &ldquo;Ageless&ndash;Anti Aging Center&rdquo; (doravante &ldquo;Ageless&rdquo;), uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., a qual caducar&aacute; necess&aacute;ria e automaticamente se e quando o Dr. Vitor Manuel Figueiredo deixar de ser s&oacute;cio ou de integrar profissionalmente (o que primeiro ocorrer) tal sociedade, a qual deixar&aacute; de poder utilizar os Materiais. Para clarifica&ccedil;&atilde;o, a refer&ecirc;ncia feita neste documento a &ldquo;Ageless&rdquo; reporta-se exclusivamente &agrave; Ready Point, Lda.</li>
<li>Os Materiais podem ser utilizados no &acirc;mbito de qualquer parceria que a Ready Point Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Conceder &agrave; Global Metik Lda Lda. uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., podendo os Materiais ser utilizados no &acirc;mbito de qualquer parceria que a Global Metik, Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Que est&aacute; ciente do seu direito, nos termos do C&oacute;digo Civil, de revogar esta autoriza&ccedil;&atilde;o a todo o tempo, ainda que com obriga&ccedil;&atilde;o de indemnizar os danos e preju&iacute;zos causados a qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, tendo em conta os investimentos efetuados por estes, neles se incluindo, nomeadamente e sem excluir, os custos de remo&ccedil;&atilde;o, se poss&iacute;vel, da sua imagem dos Materiais ou de destrui&ccedil;&atilde;o dos Materiais;</li>
<li>N&atilde;o ceder futuramente, total ou parcialmente, os direitos aqui mencionados a qualquer outra pessoa f&iacute;sica ou jur&iacute;dica, de modo impeditivo ou que, de qualquer forma, interfira com os direitos acima concedidos;</li>
<li>Eximir qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, de todas as responsabilidades que possam resultar do exerc&iacute;cio dos direitos aqui concedidos e indemniz&aacute;-los por todos os danos resultantes do incumprimento dos compromissos aqui obtidos ou da inexatid&atilde;o das declara&ccedil;&otilde;es efetuadas;</li>
<li>Relativamente ao tratamento do seu dado pessoal imagem, ter sido informado que tal tratamento ser&aacute; da responsabilidade do Dr. Vitor Manuel Figueiredo, da Ageless ou da Global Metik, Lda., cada um enquanto respons&aacute;vel independente pelo tratamento de dados, ser&aacute; realizado para as finalidades acima indicadas e, ainda, que:</li>
<li>O tratamento do dado pessoal imagem n&atilde;o constitui uma obriga&ccedil;&atilde;o legal ou contratual, n&atilde;o estando o paciente obrigado a fornecer a sua imagem e n&atilde;o havendo quaisquer consequ&ecirc;ncias caso n&atilde;o o forne&ccedil;a;</li>
<li>As imagens do paciente ser&atilde;o mantidas pelo per&iacute;odo que se revelar estritamente necess&aacute;rio tendo em considera&ccedil;&atilde;o as finalidades supra indicadas.</li>
<li>Os dados pessoais utilizados no &acirc;mbito das parcerias poder&atilde;o ser transmitidos aos parceiros. Sem preju&iacute;zo, os dados poder&atilde;o ser acedidos por terceiros no &acirc;mbito de presta&ccedil;&otilde;es de servi&ccedil;os de tecnologias de informa&ccedil;&atilde;o ou outras, sendo que tais terceiros tratar&atilde;o os dados em nome do respons&aacute;vel pelo tratamento e de acordo com instru&ccedil;&otilde;es do mesmo.</li>
<li>Pode, a qualquer momento, retirar o seu consentimento para a capta&ccedil;&atilde;o e utiliza&ccedil;&atilde;o de imagens, sem que tal comprometa, no entanto, a licitude do tratamento realizado com base no consentimento previamente prestado.</li>
<li>Tem o direito de acesso, retifica&ccedil;&atilde;o, apagamento, portabilidade, limita&ccedil;&atilde;o e oposi&ccedil;&atilde;o ao tratamento dos seus dados pessoais e de retirar o consentimento, podendo exercer qualquer destes direitos mediante pedido escrito para Ageless, Via do Oriente, Lote 8, 5.03.01C, Escrit&oacute;rios 1,2,3,4, Edif&iacute;cio Tibre, 1990 - 514 Lisboa. Tem igualmente direito de apresentar uma reclama&ccedil;&atilde;o &agrave; Comiss&atilde;o Nacional de Prote&ccedil;&atilde;o de Dados (www.cnpd.pt).</li>
</ol>
<p>&nbsp;</p>
    <p><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
    <p><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
    <p><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
    <p><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
    ';

    // Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="50">
        </td>';
        
if ($medico_signature_file) {
    $html .= '
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="50">
        </td>';
}

$html .= '
    </tr>
</table>';


    // Adiciona o conteúdo na quarta página
    $pdf->writeHTML($html, true, false, true, false, '');
}
                break;
case 'CI-Superfomer':
                // Adiciona uma página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo do PDF com o conteúdo e identificadores dinâmicos
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;font-weight:bold;">Consentimento informado para o tratamento Superfomer<sup>®</sup></span></h1>
<p style="margin:0cm;text-align:justify;"><u>O que é o tratamento Superfomer</u><sup>®</sup>?
<br>O Superfomer<sup>®</sup> O superformer® é um procedimento de rejuvenescimento do rosto, pescoço, e/ou corpo com resultados progressivos. Consiste na aplicação local, na superfície da pele, de uma fonte de energia à base de ultrassons HIFU (ultrassons focalizados de alta intensidade). 
Estes ultrassons atuam nos tecidos de forma intensa mas não penetram em profundidade ao contrário dos ultrassons usados nas ecografias «normais». Destinam-se a tratar flacidez e/ou gordura localizada em excesso. 
 Pode ser feito por qualquer pessoa em qualquer altura do ano, excepto durante a gravidez ou aleitamento.
</p><br>
<p style="margin:0cm;text-align:justify;"><u>Efeitos secundários</u><br>
Hematomas dependendo da zona a tratar, da toma de certos medicamentos (por exemplo aspirina e derivados) e das características de cada pessoa. Podem demorar vários dias a desaparecer. <br>
Dor ou desconforto espontâneos ou ao toque nas zonas tratadas que podem permanecer alguns dias e necessitar de analgesia oral.<br>
Edema das regiões tratadas que pode surgir subitamente ou nos dias seguintes e necessitar de corticoterapia oral ou sistémica.<br>
Rubor (vermelhidão) que pode permanecer alguns dias e necessitar de medicação tópica.<br>
Infeção dos locais tratados que pode necessitar de ciclos prolongados de antibióticos sistémicos.<br>
Nódulos e endurecimentos permanentes podem ocorrer e necessitar de procedimentos cirúrgicos para completa resolução.<br>
Assimetrias, que podem ser mais notórias em determinadas zonas da face, e que poderão necessitar de meses para estabilizar ou mesmo de tratamentos adicionais.<br>
Alteração da pigmentação na zona do tratamento em casos raros.<br>
Uma reação alérgica ou de hipersensibilidade ao anestésico tópico utilizado é sempre uma possibilidade, embora extremamente rara, tal como em qualquer outra forma de administração de medicamentos. Poderá necessitar de intervenções médicas emergentes e de evacuação hospitalar.<br>
Reativação de herpes simples ou de herpes zóster sendo necessário intervenção anti-herpética.<br>
Ausência de efeito dos HIFU utilizados que se pode dever a circunstâncias individuais inerentes à especificidade do corpo humano e que é impossível de prever.
&nbsp;</p>
<p style="margin:0cm;text-align:justify;"><u>Autorização</u><br>
Fui informada(o) e entendi que a Superfomer<sup>®</sup> implica riscos. Se surgir alguma complicação imediata dou o meu consentimento para que se faça o que for mais conveniente.<br>
Fui informado(a) da necessidade de evitar praia, sauna, piscina e qualquer outro tipo de exposição solar até 48 horas depois do tratamento, assim como da necessidade de evitar exercício físico nas primeiras 24 horas. <br>
Fui informado(a) da necessidade de dar sempre conhecimento ao médico e  equipa da medicação que faço e da mera possibilidade de em qualquer altura estar grávida.<br>
Fui informado(a) de que a possibilidade de surgirem reações alérgicas ou de hipersensibilidade é a mesma que existe em qualquer outra via de administração de fármacos.<br>
Fui informado(a) do direito que tenho de aceitar ou não o procedimento, bem como do direito de anular a aceitação prévia das possibilidades de êxito do tratamento.<br>
Reconheço que não me podem ser dadas garantias ou segurança absoluta acerca do resultado do tratamento e que as minhas perguntas neste sentido foram satisfatoriamente respondidas. Sei que posso colocar reservas ou condições particulares em relação ao tratamento e foi-me dada oportunidade para tal.<br>
Autorizo o médico a administrar os fármacos necessários para o meu tratamento assumindo todas as consequências daí resultante.<br>
Autorizo a obtenção de documentos fotográficos necessários para o adequado cumprimento didático e científico sendo preservada a sua identidade e privacidade.<br>
Tudo o exposto me foi claramente explicado e aceito o tratamento proposto, estando consciente das possibilidades de êxito e das possíveis complicações pelo que assino, em sinal de acordo, de aceitação e de entendimento, este documento.<br>
Fui informado (a) que em caso de necessidade ou dúvida posso contactar o médico e equipa 24 horas por dia através de um contacto que me foi fornecido. <br>
Dou o meu consentimento a que me seja realizado o tratamento superformer<sup>®</sup>.
</p>
<br>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da segunda página do PDF
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:16px;">AUTORIZAÇÃO PARA O TRATAMENTO SUPERFORMER<sup>®</sup></span></h1>
<p style="margin:0cm;text-align:justify;">Declaro que foi por minha iniciativa que recorri à consulta médica por sentir necessidade de diagnosticar e tratar uma condição física e psicológica que me perturba.<br>
Reconheço que a intervenção acima designada me foi proposta após me ter sido realizado um diagnóstico médico detalhado, concreto e rigoroso, com o qual concordo e no qual me revejo.<br>
Reconheço que a intervenção acima designada se destina a tratar uma situação física e psíquica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá ajudar a prevenir o agravamento da situação física e psicológica de que padeço e que me foi diagnosticada.<br>
Reconheço que a intervenção acima designada poderá contribuir para a cura da situação física e psicológica de que padeço e me foi diagnosticada.<br>
Reconheço que a intervenção se destina a melhorar a minha autoconfiança e restabelecer o meu bem-estar físico, mental e social.<br>
Reconheço que a intervenção proposta se destina, portanto, a restabelecer, proteger e manter a minha saúde física e mental.<br>
Reconheço que a intervenção acima designada é efetuada numa clínica médica com todas as condições, por um licenciado em medicina inscrito na ordem dos médicos portugueses, que me certifiquei que possui preparação e formação para a executar.<br>
</p><br>
<p>&nbsp;</p>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>';

// Adiciona o conteúdo na segunda página
$pdf->writeHTML($html, true, false, true, false, '');

// Adiciona uma nova página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo da terceira página do PDF
$html = '
<p style="margin:0cm;text-align:right;"><span style="font-size:16px;font-weight:bold;">DIAGNÓSTICO MÉDICO PARA O TRATAMENTO Superfomer<sup>®</sup></span></p>
<span style="text-align:left">
<p>Diagn&oacute;stico m&eacute;dico conforme ICD 10 (Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de)</p>
<ul>
<li>H02 - Outras perturba&ccedil;&otilde;es da p&aacute;lpebra</li>
<li>H0230 - Blefarocal&aacute;sia em olho n&atilde;o especificado, p&aacute;lpebra n&atilde;o especificada</li>
<li>H024 &ndash; Ptose da p&aacute;lpebra</li>
<li>L568 &ndash; Outras altera&ccedil;&otilde;es agudas especificadas da pele devidas a radia&ccedil;&atilde;o ultravioleta</li>
<li>L574 &ndash; Cutis laxa associada &aacute; idade</li>
<li>L814 &ndash; Outras formas de hiperpigmenta&ccedil;&atilde;o pela melanina</li>
<li>L85 &ndash; Outro espessamento epid&eacute;rmico</li>
<li>L853 &ndash; Xerose cut&acirc;nea</li>
<li>L90 &ndash; Perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L908 &ndash; Outras perturba&ccedil;&otilde;es atr&oacute;ficas da pele</li>
<li>L909 &ndash; Perturba&ccedil;&atilde;o atr&oacute;ficas da pele sem outra especifica&ccedil;&atilde;o</li>
<li>L987 &ndash; Pele e tecido subcut&acirc;neo excessivo e redundante</li>
<li>L989 &ndash; Afe&ccedil;&otilde;es da pele e do tecido subcut&acirc;neo, n&atilde;o especificadas</li>
</ul>

<p>Que originam e causam</p>
<ul>
<li>Sintomas depressivos</li>
<li>Ansiedade</li>
<li>Ins&oacute;nia e/ou perturba&ccedil;&otilde;es do sono</li>
<li>Irritabilidade</li>
<li>Dificuldade de desempenhar as atividades habituais</li>
<li>Baixa vitalidade, energia e tranquilidade</li>
<li>Estado de infelicidade</li>
<li>Incapacidade de responder &agrave;s adversidades</li>
<li>Perturba&ccedil;&atilde;o das rela&ccedil;&otilde;es com os demais</li>
<li>Dificuldade em sentir-se bem consigo pr&oacute;prio</li>
<li>Aus&ecirc;ncia de um estado de completo bem-estar f&iacute;sico, mental e social</li>
</ul>
<p>O que conforme a ICD 10(Classifica&ccedil;&atilde;o Internacional de doen&ccedil;as da Organiza&ccedil;&atilde;o Mundial de Sa&uacute;de) :</p>
<ul>
<li>F39 &ndash; Perturba&ccedil;&atilde;o do humor [afetivo], sem outra especifica&ccedil;&atilde;o</li>
<li>F419 &ndash; Estado de ansiedade, sem outra especifica&ccedil;&atilde;o</li>
<li>F51 &ndash; Transtornos n&atilde;o-org&acirc;nicos do sono devidos a fatores emocionais</li>
<li>F518 &ndash; Outros transtornos do sono devidos a fatores n&atilde;o-org&acirc;nicos</li>
</ul>
    </span>
<p style="text-align:left;"><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
<p style="text-align:left;"><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
<p style="text-align:left;"><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
<p style="text-align:left;"><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
';

// Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="70">
        </td>
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="70">
        </td>
    </tr>
</table>';


// Adiciona o conteúdo na terceira página
$pdf->writeHTML($html, true, false, true, false, '');

// Verifica se o consentimento de imagem foi removido
$remover_consentimento = isset($_POST["remover_consentimento_imagem"]) && $_POST["remover_consentimento_imagem"] == '1';

// Adiciona uma nova página para consentimento de imagem SE o consentimento NÃO foi removido
if (!$remover_consentimento) {
    // Adiciona uma nova página
    $pdf->AddPage();

    // Define a fonte
    $pdf->SetFont('helvetica', '', 8);

    // Conteúdo da quarta página (Consentimento de Imagem)
    $html = '
    <label style="font-size:18px;" for="consentimento_imagem"><u>Consentimento de Imagem</u></label>
                            <p>' . htmlspecialchars($form_data["nome_completo"]) . '  <strong>DECLARA</strong>, para os devidos efeitos:</p>

<ol>
<li>Que, no &acirc;mbito do procedimento m&eacute;dico ' . htmlspecialchars($form_data["procedimento_type"]) . '<sup>&reg;</sup> realizado com Dr Vitor Figueiredo ou com outro m&eacute;dico sob a dire&ccedil;&atilde;o t&eacute;cnica e cl&iacute;nica daquele, existe capta&ccedil;&atilde;o de imagens (fotografia e/ou v&iacute;deo), antes e depois de ocorrer o procedimento m&eacute;dico, capta&ccedil;&atilde;o essa promovida pelo Dr,Vitor Figueiredo e da sua responsabilidade exclusiva, com a qual concorda e na qual expressamente consente.</li>
<li>Ter sido informado que quaisquer materiais resultantes da capta&ccedil;&atilde;o de imagens (doravante designados apenas por &ldquo;Materiais&rdquo;) ser&atilde;o utilizados, sem identifica&ccedil;&atilde;o e sob reserva de confidencialidade a todo o tempo, para fins did&aacute;ticos, formativos e cient&iacute;ficos, bem como para fins publicit&aacute;rios ou comerciais, em qualquer meio de difus&atilde;o e comunica&ccedil;&atilde;o interno ou externo, a n&iacute;vel nacional ou internacional, atrav&eacute;s de quaisquer canais (incluindo digitais), nomeadamente televis&atilde;o, imprensa escrita, internet, redes sociais e outros existentes ou que venham a existir.</li>
<li>Conceder ao Dr. Vitor Manuel Figueiredo uma autoriza&ccedil;&atilde;o expressa, gratuita e por 10 (dez) anos para utiliza&ccedil;&atilde;o, por qualquer forma, e comunica&ccedil;&atilde;o ao p&uacute;blico, dos Materiais para os fins e atrav&eacute;s dos meios acima referidos, com vista &agrave; sua explora&ccedil;&atilde;o a n&iacute;vel mundial, sem limita&ccedil;&otilde;es de nenhum tipo e sem necessidade de obter nenhum consentimento ou autoriza&ccedil;&atilde;o posterior em rela&ccedil;&atilde;o ao uso que se fa&ccedil;a dos mesmos.</li>
<li>Conceder &agrave; Ready Point, Lda, que usa comercialmente a designa&ccedil;&atilde;o &ldquo;Ageless&ndash;Anti Aging Center&rdquo; (doravante &ldquo;Ageless&rdquo;), uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., a qual caducar&aacute; necess&aacute;ria e automaticamente se e quando o Dr. Vitor Manuel Figueiredo deixar de ser s&oacute;cio ou de integrar profissionalmente (o que primeiro ocorrer) tal sociedade, a qual deixar&aacute; de poder utilizar os Materiais. Para clarifica&ccedil;&atilde;o, a refer&ecirc;ncia feita neste documento a &ldquo;Ageless&rdquo; reporta-se exclusivamente &agrave; Ready Point, Lda.</li>
<li>Os Materiais podem ser utilizados no &acirc;mbito de qualquer parceria que a Ready Point Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Conceder &agrave; Global Metik Lda Lda. uma autoriza&ccedil;&atilde;o nos mesmos termos da prevista no n&uacute;mero 3., podendo os Materiais ser utilizados no &acirc;mbito de qualquer parceria que a Global Metik, Lda. estabele&ccedil;a com terceiros, sob qualquer marca ou designa&ccedil;&atilde;o comercial, enquanto tal parceria se mantiver e n&atilde;o podendo tais terceiros utilizar individualmente qualquer dos Materiais.</li>
<li>Que est&aacute; ciente do seu direito, nos termos do C&oacute;digo Civil, de revogar esta autoriza&ccedil;&atilde;o a todo o tempo, ainda que com obriga&ccedil;&atilde;o de indemnizar os danos e preju&iacute;zos causados a qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, tendo em conta os investimentos efetuados por estes, neles se incluindo, nomeadamente e sem excluir, os custos de remo&ccedil;&atilde;o, se poss&iacute;vel, da sua imagem dos Materiais ou de destrui&ccedil;&atilde;o dos Materiais;</li>
<li>N&atilde;o ceder futuramente, total ou parcialmente, os direitos aqui mencionados a qualquer outra pessoa f&iacute;sica ou jur&iacute;dica, de modo impeditivo ou que, de qualquer forma, interfira com os direitos acima concedidos;</li>
<li>Eximir qualquer das pessoas, singulares ou coletivas, a quem foi conferida autoriza&ccedil;&atilde;o, de todas as responsabilidades que possam resultar do exerc&iacute;cio dos direitos aqui concedidos e indemniz&aacute;-los por todos os danos resultantes do incumprimento dos compromissos aqui obtidos ou da inexatid&atilde;o das declara&ccedil;&otilde;es efetuadas;</li>
<li>Relativamente ao tratamento do seu dado pessoal imagem, ter sido informado que tal tratamento ser&aacute; da responsabilidade do Dr. Vitor Manuel Figueiredo, da Ageless ou da Global Metik, Lda., cada um enquanto respons&aacute;vel independente pelo tratamento de dados, ser&aacute; realizado para as finalidades acima indicadas e, ainda, que:</li>
<li>O tratamento do dado pessoal imagem n&atilde;o constitui uma obriga&ccedil;&atilde;o legal ou contratual, n&atilde;o estando o paciente obrigado a fornecer a sua imagem e n&atilde;o havendo quaisquer consequ&ecirc;ncias caso n&atilde;o o forne&ccedil;a;</li>
<li>As imagens do paciente ser&atilde;o mantidas pelo per&iacute;odo que se revelar estritamente necess&aacute;rio tendo em considera&ccedil;&atilde;o as finalidades supra indicadas.</li>
<li>Os dados pessoais utilizados no &acirc;mbito das parcerias poder&atilde;o ser transmitidos aos parceiros. Sem preju&iacute;zo, os dados poder&atilde;o ser acedidos por terceiros no &acirc;mbito de presta&ccedil;&otilde;es de servi&ccedil;os de tecnologias de informa&ccedil;&atilde;o ou outras, sendo que tais terceiros tratar&atilde;o os dados em nome do respons&aacute;vel pelo tratamento e de acordo com instru&ccedil;&otilde;es do mesmo.</li>
<li>Pode, a qualquer momento, retirar o seu consentimento para a capta&ccedil;&atilde;o e utiliza&ccedil;&atilde;o de imagens, sem que tal comprometa, no entanto, a licitude do tratamento realizado com base no consentimento previamente prestado.</li>
<li>Tem o direito de acesso, retifica&ccedil;&atilde;o, apagamento, portabilidade, limita&ccedil;&atilde;o e oposi&ccedil;&atilde;o ao tratamento dos seus dados pessoais e de retirar o consentimento, podendo exercer qualquer destes direitos mediante pedido escrito para Ageless, Via do Oriente, Lote 8, 5.03.01C, Escrit&oacute;rios 1,2,3,4, Edif&iacute;cio Tibre, 1990 - 514 Lisboa. Tem igualmente direito de apresentar uma reclama&ccedil;&atilde;o &agrave; Comiss&atilde;o Nacional de Prote&ccedil;&atilde;o de Dados (www.cnpd.pt).</li>
</ol>
<p>&nbsp;</p>
    <p><strong>Data</strong>: ' . htmlspecialchars($form_data["data"]) . '</p>
    <p><strong>Nome da(o) paciente</strong>: <u>' . htmlspecialchars($form_data["nome_completo"]) . '</u></p>
    <p><strong>B.I/C.C./Passaporte Nº</strong>: <u>' . htmlspecialchars($form_data["numero_documento"]) . '</u> &nbsp; <strong>Validade</strong>: <u>' . htmlspecialchars($form_data["validade_documento"]) . '</u></p>
    <p><strong>Nome do Médico</strong>: ' . htmlspecialchars($form_data["medico"]) . '</p>
    ';

    // Agora adicione as assinaturas ao mesmo conteúdo, sem redefinir $html
$html .= '
<table style="width: 100%; text-align:center;">
    <tr>
        <td>
            <h4>Assinatura do Paciente:</h4>
            <img src="' . $form_data['signature'] . '" alt="Assinatura" height="50">
        </td>';
        
if ($medico_signature_file) {
    $html .= '
        <td>
            <h4>Assinatura do Médico:</h4>
            <img src="' . $medico_signature_file . '" alt="Assinatura do Médico" height="50">
        </td>';
}

$html .= '
    </tr>
</table>';


    // Adiciona o conteúdo na quarta página
    $pdf->writeHTML($html, true, false, true, false, '');
}
                break;

    default:
    // Caso padrão (fallback)
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    $html = '
    <h4>Documento de <b>' . htmlspecialchars($form_data["nome_completo"]) . '</b></h4>
    <p>Este é um documento genérico para o tipo de formulário não identificado: <b>' . htmlspecialchars($form_data["form_type"]) . '</b>.</p>';
    $pdf->writeHTML($html, true, false, true, false, '');
                break;
    }

    $pdf->writeHTML($html, true, false, true, false, '');

    // Gera o PDF
    $pdf->Output($pdf_filename, 'D');
}

// Gera o PDF se solicitado
if (isset($_GET['generate_pdf'])) {
    gerarPDF($form_data);
    exit;
}

// Fecha a conexão
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Formulário</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <style>
        body {
            background-color: #f1dbb1;;
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
                        <h3 class="text-center font-weight-light my-4">Detalhes do Formulário</h3>
                    </div>
                    <div class="card-body">
                        <h4>Documento de <b><?php echo htmlspecialchars($form_data["nome_completo"]); ?></b></h4>
                        <p>
                            No dia <strong><?php echo htmlspecialchars($form_data["data"]); ?>, <?php echo htmlspecialchars($form_data["nome_completo"]); ?></strong>, Assinou o documento: <strong><?php echo htmlspecialchars($form_data["form_type"]); ?></strong>.<br>
                            Realizado pelo médico <?php echo htmlspecialchars($form_data["medico"]); ?>. O número do documento de identificação é <?php echo htmlspecialchars($form_data["numero_documento"]); ?>, com validade até <?php echo htmlspecialchars($form_data["validade_documento"]); ?>.
                        </p>
                        <p>Assinatura:</p>
                        <img src="<?php echo htmlspecialchars($form_data["signature"]); ?>" alt="Assinatura" height="100"><br><br>
                        
                        <a href="view_forms.php" class="btn btn-primary">Voltar para a Lista</a><br><br>
                        <a href="view_form.php?id=<?php echo $form_id; ?>&generate_pdf=1" class="btn btn-secondary">Gerar PDF novamente</a>
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

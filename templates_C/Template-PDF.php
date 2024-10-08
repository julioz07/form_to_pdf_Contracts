<?php
// Adiciona uma página
$pdf->AddPage();

// Define a fonte
$pdf->SetFont('helvetica', '', 9);

// Conteúdo do PDF com o conteúdo e identificadores dinâmicos
$html = '
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:18px;font-weight:bold;">CONSENTIMENTO INFORMADO PARA A REALIZAÇÃO DE PEELING</span></h1>

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
<h1 style="break-after:avoid;margin:12pt 0cm 3pt;tab-stops:199.35pt;text-align:right;"><span style="font-size:18px;">AUTORIZAÇÃO PARA A REALIZAÇÃO DE PEELING</span></h1>
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
<p style="margin:0cm;text-align:right;"><span style="font-size:18px;font-weight:bold;">DIAGNÓSTICO MÉDICO PARA A REALIZAÇÃO DE PEELING</span></p>
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
            <img src="' . $signature_file_patient . '" alt="Assinatura" height="70">
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
            <img src="' . $signature_file_patient . '" alt="Assinatura" height="50">
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

?>
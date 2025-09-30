
<!-- Footer CI (Concentimento de imagem + btn assinar) -->

 <!-- Consentimento de imagem (visível por padrão) -->
                        <div class="form-group">
                            <label for="consentimento_imagem">Consentimento de Imagem</label>
                            <p style="margin:6.0pt 0cm 0cm;text-align:justify;"><span style="font-size:10.0pt;"><?php echo htmlspecialchars($form_data["nome_completo"]); ?>, expressamente <strong>DECLARA</strong>, para os devidos efeitos:</span></p>
<p style="line-height:12.0pt;margin:0cm 0cm 0cm 21.3pt;text-align:justify;text-indent:-21.3pt;"><span style="font-size:12pt;"><span style="font-size:10.0pt;"></span></span></p>
<ol style="padding-left:28.4px;">
   // conteudo do seu concentimento
<p style="margin:0cm;text-align:justify;">&nbsp;</p>
                        </div>

                         <!-- Formulário para submissão -->
                <form action="signature.php" method="post">
    <!-- Checkbox para permitir a remoção do consentimento -->
                 <div class="form-group">
                     <label for="remover_consentimento_imagem">
                        <input type="checkbox" name="remover_consentimento_imagem" id="remover_consentimento_imagem" value="1">
                        Remover consentimento de imagem
                    </label>
                </div>

    <!-- Campo oculto para o tipo de formulário -->
                <input type="hidden" name="form_type" value="<?php echo htmlspecialchars($form_data["form_type"]); ?>">

    <!-- Botão de submissão -->
                <button type="submit" class="btn btn-success">Assinar</button>
            </form><br>

    <!-- Link para voltar à página inicial -->
                <a href="welcome.php" class="btn btn-primary">Voltar para a Página Inicial</a>
    <!-- Fim - Footer CI (Concentimento de imagem + btn assinar) -->

<!-- Footer (dados do cliente) -->
<span style="text-align:left"> 
<p style="margin:0cm;">&nbsp;</p>
<p><span style="font-size:14px;"><strong>Data</strong>:&nbsp;<?php echo htmlspecialchars($form_data["data"]); ?></span></p>
<p><span style="font-size:14px;"><strong>Nome da(o) paciente</strong>:&nbsp;<u><?php echo htmlspecialchars($form_data["nome_completo"]); ?></u></span></p>
<p><span style="font-size:14px;"><strong>B.I/C.C./Passaporte Nº</strong>: &nbsp;<u><?php echo htmlspecialchars($form_data["numero_documento"]); ?></u> &nbsp; &nbsp;&nbsp;<strong>Validade</strong>: &nbsp;<u><?php echo htmlspecialchars($form_data["validade_documento"]); ?></u></span></p>
<p><span style="font-size:14px;"><strong>Nome do Médico</strong>:&nbsp; <?php echo htmlspecialchars($form_data["medico"]); ?></span></p>
<p>&nbsp;</p></span>

<!-- Fim Footer (dados do cliente) -->

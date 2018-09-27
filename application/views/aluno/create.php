<br /><br />
<div class='row padding20 text-white relative' style="width: 95%; left: 3.5%">
	    <?php
    	echo"<div class='col-lg-12 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."usuario'>Usuários</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar usuário' : 'Novo usuário')."</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<div class='col-lg-12 padding background_dark'>
		<div>
			<a href='javascript:window.history.go(-1)' title='Voltar'>
				<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
			</a>
		</div>
		<br /><br />
		<?php $atr = array("id" => "form_cadastro_$controller", "name" => "form_cadastro"); 
			echo form_open("$controller/store", $atr); 
			
		?>
		<div class="row"><!--ABRE A ROW QUE FECHA O CREATE_EDIT DE USUARIO-->
			<div class="col-lg-6">
				<div class='form-group'>
					<?php
						if(empty($obj['Id']))
							$method = "\"create\"";
						else
							$method = "\"edit\"";
						
						if(!empty($obj['Id']))
							$id = $obj['Id'];
						else
							$id = 0;

						echo"<select name='grupo_id' id='grupo_id' class='form-control padding0' onchange='Main.altera_tipo_cadastro_usuario(this.value,$id,$method)'>";
						echo"<option value='0' class='background_dark'>Selecione o tipo de usuário</option>";

						for($i = 0; $i < count($grupos_usuario); $i++)
						{
							$selected = "";
							if($grupos_usuario[$i]['Id'] == $type)
								$selected = "selected";
							echo"<option class='background_dark' $selected value='". $grupos_usuario[$i]['Id'] ."'>".$grupos_usuario[$i]['Nome_grupo']."</option>";
						}
						echo "</select>";
					?>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-grupo_id'></div>
				</div>
			</div>
			<?php
				$this->load->view("usuario/_create_edit",$obj);
				echo"<br />";
			?>
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group relative">
					<input maxlength="100" id="naturalidade" spellcheck="false" name="naturalidade" value='<?php echo (!empty($obj['Naturalidade']) ? $obj['Naturalidade']:''); ?>' type="text" class="input-material">
					<label for="naturalidade" class="label-material">Naturalidade</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-naturalidade'></div>
				</div>
			</div>
			<div class="col-lg-2">
				<div class='form-group'>
					<select name='uf' id='uf' class='form-control padding0'>
						<option value='0' class='background_dark'>UF</option>
						<option class='background_dark' value='MG'>MG</option>
					</select>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-uf'></div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group relative">
					<input maxlength="100" id="nome_mae" spellcheck="false" name="nome_mae" value='<?php echo (!empty($obj['Nome_mae']) ? $obj['Nome_mae']:''); ?>' type="text" class="input-material">
					<label for="nome_mae" class="label-material">Nome da mãe</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome_mae'></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group relative">
					<input maxlength="100" id="nome_pai" spellcheck="false" name="nome_pai" value='<?php echo (!empty($obj['Nome_pai']) ? $obj['Nome_pai']:''); ?>' type="text" class="input-material">
					<label for="nome_pai" class="label-material">Nome do pai</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome_pai'></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class='form-group'>
					<?php 

						$cor = array();
						$cor[0] = 'Branca';
						$cor[1] = 'Preta';
						$cor[2] = 'Parda';
						$cor[3] = 'Amarela';
						$cor[4] = 'Indigena';
						$cor[5] = 'Não declarada';
					?>
					<fieldset>
						<legend>&nbsp;Cor</legend>
						<?php 
							for($i = 0; $i < COUNT($cor); $i++)
							{
								echo"<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>";
									echo"<label class='form-check-label' id='cor$i'>";
								    	echo"<input name='cor' id='cor$i' value='0' type='radio'/> <span></span>".$cor[$i];
									echo"</label>";
								echo"</div>";
							}
						?>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-cor'></div>
					</fieldset>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<div class='form-group'>
					<fieldset>
						<legend>&nbsp;Portador de necessidades especiais</legend>
						<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
							<label class='form-check-label' id='necessidade_especial_sim'>
						    	<input name='necessidade_especial' id='necessidade_especial_sim' value='0' type='radio'/> <span></span>Sim
							</label>
						</div>
						<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
							<label class='form-check-label' id='necessidade_especial_nao'>
						    	<input name='necessidade_especial' id='necessidade_especial_nao' value='0' type='radio'/> <span></span>Não
							</label>
						</div>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-necessidade_especial'></div>
					</fieldset>
				</div>
			</div>
			<div class="col-lg-4">
				<br />
				<div class="form-group relative">
					<input maxlength="100" id="Identificacao" spellcheck="false" name="Identificacao" value='<?php echo (!empty($obj['Identificacao']) ? $obj['Identificacao']:''); ?>' type="text" class="input-material">
					<label for="Identificacao" class="label-material">Identificação</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-identificacao'></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<div class='form-group'>
					<?php 
						$sit = array();
						$sit[0] = '1º ano';
						$sit[1] = '2º ano';
						$sit[2] = '3º ano';
						$sit[3] = 'Concluinte';
					?>
					<fieldset>
						<legend>&nbsp;Situação do ensino médio ao iniciar o CEP	</legend>
						<?php 
							for($i = 0; $i < COUNT($sit); $i++)
							{
								echo"<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>";
									echo"<label class='form-check-label' id='situacao_em$i'>";
								    	echo"<input name='situacao_em' id='situacao_em$i' value='0' type='radio'/> <span></span>".$sit[$i];
									echo"</label>";
								echo"</div>";
							}
						?>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-situacao_em'></div>
					</fieldset>
				</div>
			</div>
			<div class="col-lg-4">
				<div class='form-group'>
					<fieldset>
						<legend>&nbsp;Escola</legend>
						<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
							<label class='form-check-label' id='escola_publica'>
						    	<input name='escola' id='escola_publica' value='0' type='radio'/> <span></span>Pública
							</label>
						</div>
						<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
							<label class='form-check-label' id='escola_privada'>
						    	<input name='escola' id='escola_privada' value='0' type='radio'/> <span></span>Privada
							</label>
						</div>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-escola'></div>
					</fieldset>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group relative">
					<input maxlength="100" id="simade" spellcheck="false" name="simade" value='<?php echo (!empty($obj['Simade']) ? $obj['Simade']:''); ?>' type="text" class="input-material">
					<label for="simade" class="label-material">Simade</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-simade'></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="100" id="cpf" spellcheck="false" name="cpf" value='<?php echo (!empty($obj['Cpf']) ? $obj['Cpf']:''); ?>' type="text" class="input-material">
					<label for="cpf" class="label-material">CPF</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-cpf'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="100" id="rg" spellcheck="false" name="rg" value='<?php echo (!empty($obj['Rg']) ? $obj['Rg']:''); ?>' type="text" class="input-material">
					<label for="rg" class="label-material">RG</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-rg'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="100" id="orgao_expedidor" spellcheck="false" name="orgao_expedidor" value='<?php echo (!empty($obj['Orgao_expedidor']) ? $obj['Orgao_expedidor']:''); ?>' type="text" class="input-material">
					<label for="orgao_expedidor" class="label-material">Órgão expedidor</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-orgao_expedidor'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group relative" id="data1">
					<input maxlength="100" id="data_expedicao" spellcheck="false" name="data_expedicao" value='<?php echo (!empty($obj['Data_expedicao']) ? $obj['Data_expedicao']:''); ?>' type="text" class="input-material">
					<label for="data_expedicao" class="label-material">Data da expedição</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-data_expedicao'></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="100" id="titulo_eleitor" spellcheck="false" name="titulo_eleitor" value='<?php echo (!empty($obj['Titulo_eleitor']) ? $obj['Titulo_eleitor']:''); ?>' type="text" class="input-material">
					<label for="titulo_eleitor" class="label-material">Título de eleitor</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-titulo_eleitor'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="100" id="zona_eleitoral" spellcheck="false" name="zona_eleitoral" value='<?php echo (!empty($obj['Zona_eleitoral']) ? $obj['Zona_eleitoral']:''); ?>' type="text" class="input-material">
					<label for="zona_eleitoral" class="label-material">Zona</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-zona_eleitoral'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="100" id="secao_eleitoral" spellcheck="false" name="secao_eleitoral" value='<?php echo (!empty($obj['Secao_eleitoral']) ? $obj['Secao_eleitoral']:''); ?>' type="text" class="input-material">
					<label for="secao_eleitoral" class="label-material">Seção</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-secao_eleitoral'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class='form-group'>
					<select name='uf_titulo' id='uf_titulo' class='form-control padding0'>
						<option value='0' class='background_dark'>UF</option>
						<option class='background_dark' value='MG'>MG</option>
					</select>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-uf_titulo'></div>
				</div>
			</div>
		</div>
		<fieldset>
		<legend>&nbsp;Endereço</legend>
			<div class="row">
				<div class="col-lg-8">
					<div class="form-group relative">
						<input maxlength="100" id="rua" spellcheck="false" name="rua" value='<?php echo (!empty($obj['Rua']) ? $obj['Rua']:''); ?>' type="text" class="input-material">
						<label for="rua" class="label-material">Rua</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-rua'></div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-group relative">
						<input maxlength="100" id="bairro" spellcheck="false" name="bairro" value='<?php echo (!empty($obj['Bairro']) ? $obj['Bairro']:''); ?>' type="text" class="input-material">
						<label for="bairro" class="label-material">Bairro</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-bairro'></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4">
					<div class='form-group'>
						<fieldset>
							<legend>&nbsp;Zona</legend>
							<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
								<label class='form-check-label' id='zona_urbana'>
							    	<input name='zona' id='zona_urbana' value='0' type='radio'/> <span></span>Urbana
								</label>
							</div>
							<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
								<label class='form-check-label' id='zona_rural'>
							    	<input name='zona' id='zona_rural' value='0' type='radio'/> <span></span>Rural
								</label>
							</div>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-zona'></div>
						</fieldset>
					</div>
				</div>
				<div class="col-lg-4">
					<div class='form-group'>
						<fieldset>
							<legend>&nbsp;Transporte público</legend>
							<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
								<label class='form-check-label' id='transp_publico_sim'>
							    	<input name='transp_publico' id='transp_publico_sim' value='0' type='radio'/> <span></span>Sim
								</label>
							</div>
							<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
								<label class='form-check-label' id='transp_publico_nao'>
							    	<input name='transp_publico' id='transp_publico_nao' value='0' type='radio'/> <span></span>Não
								</label>
							</div>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-transp_publico'></div>
						</fieldset>
					</div>
				</div>
				<div class="col-lg-4">
					<br />
					<div class="form-group relative">
						<input maxlength="100" id="municipio" spellcheck="false" name="municipio" value='<?php echo (!empty($obj['Municipio']) ? $obj['Municipio']:''); ?>' type="text" class="input-material">
						<label for="municipio" class="label-material">Município</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-municipio'></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-3">
					<div class='form-group'>
						<select name='uf_endereco' id='uf_endereco' class='form-control padding0'>
							<option value='0' class='background_dark'>UF</option>
							<option class='background_dark' value='MG'>MG</option>
						</select>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-uf_endereco'></div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group relative">
						<input maxlength="100" id="cep" spellcheck="false" name="cep" value='<?php echo (!empty($obj['Cep']) ? $obj['Cep']:''); ?>' type="text" class="input-material">
						<label for="cep" class="label-material">CEP</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-cep'></div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group relative">
						<input maxlength="100" id="telefone_aluno" spellcheck="false" name="telefone_aluno" value='<?php echo (!empty($obj['Telefone_aluno']) ? $obj['Telefone_aluno']:''); ?>' type="text" class="input-material">
						<label for="telefone_aluno" class="label-material">Telefone do aluno</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-telefone_aluno'></div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group relative">
						<input maxlength="100" id="telefone_responsavel" spellcheck="false" name="telefone_responsavel" value='<?php echo (!empty($obj['Telefone_responsavel']) ? $obj['Telefone_responsavel']:''); ?>' type="text" class="input-material">
						<label for="telefone_responsavel" class="label-material">Telefone do responsável</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-telefone_responsavel'></div>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset class="my-3">
			<legend>&nbsp;Documentos do aluno</legend>
			<div class="row">
				<div class="col-lg-6">
					<?php
						$d = (int)(COUNT($lista_documentos_aluno) / 2);

						for($i = 0; $i < (COUNT($lista_documentos_aluno) - $d); $i++)
						{
							echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
								echo "<label for='documento_aluno$i'>";
									echo "<input type='checkbox' id='documento_aluno$i' name='documento_aluno$i' value='1' /><span></span>";
										echo $lista_documentos_aluno[$i]['Nome_doc'];
								echo "</label>";
							echo"</div>";

						}
					?>
				</div>
				<div class="col-lg-6">
					<?php
						$d = (int)(COUNT($lista_documentos_aluno) / 2);

						for($i = (COUNT($lista_documentos_aluno) - $d); $i < COUNT($lista_documentos_aluno); $i++)
						{
							echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
								echo "<label for='documento_aluno$i'>";
									echo "<input type='checkbox' id='documento_aluno$i' name='documento_aluno$i' value='1' /><span></span>";
										echo $lista_documentos_aluno[$i]['Nome_doc'];
								echo "</label>";
							echo"</div>";

						}
					?>
				</div>
			</div>
		</fieldset>
		<fieldset class="my-3">
			<legend>&nbsp;Documentos do responsável</legend>
			<div class="row">
				<div class="col-lg-6">
					<?php
						for($i = 0; $i < COUNT($lista_documentos_responsavel); $i++)
						{
							echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
								echo "<label for='documento_responsavel$i'>";
									echo "<input type='checkbox' id='documento_responsavel$i' name='documento_responsavel$i' value='1' /><span></span>";
										echo $lista_documentos_responsavel[$i]['Nome_doc'];
								echo "</label>";
							echo"</div>";

						}
					?>
				</div>
			</div>
		</fieldset>








		<BR />
		<div class="row">
			<div class="col-lg-4">
				<div class='form-group'>
					<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
						<?php
							$checked = "";
							if($obj['Ativo'] == 1)
								$checked = "checked";
							
							echo"<label for='conta_ativa' class=''>";
								echo "<input type='checkbox' $checked id='conta_ativa' name='conta_ativa' value='1' /><span></span> Conta ativa";
							echo"</label>";
						?>
					</div>
				</div>
			</div>
			<div class="col-lg-8 ">
				<div class='form-group'>
					<?php
						if($obj['Email_notifica_nova_conta'] == 0)
						{
							echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
								echo"<label for='email_notifica_nova_conta' class=''>";
									echo "<input type='checkbox' id='email_notifica_nova_conta' name='email_notifica_nova_conta' value='1' /><span></span> Enviar e-mail de notificação";
								echo"</label>";
							echo "</div>";
						}
						else
							echo "<span class='glyphicon glyphicon-ok-sign'></span> O E-mail de notificação já foi enviado para este usuário.";
					?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class='form-group'>
					<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
						<label for='inscricao_aluno'>
							<input type='checkbox' id='inscricao_aluno' checked name='inscricao_aluno' value='1' /><span></span> Salvar e ir para a inscrição do aluno.
						</label>
					</div>
				</div>
			</div>
		</div>
		<?php
			if(empty($obj['Id']))
				echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
			else
				echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
		?>
		</form>
	</div>
</div> 
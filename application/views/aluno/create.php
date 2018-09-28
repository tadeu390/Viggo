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
			echo "<input type='hidden' id='aluno_id' name='aluno_id' value='".$obj_aluno['Id']."'>";
			echo "<input type='hidden' id='endereco_id' name='endereco_id' value='".$Endereco[0]['Endereco_id']."'>";
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
					<input maxlength="100" id="naturalidade" spellcheck="false" name="naturalidade" value='<?php echo (!empty($obj_aluno['Naturalidade']) ? $obj_aluno['Naturalidade']:''); ?>' type="text" class="input-material">
					<label for="naturalidade" class="label-material">Naturalidade</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-naturalidade'></div>
				</div>
			</div>
			<div class="col-lg-2">
				<div class='form-group'>
					<?php
						$uf = array();
						$uf[0] = 'AC';
						$uf[1] = 'AL';
						$uf[2] = 'AP';
						$uf[3] = 'AM';
						$uf[4] = 'BA';
						$uf[5] = 'CE';
						$uf[6] = 'DF';
						$uf[7] = 'ES';
						$uf[8] = 'GO';
						$uf[9] = 'MA';
						$uf[10] = 'MT';
						$uf[11] = 'MS';
						$uf[12] = 'MG';
						$uf[13] = 'PA';
						$uf[14] = 'PR';
						$uf[15] = 'PE';
						$uf[16] = 'PI';
						$uf[17] = 'RR';
						$uf[18] = 'RO';
						$uf[19] = 'RJ';
						$uf[20] = 'RN';
						$uf[21] = 'RS';
						$uf[22] = 'SC';
						$uf[23] = 'SP';
						$uf[24] = 'SE';
						$uf[25] = 'TO';
					?>
					<select name='uf' id='uf' class='form-control padding0'>
						<option value='0' class='background_dark'>UF</option>
						<?php 
							for($i = 0; $i < COUNT($uf); $i++)
							{	
								$selected = '';
								if($obj_aluno['Uf'] == $uf[$i])
									$selected = 'selected';
								echo"<option $selected class='background_dark' value='".$uf[$i]."'>".$uf[$i]."</option>";
							}
						?>
					</select>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-uf'></div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group relative">
					<input maxlength="100" id="nome_mae" spellcheck="false" name="nome_mae" value='<?php echo (!empty($obj_aluno['Nome_mae']) ? $obj_aluno['Nome_mae']:''); ?>' type="text" class="input-material">
					<label for="nome_mae" class="label-material">Nome da mãe</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome_mae'></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group relative">
					<input maxlength="100" id="nome_pai" spellcheck="false" name="nome_pai" value='<?php echo (!empty($obj_aluno['Nome_pai']) ? $obj_aluno['Nome_pai']:''); ?>' type="text" class="input-material">
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
								$checked = '';
								if($obj_aluno['Cor'] == $cor[$i])
									$checked = 'checked';

								echo"<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>";
									echo"<label class='form-check-label' id='cor$i'>";
								    	echo"<input $checked name='cor' id='cor$i' value='".$cor[$i]."' type='radio'/> <span></span>".$cor[$i];
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
						    	<input name='necessidade_especial' <?php echo ($obj_aluno['Especial'] == 1 ? 'checked': ''); ?> id='necessidade_especial_sim' value='1' type='radio'/> <span></span>Sim
							</label>
						</div>
						<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
							<label class='form-check-label' id='necessidade_especial_nao'>
						    	<input name='necessidade_especial' <?php echo ($obj_aluno['Especial'] == '0' ? 'checked': ''); ?> id='necessidade_especial_nao' value='0' type='radio'/> <span></span>Não
							</label>
						</div>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-necessidade_especial'></div>
					</fieldset>
				</div>
			</div>
			<div class="col-lg-4">
				<br />
				<div class="form-group relative">
					<input maxlength="100" id="identificacao" spellcheck="false" name="identificacao" value='<?php echo (!empty($obj_aluno['Identificacao']) ? $obj_aluno['Identificacao']:''); ?>' type="text" class="input-material">
					<label for="identificacao" class="label-material">Identificação</label>
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
								$checked = '';
								if($obj_aluno['Sit_ensino_medio'] == $sit[$i])
									$checked = 'checked';

								echo"<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>";
									echo"<label class='form-check-label' id='situacao_em$i'>";
								    	echo"<input $checked name='situacao_em' id='situacao_em$i' value='".$sit[$i]."' type='radio'/> <span></span>".$sit[$i];
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
						    	<input name='escola' <?php echo ($obj_aluno['Escola'] == 'Pública' ? 'checked': ''); ?> id='escola_publica' value='Pública' type='radio'/> <span></span>Pública
							</label>
						</div>
						<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
							<label class='form-check-label' id='escola_privada'>
						    	<input name='escola' <?php echo ($obj_aluno['Escola'] == 'Privada' ? 'checked': ''); ?> id='escola_privada' value='Privada' type='radio'/> <span></span>Privada
							</label>
						</div>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-escola'></div>
					</fieldset>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="15" id="cpf" spellcheck="false" name="cpf" value='<?php echo (!empty($obj_aluno['Cpf']) ? $obj_aluno['Cpf']:''); ?>' type="text" class="input-material">
					<label for="cpf" class="label-material">CPF</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-cpf'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="20" id="rg" spellcheck="false" name="rg" value='<?php echo (!empty($obj_aluno['Rg']) ? $obj_aluno['Rg']:''); ?>' type="text" class="input-material">
					<label for="rg" class="label-material">RG</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-rg'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="20" id="orgao_expedidor" spellcheck="false" name="orgao_expedidor" value='<?php echo (!empty($obj_aluno['Orgao_expedidor']) ? $obj_aluno['Orgao_expedidor']:''); ?>' type="text" class="input-material">
					<label for="orgao_expedidor" class="label-material">Órgão expedidor</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-orgao_expedidor'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group relative" id="data1">
					<input id="data_expedicao" spellcheck="false" name="data_expedicao" value='<?php echo (!empty($obj_aluno['Data_expedicao_pt'] && $obj_aluno['Data_expedicao_pt'] !='00/00/0000') ? $obj_aluno['Data_expedicao_pt']:''); ?>' type="text" class="input-material">
					<label for="data_expedicao" class="label-material">Data da expedição</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-data_expedicao'></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="15" id="titulo_eleitor" spellcheck="false" name="titulo_eleitor" value='<?php echo (!empty($obj_aluno['Titulo_eleitor']) ? $obj_aluno['Titulo_eleitor']:''); ?>' type="text" class="input-material">
					<label for="titulo_eleitor" class="label-material">Título de eleitor</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-titulo_eleitor'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="5" id="zona_eleitoral" spellcheck="false" name="zona_eleitoral" value='<?php echo (!empty($obj_aluno['Zona_eleitoral']) ? $obj_aluno['Zona_eleitoral']:''); ?>' type="text" class="input-material">
					<label for="zona_eleitoral" class="label-material">Zona</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-zona_eleitoral'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group relative">
					<input maxlength="5" id="secao_eleitoral" spellcheck="false" name="secao_eleitoral" value='<?php echo (!empty($obj_aluno['Secao_eleitoral']) ? $obj_aluno['Secao_eleitoral']:''); ?>' type="text" class="input-material">
					<label for="secao_eleitoral" class="label-material">Seção</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-secao_eleitoral'></div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class='form-group'>
					<select name='uf_titulo' id='uf_titulo' class='form-control padding0'>
						<option value='0' class='background_dark'>UF</option>
						<?php 
							for($i = 0; $i < COUNT($uf); $i++)
							{
								$selected = '';
								if($obj_aluno['Uf_titulo'] == $uf[$i])
									$selected = 'selected';
								echo"<option $selected class='background_dark' value='".$uf[$i]."'>".$uf[$i]."</option>";
							}
						?>
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
						<input maxlength="100" id="rua" spellcheck="false" name="rua" value='<?php echo (!empty($Endereco[0]['Rua']) ? $Endereco[0]['Rua']:''); ?>' type="text" class="input-material">
						<label for="rua" class="label-material">Rua</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-rua'></div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-group relative">
						<input maxlength="40" id="bairro" spellcheck="false" name="bairro" value='<?php echo (!empty($Endereco[0]['Bairro']) ? $Endereco[0]['Bairro']:''); ?>' type="text" class="input-material">
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
							    	<input name='zona' <?php echo ($Endereco[0]['Zona'] == 'Urbana' ? 'checked': ''); ?> id='zona_urbana' value='Urbana' type='radio'/> <span></span>Urbana
								</label>
							</div>
							<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
								<label class='form-check-label' id='zona_rural'>
							    	<input name='zona' <?php echo ($Endereco[0]['Zona'] == 'Rural' ? 'checked': ''); ?> id='zona_rural' value='Rural' type='radio'/> <span></span>Rural
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
							    	<input name='transp_publico' <?php echo ($Endereco[0]['Transp_publico'] == 1 ? 'checked': ''); ?> id='transp_publico_sim' value='1' type='radio'/> <span></span>Sim
								</label>
							</div>
							<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked ml-4'>
								<label class='form-check-label' id='transp_publico_nao'>
							    	<input name='transp_publico' <?php echo ($Endereco[0]['Transp_publico'] == '0' ? 'checked': ''); ?> id='transp_publico_nao' value='0' type='radio'/> <span></span>Não
								</label>
							</div>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-transp_publico'></div>
						</fieldset>
					</div>
				</div>
				<div class="col-lg-4">
					<br />
					<div class="form-group relative">
						<input maxlength="50" id="municipio" spellcheck="false" name="municipio" value='<?php echo (!empty($Endereco[0]['Municipio']) ? $Endereco[0]['Municipio']:''); ?>' type="text" class="input-material">
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
							<?php 
								for($i = 0; $i < COUNT($uf); $i++)
								{
									$selected = '';
									if($Endereco[0]['Uf'] == $uf[$i])
										$selected = 'selected';
									echo"<option $selected class='background_dark' value='".$uf[$i]."'>".$uf[$i]."</option>";
								}
							?>
						</select>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-uf_Endereco[0]'></div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group relative">
						<input maxlength="15" id="cep" spellcheck="false" name="cep" value='<?php echo (!empty($Endereco[0]['Cep']) ? $Endereco[0]['Cep']:''); ?>' type="text" class="input-material">
						<label for="cep" class="label-material">CEP</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-cep'></div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group relative">
						<input maxlength="15" id="telefone_aluno" spellcheck="false" name="telefone_aluno" value='<?php echo (!empty($Endereco[0]['Telefone_aluno']) ? $Endereco[0]['Telefone_aluno']:''); ?>' type="text" class="input-material">
						<label for="telefone_aluno" class="label-material">Telefone do aluno</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-telefone_aluno'></div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group relative">
						<input maxlength="15" id="telefone_responsavel" spellcheck="false" name="telefone_responsavel" value='<?php echo (!empty($Endereco[0]['Telefone_responsavel']) ? $Endereco[0]['Telefone_responsavel']:''); ?>' type="text" class="input-material">
						<label for="telefone_responsavel" class="label-material">Telefone do responsável</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-telefone_responsavel'></div>
					</div>
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
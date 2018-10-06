<br /><br />
<?php $this->load->helper("mstring");?>
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Inscrição</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar inscrição' : 'Nova inscrição')."</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<div class='col-lg-10 offset-lg-1 padding background_dark'>
		<div>
			<a href='javascript:window.history.go(-1)' title='Voltar'>
				<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
			</a>
		</div>
		<br /><br />
			<?php $atr = array("id" => "form_cadastro_$controller", "name" => "form_cadastro"); 
				echo form_open("$controller/store", $atr);
			?>
			<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['Id'])) echo $obj['Id']; ?>'/>
			<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
			


		<div class="row">
			<div class="col-lg-6">
				<?php if($obj['Editar_apagar'] != 'bloqueado'): ?>
					<div class='form-group text-dark'>
						<div class='input-group-addon' style="color: #8a8d93;">Aluno</div>
						<select name='aluno_id' id='aluno_id' class='form-control chosen-select ' style='padding-left: 0px;'>
							<option value='0'>Selecione</option>
							<?php
							for ($i = 0; $i < count($lista_alunos); $i++)
							{
								$selected = "";
								if ($lista_alunos[$i]['Usuario_id'] == $obj['Usuario_id'] || $lista_alunos[$i]['Usuario_id'] == $this->input->cookie('inscricao_aluno'))
									$selected = "selected";
								echo "<option title='".$lista_alunos[$i]['Nome_ra']."' $selected value='" . $lista_alunos[$i]['Id'] . "'>";
									echo mstring::corta_string($lista_alunos[$i]['Nome_ra'], 45);
								echo "</option>";
							}
							?>
						</select>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-aluno_id'></div>
					</div>
				<?php else : ?>
					<div class='form-group relative'>
						<input maxlength="100" readonly="readonly" type='text' class="input-material" value='<?php echo $obj['Nome_aluno'];?>'>
						<label for="nome" class="label-material">Aluno</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-lg-6">
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				<?php if($obj['Editar_apagar'] != 'bloqueado'): ?>
					<div class='form-group'>
						<div class='input-group-addon' style="color: #8a8d93;">Curso</div>
						<select name='curso_id' id='curso_id' class='form-control' style='padding-left: 0px;'>
							<option value='0' style='background-color: #393836;'>Selecione</option>
							<?php
							for ($i = 0; $i < count($lista_cursos); $i++)
							{
								$selected = "";
								if ($lista_cursos[$i]['Id'] == $obj['Curso_id'])
									$selected = "selected";
								echo "<option class='background_dark' $selected value='" . $lista_cursos[$i]['Id'] . "'>" . $lista_cursos[$i]['Nome_curso'] . "</option>";
							}
							?>
						</select>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-curso_id'></div>
					</div>
				<?php else : ?>
					<div class='form-group relative'>
						<input maxlength="100" readonly="readonly" type='text' class="input-material" value='<?php echo $obj['Nome_curso'];?>'>
						<label for="nome" class="label-material">Curso</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-lg-6">
				<?php if($obj['Editar_apagar'] != 'bloqueado'): ?>
					<div class="form-group relative">
						<div class='form-group'>
							<div class='input-group-addon' style="color: #8a8d93;">Modalidade</div>
							<select name='modalidade_id' id='modalidade_id' class='form-control' style='padding-left: 0px;'>
								<option value='0' style='background-color: #393836;'>Selecione</option>
								<?php
								for ($i = 0; $i < count($lista_modalidades); $i++)
								{
									$selected = "";
									if ($lista_modalidades[$i]['Id'] == $obj['Modalidade_id'])
										$selected = "selected";
									echo "<option class='background_dark' $selected value='" . $lista_modalidades[$i]['Id'] . "'>" . $lista_modalidades[$i]['Nome_modalidade'] . "</option>";
								}
								?>
							</select>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-modalidade_id'></div>
						</div>
					</div>
				<?php else : ?>
					<div class='form-group relative'>
						<input maxlength="100" readonly="readonly" type='text' class="input-material" value='<?php echo $obj['Nome_modalidade'];?>'>
						<label for="nome" class="label-material">Modalidade</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<fieldset class="my-3">
			<legend>&nbsp;Documentos do aluno</legend>
			<div class="row">
				<div class="col-lg-6">
					<?php
						$d = (int)(COUNT($lista_documentos_aluno) / 2);

						for($i = 0; $i < (COUNT($lista_documentos_aluno) - $d); $i++)
						{
							$checked = '';
							for($j = 0; $j < COUNT($lista_doc_inscricao); $j++)
							{
								if($lista_documentos_aluno[$i]['Doc_id'] == $lista_doc_inscricao[$j]['Doc_id'])
								{
									$checked = 'checked';
									break;
								}
							}
							echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
								echo "<label for='documento_aluno$i'>";
									echo "<input type='checkbox' $checked id='documento_aluno$i' name='documento[]' value='".$lista_documentos_aluno[$i]['Doc_id']."' /><span></span>";
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
							$checked = '';
							for($j = 0; $j < COUNT($lista_doc_inscricao); $j++)
							{
								if($lista_documentos_aluno[$i]['Doc_id'] == $lista_doc_inscricao[$j]['Doc_id'])
								{
									$checked = 'checked';
									break;
								}
							}
							echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
								echo "<label for='documento_aluno$i'>";
									echo "<input $checked type='checkbox' id='documento_aluno$i' name='documento[]' value='".$lista_documentos_aluno[$i]['Doc_id']."' /><span></span>";
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
							$nome_doc_outros = "";
							$checked = '';
							for($j = 0; $j < COUNT($lista_doc_inscricao); $j++)
							{
								if($lista_documentos_responsavel[$i]['Doc_id'] == $lista_doc_inscricao[$j]['Doc_id'])
								{
									$checked = 'checked';
									if($lista_documentos_responsavel[$i]['Nome_doc'] == 'RG Outros')
										$nome_doc_outros = $lista_doc_inscricao[$j]['Outros'];
									else if($lista_documentos_responsavel[$i]['Nome_doc'] == 'CPF Outros')
										$nome_doc_outros = $lista_doc_inscricao[$j]['Outros'];
									break;
								}
							}
							echo"<div class='row'>";
								echo"<div class='col-lg-6'>";
									echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
										echo "<label for='documento_responsavel$i'>";
											if($lista_documentos_responsavel[$i]['Nome_doc'] == 'RG Outros')
												echo "<input onchange='Main.estado_campo(\"rg_outro\");' $checked type='checkbox' id='documento_responsavel$i' name='documento[]' value='".$lista_documentos_responsavel[$i]['Doc_id']."' /><span></span>";
											else if($lista_documentos_responsavel[$i]['Nome_doc'] == 'CPF Outros')
												echo "<input onchange='Main.estado_campo(\"cpf_outro\");' $checked type='checkbox' id='documento_responsavel$i' name='documento[]' value='".$lista_documentos_responsavel[$i]['Doc_id']."' /><span></span>";
											else 
												echo "<input $checked type='checkbox' id='documento_responsavel$i' name='documento[]' value='".$lista_documentos_responsavel[$i]['Doc_id']."' /><span></span>";
												echo $lista_documentos_responsavel[$i]['Nome_doc'];
										echo "</label>";
									echo"</div>";
								echo"</div>";
								echo"<div class='col-lg-6 text-left'>";
									$disabled = 'disabled';
									if($lista_documentos_responsavel[$i]['Nome_doc'] == 'RG Outros')
									{
										if($checked == 'checked')
											$disabled = '';
										echo"<div class='form-group relative' style='margin-bottom: 0px;'>";
											echo"<input placeholder='Especifique' $disabled maxlength='15' id='rg_outro' spellcheck='false' name='rg_outro' value='".$nome_doc_outros."' type='text' class='input-material'>";
											//echo"<label for='rg_outro' class='label-material'>Especifique</label>";
											echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-rg_outro'></div>";
										echo"</div>";
									}
									else if($lista_documentos_responsavel[$i]['Nome_doc'] == 'CPF Outros')
									{
										if($checked == 'checked')
											$disabled = '';
										echo"<div class='form-group relative' style='margin-bottom: 0px;'>";
											echo"<input placeholder='Especifique' $disabled maxlength='15' id='cpf_outro' spellcheck='false' name='cpf_outro' value='".$nome_doc_outros."' type='text' class='input-material'>";
											//echo"<label for='cpf_outro' class='label-material'>Especifique</label>";
											echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-cpf_outro'></div>";
										echo"</div>";
									}
								echo"</div>";
							echo"</div>";
						}
					?>
				</div>
			</div>
		</fieldset>

		<div class='form-group'>
			<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
				<?php
				$checked = "";
				if (!empty($obj['Renovacao_matricula_id']))
					$checked = "checked";
				if($obj['Editar_apagar'] != 'bloqueado')
				{
					echo "<label for='matricular' style='color: #8a8d93;'>";
					echo "<input type='checkbox' $checked id='matricular' name='matricular' value='1' /><span></span> Confirmar matrícula";
					echo "</label>";
				}
				else 
					echo"Matricula ativa";
				?>
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
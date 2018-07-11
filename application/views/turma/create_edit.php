<br /><br />
<?php $this->load->helper("mstring");?>
<div class='row padding20 text-white relative' style="width: 95%; left: 3.5%">
	<?php
    	echo"<div class='col-lg-12 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."turma'>Turmas</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar turma' : 'Nova turma')."</li>";
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
			<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['Id'])) echo $obj['Id']; ?>'/>
			<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group relative">
						<input spellcheck="false" maxlength="20" id="nome" name="nome" value='<?php echo (!empty($obj['Nome_turma']) ? $obj['Nome_turma']:''); ?>' type="text" class="input-material">
						<label for="nome" class="label-material">Nome</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
					</div>
				</div>
				<div class="col-lg-6">
					
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class='form-group'>
						<div class='input-group-addon' style="color: #8a8d93;">Curso</div>
						<select onchange="Main.load_data_disciplina();" name='curso_id' id='curso_id' class='form-control' style='padding-left: 0px;'>
							<option value='0' style='background-color: #393836;'>Selecione</option>
							<?php
							for ($i = 0; $i < count($lista_cursos); $i++)
							{
								$selected = "";
								if ($lista_cursos[$i]['Id'] == $lista_disc_turma_header['Curso_id'])
									$selected = "selected";
								echo "<option class='background_dark' $selected value='" . $lista_cursos[$i]['Id'] . "'>" . $lista_cursos[$i]['Nome_curso'] . "</option>";
							}
							?>
						</select>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-curso_id'></div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class='form-group'>
						<div class='input-group-addon' style="color: #8a8d93;">Modalidade</div>
						<select name='modalidade_id' id='modalidade_id' class='form-control' style='padding-left: 0px;'>
							<option value='0' style='background-color: #393836;'>Selecione</option>
							<?php
							for ($i = 0; $i < count($lista_modalidades); $i++)
							{
								$selected = "";
								if ($lista_modalidades[$i]['Id'] == $lista_disc_turma_header['Modalidade_id'])
									$selected = "selected";
								echo "<option class='background_dark' $selected value='" . $lista_modalidades[$i]['Id'] . "'>" . $lista_modalidades[$i]['Nome_modalidade'] . "</option>";
							}
							?>
						</select>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-modalidade_id'></div>
					</div>
				</div>
			</div>

			<fieldset>
				<legend class='text-white'>&nbsp;Disciplinas</legend>
				<div class='disciplinas' id='disciplinas'>
					<?php
						if (!empty($obj['Id']))
						{
							$data['lista_disc_turma_disciplina'] = $lista_disc_turma_disciplina;
							$data['lista_categorias'] = $lista_categorias;
							$data['lista_professores'] = $lista_professores;

							$this->load->view("turma/_disciplinas",$data);
						}
						else
						{
							echo "<table class='table table-striped table-hover'>";
								echo "<thead>";
									echo "<tr>";
										echo "<td class='text-center'>Nome</td>";
										echo "<td class='text-center'>Categoria</td>";
										echo "<td class='text-center'>Professor</td>";
									echo "<tr>";
								echo "</thead>";
								echo "<tbody>";
								echo "</tbody>";
							echo "</table>";
						}
					?>
				</div>
			</fieldset>
			<br />
			
			<div class="row">
				<div class="col-lg-7">
					<fieldset>
						<legend class='text-white'>&nbsp; Alunos</legend>
						<div style="border-radius: 5px 5px 0px 0px; border: 1px solid white; border-bottom: 0px;">
							<div class="row background_dark padding10" style="margin: auto;">
								<div class="col-lg-6">
									<div class='form-group'>
										<?php
											echo"<select name='turma_id' id='turma_id' class='form-control padding0'>";
											echo"<option value='0' class='background_dark'>Turmas</option>";
											for($i = 0; $i < count($lista_turmas); $i++)
												echo"<option $selected class='background_dark' value='". $lista_turmas[$i]['Id'] ."'>".$lista_turmas[$i]['Nome_turma']."</option>";
											echo "</select>";
										?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group relative">
										<input id="nome_aluno" name="nome_aluno" type="text" class="input-material">
										<label for="nome_aluno" class="label-material">Nome</label>
									</div>
								</div>
							</div>
							<div class="row background_dark padding10" style="border-radius: 0px; padding-top: 0px; margin: auto;">
								<div class="col-lg-6">
									<div class="form-group relative" id="data1">
										<input id="data_registro_inicio" value='<?php echo date("d/m/Y", strtotime('-6 months')); ?>' name="data_registro_inicio" type="text" class="input-material">
										<label for="data_registro_inicio" class="label-material active">Data de registro início</label>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group relative" id="data1">
										<input id="data_registro_fim" value='<?php echo date("d/m/Y"); ?>' name="data_registro_fim" type="text" class="input-material">
										<label for="data_registro_fim" class="label-material active">Data de registro fim</label>
									</div>
								</div>
							</div>
							<div class="row background_dark padding10" style="border-radius: 0px; padding-top: 0px; margin: auto;">
								<div class="col-lg-6">
								</div>
								<div class="col-lg-6">
									<button  onclick="Main.load_data_aluno();" type="button" class='btn btn-success btn-block'>
										<span class='glyphicon glyphicon-search'></span> Pesquisar
									</button>
								</div>
							</div>
							<div class="alunos" id="alunos" style="max-height: 460px; border-radius: 0px;">
								<?php
									$data['lista_alunos'] = $lista_alunos;

									$this->load->view("turma/_alunos", $data);

								?>
							</div>
						</div>
						<div class="col-lg-12 padding0">
							<button type="button" onclick="Main.add_aluno();" class='btn btn-danger btn-block' style="border-radius: 0px 0px 5px 5px;"><span class='glyphicon glyphicon-plus'></span> Adicionar</button>
						</div>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset>
						<legend class='text-white'>&nbsp; Alunos da turma</legend>
						<div class='alunos'>
							<?php
								echo "<table class='table table-striped table-sm table-hover'>";
									echo "<thead>";
										echo "<tr>";
											echo "<td style='width: 80%;'>Nome</td>";
											echo "<td>Subturma</td>";
											echo "<td style='width: 10%;' class='text-center'>#</td>";
										echo "<tr>";
									echo "</thead>";
									echo "<tbody id='alunos_turma'>";
									//if(!empty($obj['Id']))
									//{	
										$limite_aluno_add = 0;
										for($i = 0; $i < count($lista_disc_turma_aluno); $i++)
										{
											//if($lista_disc_turma_aluno[$i]['Aluno_id'] != NULL)
											//{
												echo "<tr id='aluno_item_add$i'>";
													echo"<td title='".$lista_disc_turma_aluno[$i]['Nome_aluno']."'>";
														echo"<div style='margin-top: 5px; height: 25px;' class='checkbox checbox-switch switch-success custom-controls-stacked'>";
															echo "<label for='nome_aluno_add$i' style='display: block; height: 25px;'>";
																echo "<input type='checkbox' id='nome_aluno_add$i' name='nome_aluno_add$i' value='1' /><span></span>";
																echo mstring::corta_string($lista_disc_turma_aluno[$i]['Nome_aluno'], 20);
															echo "</label>";
														echo"</div>";
														echo "<input type='hidden' value='".$lista_disc_turma_aluno[$i]['Aluno_id']."' id='aluno_id_add$i' name='aluno_id_add$i'>";
													echo"</td>";

													echo "<td class='text-center' style='vertical-align: middle;'>";
														echo "<input type='number' class='text-center' style='width: 60%;' maxlength='1' id='sub_turma$i' name='sub_turma$i' value='".$lista_disc_turma_aluno[$i]['Sub_turma']."'>";
													echo "</td>";
													echo "<td class='text-center' style='vertical-align: middle;'>";
														echo "<span title='Detalhes' style='cursor: pointer;' class='glyphicon glyphicon-th text-danger'></span>";
													echo "</td>";
												echo "</tr>";
												$limite_aluno_add = $limite_aluno_add + 1;
											//}
										}
									//}
									echo "</tbody>";
								echo "</table>";
								echo "<input type='hidden' value='".$limite_aluno_add."' id='limite_aluno_add' name='limite_aluno_add'>";
							?>
						</div>
						<div class="col-lg-12 padding0">
							<button type="button" class='btn btn-danger btn-block' onclick="Main.remove_aluno();" style="border-radius: 0px 0px 5px 5px;"><span class='glyphicon glyphicon-trash'></span> Remover</button>
						</div>
					</fieldset>
				</div>
			</div>
			
			<br />
			<div class='form-group'>
				<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
					<?php
					$checked = "";
					if ($obj['Ativo'] == 1)
						$checked = "checked";

					echo "<label for='turma_ativa' style='color: #8a8d93;'>";
					echo "<input type='checkbox' $checked id='turma_ativa' name='turma_ativa' value='1' /><span></span> Turma ativa";
					echo "</label>";
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2">
					<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Avançar'>
				</div>	
				<div class="col-lg-2">
					<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Finalizar'>
				</div>	
			</div>
		</form>
	</div>
</div>
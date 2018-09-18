<br /><br />
<?php $this->load->helper("mstring");?>
<div class='row padding20 text-white relative' style="width: 95%; left: 3.5%">
	<?php
    	echo"<div class='col-lg-12 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Turmas</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($lista_disc_turma_header['Id'])) ? 'Editar turma' : 'Nova turma')."</li>";
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
			<input type='hidden' id='id' name='id' value='<?php if(!empty($lista_disc_turma_header['Id'])) echo $lista_disc_turma_header['Id']; ?>'/>
			<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group relative">
						<input spellcheck="false" maxlength="20" id="nome" name="nome" value='<?php echo (!empty($lista_disc_turma_header['Nome_turma']) ? $lista_disc_turma_header['Nome_turma']:''); ?>' type="text" class="input-material">
						<label for="nome" class="label-material">Nome</label>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class='form-group relative'>
						<?php if(empty($lista_disc_turma_header['Id'])): ?>
						<select onchange="Main.habilita_curso(this.value);" name='modalidade_id' id='modalidade_id' class='form-control' style='padding-left: 0px;'>
							<option value='0' style='background-color: #393836;'>Selecione a modalidade</option>
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
						<?php else: ?>
							<?php
							for ($i = 0; $i < count($lista_modalidades); $i++)
							{
								if ($lista_modalidades[$i]['Id'] == $lista_disc_turma_header['Modalidade_id'])
								{
									echo"<input readonly id='modalidade' name='modalidade' value='".$lista_modalidades[$i]['Nome_modalidade']."' type='text' class='input-material'>";
									echo"<input id='modalidade_id' name='modalidade_id' value='".$lista_disc_turma_header['Modalidade_id']."' type='hidden' class='input-material'>";
									echo"<label for='modalidade' class='label-material'>Modalidade</label>";
								}
							}
							?>
						<?php endif;?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group relative">
						<input readonly="true" id="nome_periodo_letivo" name="nome_periodo_letivo" value='<?php echo (!empty($lista_disc_turma_header['Nome_periodo']) ? $lista_disc_turma_header['Nome_periodo']:''); ?>' type="text" class="input-material">
						<label for="nome_periodo_letivo" class="label-material active">Período letivo</label>
					</div>
				</div>
				<div class="col-lg-6" id='curso'>
					<?php
						$data['lista_cursos'] = $lista_cursos;

						$data['lista_disc_turma_header'] = $lista_disc_turma_header;
						$this->load->view("turma/_cursos", $data);
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6" id='grade'>
					<?php
						$data['lista_grades'] = $lista_grades;

						$data['lista_disc_turma_header'] = $lista_disc_turma_header;
						$this->load->view("turma/_grade", $data);
					?>
				</div>
				<div class="col-lg-6" id='periodo_grade'>
					<?php
						$data['lista_periodo_grade'] = $lista_periodo_grade;
						$data['lista_disc_turma_header'] = $lista_disc_turma_header;

						$this->load->view("turma/_periodo_grade", $data);
					?>
				</div>
			</div>
			<br />
			<?php
				if(!empty($lista_disc_turma_header['Id']))
				{
					echo"<div class='row'>";
						echo"<div class='col-lg-12'>";
							echo"<span class='glyphicon glyphicon-time'></span> <a href='".$url."horario/create/".$lista_disc_turma_header['Id']."'>Alterar horário da turma</a>";
						echo "</div>";
					echo"</div>";
					echo "<br />";
				}
			?>
			<fieldset>
				<legend class='text-white'>&nbsp;Grade disciplinar</legend>
				<div class='disciplinas' id='disciplinas'>
					<?php
						if (!empty($lista_disc_turma_header['Id']))
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
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-disciplinas'></div>
			</fieldset>
			<br />
			<div class="row border_radius" style="margin: auto; border: 1px solid white;">
				<div class="col-lg-7 padding10" style="border-right: 1px solid white;">
					<div class="text-center">Filtro geral</div>
					<div class="row">
						<div class="col-lg-6 padding20">
							<div class="form-group relative" id="data1">
								<input id="data_renovacao_inicio" value='<?php echo date("d/m/Y", strtotime('-6 months')); ?>' name="data_renovacao_inicio" type="text" class="input-material">
								<input id="data_renovacao_inicio_hidden" value='<?php echo date("d/m/Y", strtotime('-6 months')); ?>' type="hidden">
								<label for="data_renovacao_inicio" class="label-material active">Data de início da renovação</label>
							</div>
						</div>
						<div class="col-lg-6 padding20">
							<div class="form-group relative" id="data1">
								<input id="data_renovacao_fim" value='<?php echo date("d/m/Y"); ?>' name="data_renovacao_fim" type="text" class="input-material">
								<input id="data_renovacao_fim_hidden" value='<?php echo date("d/m/Y"); ?>' type="hidden">
								<label for="data_renovacao_fim" class="label-material active">Data de fim da renovação</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group relative">
								<input id="nome_aluno" name="nome_aluno" type="text" class="input-material">
								<label for="nome_aluno" class="label-material">Nome</label>
							</div>
						</div>
						<div class="col-lg-6">
							<div class='form-group' style="margin-top: 15px">
								<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
									<label for='aluno_novo' style='color: #8a8d93;'>
									<input type='checkbox' id='aluno_novo' name='aluno_novo' value='1' /><span></span> Apenas alunos novos
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="row padding10">
						<div class="col-lg-6">
							<button onclick="Main.load_filtro_turma_aluno();" type="button" class='btn btn-success btn-block'>
								<span class='glyphicon glyphicon-search'></span> Pesquisar
							</button>
						</div>
						<div class="col-lg-6">
							<button  onclick="Main.limpa_filtro_aluno();" type="button" class='btn btn-success btn-block'>
								<span class='glyphicon glyphicon-erase'></span> Limpar
							</button>
						</div>
					</div>
				</div>
				<div class="col-lg-5" >
					<div class="col-lg-12 padding10 text-center" >
						Filtrar a partir de uma turma:
					</div>
					<div class="col-lg-12 padding10" >
						<br />
						<br />
						<div class='form-group'>
							<?php
								echo"<select onchange='Main.load_data_aluno_turma_antiga(this.value);' name='turma_id' id='turma_id' class='form-control padding0'>";
								echo"<option value='0' class='background_dark'>Turmas</option>";
									$data['lista_turmas'] = $lista_turmas;

									$this->load->view("turma/_filtro_turma", $data);
								echo "</select>";
							?>
						</div>
					</div>
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-lg-6">
					<fieldset>
						<legend class='text-white'>&nbsp; Alunos</legend>
						
						<div class='row padding10' style=" margin-top: 3px; border-radius: 5px 5px 0px 0px; border: 1px solid white; margin: 0px;">
							<div class="col-lg-6" style="padding-left: 0px;">
								<div class='form-group' style="margin-bottom: 0px; margin-top: 5px;">
									<div class='checkbox checbox-switch switch-success custom-controls-stacked' onclick="Main.add_aluno_marcado('limite_aluno','check_all_alunos');">
										<label for='check_all_alunos'>
											<input type='checkbox' id='check_all_alunos' name='check_all_alunos' value='1' /><span></span> Marcar todos
										</label>
									</div>
								</div>
							</div>
							<div class="col-lg-6 text-right" style="padding-right: 0px;">
								<button onclick="Main.add_aluno();" type="button" class="btn btn-outline-white padding0 btn-simple-white">Adicionar marcados <span class='glyphicon glyphicon-menu-right'></span><span style="margin-left: -5px;" class='glyphicon glyphicon-menu-right'></span></button>
							</div>
						</div>
						<?php
							echo "<div class='background_white ' style='padding-top: 3px; border-radius: 0px 0px 5px 5px;'>";
								echo "<table class='table'>";
										echo "<tr>";
											echo "<td id='qtd_alunos_encontrados_busca'>Alunos encontrados ";
												echo count($lista_alunos);
											echo"</td>";
										echo "</tr>";
								echo "</table>";
							echo "</div>";
						?>
						<div style="; border-bottom: 0px;">
							<div class="alunos border_radius" id="alunos">
								<?php
									$data['lista_alunos'] = $lista_alunos;
									

									$this->load->view("turma/_alunos", $data);

								?>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="col-lg-6">
					<fieldset>
						<legend class='text-white'>&nbsp; Alunos da turma</legend>
						<div class='row padding10' style=" margin-top: 3px; border-radius: 5px 5px 0px 0px; border: 1px solid white; margin: 0px;">
							<div class="col-lg-6" style="padding-left: 0px;">
								<button onclick="Main.remove_aluno();" type="button" class="btn btn-outline-white padding0 btn-simple-white"><span class='glyphicon glyphicon-menu-left'></span><span style="margin-left: -5px;" class='glyphicon glyphicon-menu-left'></span> Remover marcados</button>
							</div>
							<div class="col-lg-6 text-right" style="padding-right: 0px;">
								<div class='form-group' style="margin-bottom: 0px; margin-top: 5px;">
									<div class='checkbox checbox-switch switch-success custom-controls-stacked' onclick="Main.add_aluno_marcado('limite_aluno_add','check_all_alunos_add');">
										<label class="text-left" for='check_all_alunos_add'>
											<input type='checkbox' id='check_all_alunos_add' name='check_all_alunos_add' value='1' /><span></span> Marcar todos
										</label>
									</div>
								</div>
							</div>
						</div>
						<?php
							echo "<div class='background_white ' style='padding-top: 3px; border-radius: 0px 0px 5px 5px;'>";
								echo "<input type='hidden' id='quantidade_alunos_aux' value='".count($lista_disc_turma_aluno)."'>";
								echo "<input type='hidden' id='quantidade_minima_aux' value='".(($lista_disc_turma_header['Qtd_minima_aluno'] == 0) ? '-' : $lista_disc_turma_header['Qtd_minima_aluno'])."'>";
								echo "<input type='hidden' id='quantidade_maxima_aux' value='".(($lista_disc_turma_header['Qtd_maxima_aluno'] == 0) ? '-' : $lista_disc_turma_header['Qtd_maxima_aluno'])."'>";
								echo "<table class='table'>";
										echo "<tr>";
											echo "<td id='quantidade_alunos'>Alunos na turma ";
												echo count($lista_disc_turma_aluno);
											echo"</td>";
											echo "<td id='quantidade_minima' class='text-right'>";
												if(!empty($lista_disc_turma_header['Qtd_minima_aluno']))
													echo"Mínimo ".$lista_disc_turma_header['Qtd_minima_aluno'];
												else
													echo"Mínimo -";
											echo"</td>";
											echo "<td id='quantidade_maxima' class='text-right'>";
												if(!empty($lista_disc_turma_header['Qtd_maxima_aluno']))
													echo"Máximo ".$lista_disc_turma_header['Qtd_maxima_aluno'];
												else
													echo "Máximo -";
											echo"</td>";
										echo "</tr>";
								echo "</table>";
							echo "</div>";
						?>
						<div class='alunos border_radius'>
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
									//if(!empty($lista_disc_turma_header['Id']))
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
														echo "<input type='number' class='text-center' style='width: 60%;' maxlength='1' id='sub_turma_add$i' name='sub_turma_add$i' value='".$lista_disc_turma_aluno[$i]['Sub_turma']."'>";
													echo "</td>";
													echo "<td class='text-center' style='vertical-align: middle;'>";
														echo "<a title='Detalhes' target='n_guia' href='".$url."aluno/detalhes/".$lista_disc_turma_aluno[$i]['Usuario_id']."'>";
															echo "<span class='glyphicon glyphicon-arrow-right text-warning'></span> ";
														echo "</a>";
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
					</fieldset>
				</div>
			</div>
			
			<br />
			<div class="row">
				<div class="col-lg-12">
					<div class='form-group'>
						<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
							<label for='horario' style='color: #8a8d93;'>
								<input type='checkbox' id='horario' name='horario' value='1' /><span></span> Salvar e ir para o horário da turma.
							</label>
						</div>
					</div>
				</div>
				<div class="col-lg-12">
					<div class='form-group'>
						<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
							<?php
							$checked = "";
							if ($lista_disc_turma_header['Ativo'] == 1)
								$checked = "checked";

							echo "<label for='turma_ativa' style='color: #8a8d93;'>";
							echo "<input type='checkbox' $checked id='turma_ativa' name='turma_ativa' value='1' /><span></span> Turma ativa";
							echo "</label>";
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
			if (empty($lista_disc_turma_header['Id']))
				echo "<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
			else
				echo "<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
			?>
		</form>
	</div>
</div>
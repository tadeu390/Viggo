<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."turma'>Turmas</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar turma' : 'Nova turma')."</li>";
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
						<div class="form-group relative">
							<input maxlength="20" id="nome" name="nome" value='<?php echo (!empty($obj['Nome_turma']) ? $obj['Nome_turma']:''); ?>' type="text" class="input-material">
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
							<div class='input-group-addon' style="color: #8a8d93;">Curso</span></div>
							<select name='curso_id' id='curso_id' class='form-control' style='padding-left: 0px;'>
								<option value='0' style='background-color: #393836;'>Selecione</option>
								<?php
								for ($i = 0; $i < count($lista_cursos); $i++)
								{
									$selected = "";
									if ($lista_cursos[$i]['Id'] == $obj['Curso_id'])
										$selected = "selected";
									echo "<option class='background_dark' $selected value='" . $lista_cursos[$i]['Id'] . "'>" . $lista_cursos[$i]['Nome'] . "</option>";
								}
								?>
							</select>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-curso_id'></div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class='form-group'>
							<div class='input-group-addon' style="color: #8a8d93;">Modalidade</span></div>
							<select name='modalidade_id' id='modalidade_id' class='form-control' style='padding-left: 0px;'>
								<option value='0' style='background-color: #393836;'>Selecione</option>
								<?php
								for ($i = 0; $i < count($lista_modalidades); $i++)
								{
									$selected = "";
									if ($lista_modalidades[$i]['Id'] == $obj['Modalidade_id'])
										$selected = "selected";
									echo "<option class='background_dark' $selected value='" . $lista_modalidades[$i]['Id'] . "'>" . $lista_modalidades[$i]['Nome'] . "</option>";
								}
								?>
							</select>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-modalidade_id'></div>
						</div>
					</div>
				</div>

				<div>
					<fieldset>
						<legend class='text-white'>Disciplinas</legend>
						<div class='disciplinas'>
							<?php
								if (!empty($obj['Id']))
								{
									echo "<table class='table table-striped table-hover'>";
									echo "<thead>";
										echo "<tr>";
											echo "<td>Nome</td>";
											echo "<td>Categoria</td>";
											echo "<td>Professor</td>";
										echo "<tr>";
									echo "</thead>";
									echo "<tbody>";
										for($i = 0; $i < count($lista_disc_turma); $i++)
										{
											echo "<tr>";
												echo "<td>";
													echo"<div class='form-group'>";
														echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
															echo "<label for='nome_disciplina$i' style='color: #8a8d93;'>";
															$checked = "";
															if ($lista_disc_turma[$i]['Disciplina_id'] != NULL)
																$checked = 1;

															echo "<input type='checkbox' $checked id='nome_disciplina$i' name='nome_disciplina$i' value='1' /><span></span> ".$lista_disciplinas[$i]['Nome_disciplina'];
															echo "</label>";
														echo"</div>";
													echo"</div>";
												echo"</td>";
												echo"<td>";
													echo"<select name='categoria_id' id='categoria_id' class='form-control' style='padding-left: 0px;'>";
														echo"<option value='0' style='background-color: #393836;'>Selecione</option>";
														for ($j = 0; $j < count($lista_categorias); $j++)
														{
															$selected = "";
															if ($lista_categorias[$j]['Id'] == $lista_disc_turma[$i]['Categoria_id'])
																$selected = "selected";
															echo "<option class='background_dark' $selected value='" . $lista_categorias[$j]['Id'] . "'>" . $lista_categorias[$j]['Nome'] . "</option>";
														}
													echo"</select>";
												echo"</td>";
												echo"<td>";
													echo"<select name='professor_id' id='professor_id' class='form-control' style='padding-left: 0px;'>";
														echo"<option value='0' style='background-color: #393836;'>Selecione</option>";
														for ($k = 0; $k < count($lista_professores); $k++)
														{
															$selected = "";
															if ($lista_professores[$k]['Id'] == $lista_disc_turma[$i]['Professor_id'])
																$selected = "selected";
															echo "<option class='background_dark' $selected value='" . $lista_professores[$k]['Id'] . "'>" . $lista_professores[$k]['Nome'] . "</option>";
														}
													echo"</select>";
												echo"</td>";
											echo "</tr>";
										}
									echo "</tbody>";
								echo "</table>";
								}
							?>
						</div>
					</fieldset>
				</div>

				<div>
					<fieldset>
						<legend class='text-white'>Alunos</legend>
						<div class="row">
							<div class="col-lg-5">
								
							</div>
							<div class="col-lg-2">
								<span class='btn btn-danger btn-block'> Adicionar</span>
								</br>
								<span class='btn btn-danger btn-block'> Remover</span>
							</div>
							<div class="col-lg-5">
								<div class='alunos'>
									<?php
										if (!empty($obj['Id']))
										{
											echo "<table class='table table-striped table-hover'>";
											echo "<thead>";
												echo "<tr>";
													echo "<td>Nome</td>";
													echo "<td>Subturma</td>";
												echo "<tr>";
											echo "</thead>";
											echo "<tbody>";
												for($i = 0; $i < count($lista_disc_turma); $i++)
												{
													echo "<tr>";
														echo"<td>";
															echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
																echo "<label for='nome_aluno$i' style='color: #8a8d93;'>";
																echo "<input type='checkbox' checked id='nome_aluno$i' name='nome_aluno$i' value='1' /><span></span> ".$lista_disc_turma[$i]['Nome_aluno'];
																echo "</label>";
														echo"</div>";
														echo"</td>";
													echo "</tr>";
												}
											echo "</tbody>";
										echo "</table>";
										}
									?>
								</div>
							</div>
						</div>

					</fieldset>
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
<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Grades</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar grade' : 'Nova grade')."</li>";
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
									echo "";
								}
								?>
							</select>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-curso_id'></div>
						</div>
					</div>
					<div class="col-lg-6">
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
					</div>
				</div>

				<div class="row">
					<div class="col-lg-6">
						<div class="form-group relative">
							<div class='input-group-addon' style="color: #8a8d93;">Nome da grade</div>
							<input disabled maxlength="100" id="nome" name="nome" value='<?php echo (!empty($obj['Nome_grade']) ? $obj['Nome_grade']: 'Gerado automaticamente'); ?>' type="text" class="input-material">
							<label for="nome" class="label-material"></label>
						</div>
					</div>
				</div>

				<div class="row border_radius col-lg-6" style="margin-top: 3px; border-radius: 5px 5px 5px 5px; border: 1px solid white; margin: 0px; ">
					<div class="col-lg-12 padding">
						<div class="text-center">Filtrar disciplina</div>
						<div class="row">
							<div class="col-lg-9">
								<div class="form-group relative">
									<input id="nome_disciplina" name="nome_disciplina" type="text" class="input-material">
									<label for="nome_disciplina" class="label-material">Nome da disciplina</label>
								</div>
							</div>
							<div class="col-lg-3">
								<button  onclick="Main.load_filtro_grade_disciplina();" type="button" class='btn btn-success btn-block'>
									<span class='glyphicon glyphicon-search'></span> Pesquisar
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-6 padding10">
						<fieldset>
							<legend class='text-white'>&nbsp; Disciplinas</legend>

							<div class='row padding10' style=" margin-top: 3px; border-radius: 5px 5px 5px 5px; border: 1px solid white; margin: 0px;">
								<div class="col-lg-6" style="padding-left: 0px; margin-top: 3px;">
									<label for="nome_periodo_base" class="label-material">Adicionar no período/módulo </label>
									<input type='number' class='text-center border_radius' style='width: 15%; color: black;' maxlength='2' id='periodo_base' name='periodo_base' value='1'>
								</div>
								<div class="col-lg-6 text-right" style="padding-right: 0px;">
									<button onclick="Main.add_disciplina();" type="button" class="btn btn-outline-white padding0 btn-simple-white">Adicionar marcadas <span class='glyphicon glyphicon-menu-right'></span><span style="margin-left: -5px;" class='glyphicon glyphicon-menu-right'></span></button>
								</div>
							</div>

							<br />

							<div style="; border-bottom: 0px;">
								<div class="disciplinas border_radius" id="disciplinas">
									<?php
										$data['lista_disciplinas'] = $lista_disciplinas;
										

										$this->load->view("grade/_disciplinas", $data);

									?>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-lg-6 padding10">
						<fieldset>
							<legend class='text-white'>&nbsp; Disciplinas da grade</legend>

							<div class='row padding10' style=" margin-top: 3px; border-radius: 5px 5px 5px 5px; border: 1px solid white; margin: 0px;">
								<div class="col-lg-6" style="padding-left: 0px;">
									<button onclick="Main.remove_disciplina();" type="button" class="btn btn-outline-white padding0 btn-simple-white"><span class='glyphicon glyphicon-menu-left'></span><span style="margin-left: -5px;" class='glyphicon glyphicon-menu-left'></span> Remover marcadas</button>
								</div>
							</div>

							<br />

							<div class='disciplinas border_radius'>
							<?php
								echo "<table class='table table-striped table-sm table-hover'>";
									echo "<thead>";
										echo "<tr>";
											echo "<td style='width: 80%;'>Nome da disciplina</td>";
											echo "<td>Período/Módulo</td>";
										echo "<tr>";
									echo "</thead>";
									echo "<tbody id='disciplinas_grade'>";
										$limite_disciplina_add = 0;
										for($i = 0; $i < count($lista_disc_grade); $i++)
										{
											echo "<tr id='disciplina_item_add$i'>";
												echo"<td title='".$lista_disc_grade[$i]['Nome_disciplina']."'>";
													echo"<div style='margin-top: 5px; height: 25px;' class='checkbox checbox-switch switch-success custom-controls-stacked'>";
														echo "<label for='nome_disciplina_add$i' style='display: block; height: 25px;'>";
															echo "<input type='checkbox' id='nome_disciplina_add$i' name='nome_disciplina_add$i' value='1' /><span></span>";
															echo mstring::corta_string($lista_disc_grade[$i]['Nome_disciplina'], 20);
														echo "</label>";
													echo"</div>";
													echo "<input type='hidden' value='".$lista_disc_grade[$i]['Disciplina_id']."' id='disciplina_id_add$i' name='disciplina_id_add$i'>";
												echo"</td>";

												echo "<td class='text-center' style='vertical-align: middle;'>";
													echo "<input type='number' class='text-center' style='width: 60%;' maxlength='2' id='periodo_add$i' name='periodo_add$i' value='".$lista_disc_grade[$i]['Periodo']."'>";
												echo "</td>";
											echo "</tr>";
											$limite_disciplina_add = $limite_disciplina_add + 1;
										}
									echo "</tbody>";
								echo "</table>";
								echo "<input type='hidden' value='".$limite_disciplina_add."' id='limite_disciplina_add' name='limite_disciplina_add'>";
							?>
							</div>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-limite_disciplina_add'></div>
						</fieldset>
					</div>
				</div>
				
				<br />
				<div class='form-group'>
					<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
						<?php
							$checked = "";
							if($obj['Ativo'] == 1)
								$checked = "checked";
							
							echo"<label for='grade_ativo' class='text-white'>";
								echo "<input type='checkbox' $checked id='grade_ativo' name='grade_ativo' value='1' /><span></span> Grade ativo";
							echo"</label>";
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
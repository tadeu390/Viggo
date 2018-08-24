<style type="text/css">
	.table-bordered th, .table-bordered td {
	border: 1px solid #393836 !important;
}
</style>
<?php $this->load->helper("mtime");?>
<br /><br />
<div class='row padding20 text-white relative' style="width: 98%; left: 2%">
	<?php
    	echo"<div class='col-lg-12 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Horários</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($lista_disc_turma_header['Id'])) ? 'Alterar horário' : 'Alterar horário')."</li>";
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
							<input spellcheck="false" readonly="true" maxlength="20" id="nome" name="nome" value='<?php echo (!empty($lista_disc_turma_header['Nome_turma']) ? $lista_disc_turma_header['Nome_turma']:''); ?>' type="text" class="input-material">
							<label for="nome" class="label-material">Turma</label>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class='form-group relative'>
							<?php
							for ($i = 0; $i < count($lista_modalidades); $i++)
							{
								if ($lista_modalidades[$i]['Id'] == $lista_disc_turma_header['Modalidade_id'])
								{
									echo"<input readonly id='modalidade' name='modalidade' value='".$lista_modalidades[$i]['Nome_modalidade']."' type='text' class='input-material'>";
									echo"<input id='modalidade_id' name='modalidade_id' value='".$lista_disc_turma_header['Modalidade_id']."' type='hidden' class='input-material'>";
									echo"<label for='modalidade' class='label-material active'>Modalidade</label>";
								}
							}
							?>
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
					<div class="col-lg-6">
						<div class='form-group relative'>
							<?php
							for ($i = 0; $i < count($lista_cursos); $i++)
							{
								if ($lista_cursos[$i]['Id'] == $lista_disc_turma_header['Curso_id'])
								{
									echo"<input readonly id='curso' name='curso' value='".$lista_cursos[$i]['Nome_curso']."' type='text' class='input-material'>";
									echo"<input id='curso_id' name='curso_id' value='".$lista_disc_turma_header['Curso_id']."' type='hidden' class='input-material'>";
									echo"<label for='curso' class='label-material active'>Curso</label>";
								}
							}
							?>
						</div>
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
				<div class="row">
					<div class="col-lg-12">
						<?php
							echo"<span class='glyphicon glyphicon-info-sign'></span> <a href='".$url."turma/edit/".$lista_disc_turma_header['Id']."'>Editar informações da turma</a>";
						?>
					</div>
				</div>
				<br />
				<fieldset class="border_radius">
					<legend>&nbsp;Quadro de horários</legend>
					<div style="overflow: auto;" id="horarios">
						<?php
							$qtd_intervalo = array();
							$qtd_intervalo[0][0] = $qtd_intervalo_por_dia['Segunda'];
							$qtd_intervalo[0][1] = "Segunda";
							$qtd_intervalo[1][0] = $qtd_intervalo_por_dia['Terca'];
							$qtd_intervalo[1][1] = "Terça";
							$qtd_intervalo[2][0] = $qtd_intervalo_por_dia['Quarta'];
							$qtd_intervalo[2][1] = "Quarta";
							$qtd_intervalo[3][0] = $qtd_intervalo_por_dia['Quinta'];
							$qtd_intervalo[3][1] = "Quinta";
							$qtd_intervalo[4][0] = $qtd_intervalo_por_dia['Sexta'];
							$qtd_intervalo[4][1] = "Sexta";
							$qtd_intervalo[5][0] = $qtd_intervalo_por_dia['Sabado'];
							$qtd_intervalo[5][1] = "Sábado";
							$qtd_intervalo[6][0] = $qtd_intervalo_por_dia['Domingo'];
							$qtd_intervalo[6][1] = "Domingo";
						$maior_qtd = 0;
						for($k = 0; $k < 7; $k++)
						{
							echo"<input type='hidden' id='dia".($k + 1)."' name='dia".($k + 1)."'>";
							echo"<table id='dia".($k+1)."' class='table table-bordered text-dark background_white border_radius'>";
								echo"<tr>";
									echo"<td rowspan='3' class='text-center' style='width: 8%; vertical-align: middle;'>".$qtd_intervalo[$k][1]."</td>";//DIA DA SEMANA
										$Hora_inicio_aula = $regras['Hora_inicio_aula'];
										$aula = 1;
										for($i = 0; $i < ($regras['Quantidade_aula'] + $qtd_intervalo[$k][0]); $i++)
										{
											echo "<td class='text-center' style='vertical-align: middle;'>";
												$Duracao_intervalo = "0";

												$flag = 0;

												for($j = 0; $j < COUNT($intervalos); $j++)
												{
													if($intervalos[$j]['Dia'] == ($k + 1) && $intervalos[$j]['Hora_inicio'] == $Hora_inicio_aula)
													{
														$flag = 1;
														$Duracao_intervalo = mtime::diff_time($intervalos[$j]['Hora_fim'], $intervalos[$j]['Hora_inicio']);
													}
												}
												$Hora_inicio_aula =  mtime::add_time($Hora_inicio_aula, (($Duracao_intervalo == "0") ? "00:".$regras['Duracao_aula'].":00" : $Duracao_intervalo));
												
												if($flag == 1)
													echo "Intervalo";
												else
												{
													echo "Aula ".$aula;
													$aula = $aula + 1;
												}
											echo "</td>";
										}
								echo"</tr>";
								echo"<tr>";
									$Hora_inicio_aula = $regras['Hora_inicio_aula'];
									$aula = 1;
									for($i = 0; $i < ($regras['Quantidade_aula'] + $qtd_intervalo[$k][0]); $i++)
									{
										echo "<td class='text-center' style='vertical-align: middle;'>";
											$Duracao_intervalo = "0";

											$flag = 0;

											for($j = 0; $j < COUNT($intervalos); $j++)
											{
												if($intervalos[$j]['Dia'] == ($k + 1) && $intervalos[$j]['Hora_inicio'] == $Hora_inicio_aula)
												{
													$flag = 1;
													$Duracao_intervalo = mtime::diff_time($intervalos[$j]['Hora_fim'], $intervalos[$j]['Hora_inicio']);
												}
											}

											echo $Hora_inicio_aula." | ";
											if($flag != 1)
												echo"<input type='hidden' value='".$Hora_inicio_aula."' id='hour_init_dia".($k + 1)."_aula".$aula."' name='hour_init_dia".($k + 1)."_aula".$aula."'>";
											$Hora_inicio_aula =  mtime::add_time($Hora_inicio_aula, (($Duracao_intervalo == "0") ? "00:".$regras['Duracao_aula'].":00" : $Duracao_intervalo));
											echo $Hora_inicio_aula;
											if($flag != 1)	
												echo"<input type='hidden' value='".$Hora_inicio_aula."' id='hour_fim_dia_".($k + 1)."aula_".$aula."' name='hour_fim_dia".($k + 1)."_aula".$aula."'>";
											if($flag != 1)
												$aula = $aula + 1;
										echo "</td>";
									}
								echo"</tr>";
								echo "<tr>";
									$aula = 1;
									$Hora_inicio_aula = $regras['Hora_inicio_aula'];
									for($i = 0; $i < ($regras['Quantidade_aula'] + $qtd_intervalo[$k][0]); $i++)
									{
										echo "<td class='text-center' style='vertical-align: middle;'>";
											$Duracao_intervalo = "0";

											$flag = 0;

											for($j = 0; $j < COUNT($intervalos); $j++)
											{
												if($intervalos[$j]['Dia'] == ($k + 1) && $intervalos[$j]['Hora_inicio'] == $Hora_inicio_aula)
												{
													$flag = 1;
													$Duracao_intervalo = mtime::diff_time($intervalos[$j]['Hora_fim'], $intervalos[$j]['Hora_inicio']);
												}
											}
											//inicio
											$Hora_inicio_aula_temp = $Hora_inicio_aula;
											//fim
											$Hora_inicio_aula =  mtime::add_time($Hora_inicio_aula, (($Duracao_intervalo == "0") ? "00:".$regras['Duracao_aula'].":00" : $Duracao_intervalo));
											
											if($flag == 1)
												echo "-";
											else
											{
												for($x = 0; $x < $lista_disc_turma_header['Qtd_sub_turma']; $x++)
												{
													$title = "";
													if($lista_disc_turma_header['Qtd_sub_turma'] > 1)
														$title = "title='Subturma".($x + 1)."'"; 
													echo"<div class='form-group relative'>";
														echo"<select $title name='dia".($k + 1)."_aula".$aula."_disc_turma".($x + 1)."' id='dia".($k + 1)."_aula".$aula."_disc_turma".($x + 1)."' class='form-control' style='padding-left: 0px;'>";
														echo "<option value='0'></option>";

														for($j = 0; $j < COUNT($lista_disc_turma_disciplina); $j++)
														{
															$selected = "";
															for($y = 0; $y < COUNT($lista_disc_turma_horario); $y++)
															{
																$sub_turma = (($lista_disc_turma_header['Qtd_sub_turma'] == 1) ? 0 : ($x + 1));

																if($lista_disc_turma_horario[$y]['Dia'] == ($k + 1) && 
																   $lista_disc_turma_horario[$y]['Aula'] == $aula  && 
																   $lista_disc_turma_horario[$y]['Inicio'] == $Hora_inicio_aula_temp && 
																   $lista_disc_turma_horario[$y]['Fim'] == $Hora_inicio_aula && 
																   ($lista_disc_turma_horario[$y]['Sub_turma'] == $sub_turma || $lista_disc_turma_horario[$y]['Sub_turma'] == 0) && 
																   $lista_disc_turma_horario[$y]['Disc_turma_id'] == $lista_disc_turma_disciplina[$j]['Disc_turma_id'])
																	$selected = "selected";
															}
															echo "<option class='' $selected value='" . $lista_disc_turma_disciplina[$j]['Disc_turma_id'] . "'>" . $lista_disc_turma_disciplina[$j]['Disc_prof'] . "</option>";
														}
														echo"</select>";
													echo "</div>";
												}
												$aula = $aula + 1;
											}
										echo "</td>";
									}
								echo "</tr>";
							echo"</table>";
							echo "<br />";
							if($regras['Quantidade_aula'] + $qtd_intervalo[$k][0] > $maior_qtd)
								$maior_qtd = $regras['Quantidade_aula'] + $qtd_intervalo[$k][0];
						}
						//maior quantidade de colunas
						echo "<input type='hidden' name='maior_qtd' value='".$maior_qtd."'>";
						echo "<input type='hidden' name='qtd_sub_turma' value='".$lista_disc_turma_header['Qtd_sub_turma']."'>";
						?>
					</div>
				</fieldset>
				<br />
				<?php
					echo"<button type='submit' class='btn btn-danger btn-block' style='width: 200px;'><span class='glyphicon glyphicon-floppy-disk text-white'></span>&nbsp;Atualizar</button>";
				?>
			</form>
	</div>
</div>
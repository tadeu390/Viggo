<?php $this->load->helper("mtime");?>
<div id="horarios">
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
									$title = "title='Subturma ".($x + 1)."'"; 
								echo"<div class='form-group relative'>";
									echo"<select disabled $title name='dia".($k + 1)."_aula".$aula."_disc_turma".($x + 1)."' id='dia".($k + 1)."_aula".$aula."_disc_turma".($x + 1)."' class='form-control' style='padding-left: 0px;'>";
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
											   $lista_disc_turma_horario[$y]['Disc_turma_id'] == $lista_disc_turma_disciplina[$j]['Disc_turma_id'] && 
											   $lista_disc_turma_horario[$y]['Ativo'] == 1)
												$selected = "selected";
										}
										echo "<option $selected value='" . $lista_disc_turma_disciplina[$j]['Disc_turma_id'] . "'>" . $lista_disc_turma_disciplina[$j]['Disc_prof'] . "</option>";
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
<br />
<br />
<br />
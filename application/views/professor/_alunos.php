Status da chamada: 
<?php 
	if(!empty($lista_alunos))
		$lista_presenca = faltas::get_presenca_aluno($lista_alunos[0]['Matricula_id'], $url_part['disciplina_id'], $url_part['turma_id'], $lista_alunos[0]['Sub_turma'], $data);
	else 
		$lista_presenca = "";

	if(empty($lista_presenca)) 
		echo "Não efetuada.";
	else 
		echo "Efetuada."; 
?>
<br />
<br />
<table class="table table-bordered table-sm text-white" >
<tr>	
	<td style="width: 50%; padding: 10px; vertical-align: middle;">Aluno</td>
	<?php
		for($j = 0; $j < COUNT($lista_horarios); $j++)
		{
			echo "<td title='".$lista_horarios[$j]['Horario']."' class='text-center' style='width: 10%; vertical-align: middle;'> Aula "; 
				echo $lista_horarios[$j]['Aula'];
				echo "<input type='hidden' id='hora_inicio$j' name='hora_inicio$j' value=".$lista_horarios[$j]['Hora_inicio']." />";
			echo "</td>";
		}
	?>
	<td style="width: 30%; vertical-align: middle;">Justificativa</td>
</tr>
	<?php
		for($i=0; $i < COUNT($lista_alunos); $i++)
		{
			$lista_presenca = faltas::get_presenca_aluno($lista_alunos[$i]['Matricula_id'], $url_part['disciplina_id'], $url_part['turma_id'], $lista_alunos[$i]['Sub_turma'], $data);
			echo "<tr>";
				echo"<td style='vertical-align: middle;' title='".$lista_alunos[$i]['Nome_aluno']."'>";
					echo mstring::corta_string($lista_alunos[$i]['Nome_aluno'], 30);
					echo "<input type='hidden' id='matricula_id$i' name='matricula_id$i' value=".$lista_alunos[$i]['Matricula_id']." />";
					
				echo"</td>";
				for($j = 0; $j < COUNT($lista_horarios); $j++)
				{
					$checked = "";
					if(empty($lista_presenca))
						$checked = "checked";
					else if($lista_horarios[$j]['Dia_semana'] == $lista_presenca[$j]['Dia_presenca'] && $lista_presenca[$j]['Presenca'] == 1)
						$checked = "checked";

					echo "<td>";
						echo "<input type='hidden' id='calendario_presenca_id$i$j' name='calendario_presenca_id$i$j' value='".(empty($lista_presenca[$j]['Calendario_presenca_id']) ? '' : $lista_presenca[$j]['Calendario_presenca_id'])."' />";
						echo"<div style='margin-bottom: 0px; margin-top: 0px; height: 45px;' class='checkbox checbox-switch switch-success custom-controls-stacked'>";

							echo "<label for='presenca$i$j' style='display: block; height: 45px;'>";
							echo "<br>";
							echo "<input type='checkbox' $checked id='presenca$i$j' name='presenca$i$j' value='1' /><span></span>";
							echo "</label>";
						echo "</div>";
					echo "</td>";
				}
				echo "<td style='vertical-align: middle;'>";
					echo "<input type='text' id='justificativa$i' name='justificativa$i' class='form-control background_white border_radius' placeholder='Justificativa'>";
				echo "</td>";
			echo "</tr>";
		}
		echo "<input type='hidden' id='qtd_aluno' name='qtd_aluno' value='".COUNT($lista_alunos)."'>";
		echo "<input type='hidden' id='qtd_coluna' name='qtd_coluna' value='".COUNT($lista_horarios)."'>";
	?>
</table>
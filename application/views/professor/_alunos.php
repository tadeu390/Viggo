<table class="table table-bordered table-sm text-white" >
<tr>	
	<td style="width: 50%; padding: 10px; vertical-align: middle;">Aluno</td>
	<?php
		for($j = 0; $j < COUNT($lista_horarios); $j++)
		{
			echo "<td title='".$lista_horarios[$j]['Horario']."' class='text-center' style='width: 10%; vertical-align: middle;'> Aula ".$lista_horarios[$j]['Aula']."</td>";
		}
	?>
	<td style="width: 30%; vertical-align: middle;">Justificativa</td>
</tr>
	<?php
		for($i=0; $i < COUNT($lista_alunos); $i++)
		{
			echo "<tr>";
				echo"<td style='vertical-align: middle;' title='".$lista_alunos[$i]['Nome_aluno']."'>";
					echo mstring::corta_string($lista_alunos[$i]['Nome_aluno'], 30);
					echo "<input type='hidden' id='matricula_id$i' name='matricula_id$i' value=".$lista_alunos[$i]['Matricula_id']." />";
					echo "<input type='hidden' id='calendario_presenca_id$i' name='calendario_presenca_id$i' value=".$lista_alunos[$i]['Calendario_presenca_id']." />";
				echo"</td>";
				for($j = 0; $j < COUNT($lista_horarios); $j++)
				{
					echo "<td>";
						echo"<div style='margin-bottom: 0px; margin-top: 0px; height: 45px;' class='checkbox checbox-switch switch-success custom-controls-stacked'>";
							$checked = "checked";
							//if (!empty($obj['Renovacao_matricula_id']))
								//$checked = "checked";

							echo "<label for='presenca$i$j'style='display: block; height: 45px;'>";
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
	?>
</table>
<?php if(!empty($lista_horarios)): ?>		
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
					echo "<input type='hidden' id='horario_id$j' name='horario_id$j' value=".$lista_horarios[$j]['Horario_id']." />";
					echo "<input type='hidden' id='disc_hor_id$j' name='disc_hor_id$j' value=".$lista_horarios[$j]['Disc_hor_id']." />";
				echo "</td>";
			}
		?>
		<td style="width: 30%; vertical-align: middle;">Justificativa</td>
	</tr>
		<?php
			for($i=0; $i < COUNT($lista_alunos); $i++)
			{//echo $lista_alunos[$i]['Sub_turma'];
				$lista_presenca = faltas::get_presenca_aluno($lista_alunos[$i]['Matricula_id'], $url_part['disciplina_id'], $url_part['turma_id'], $lista_alunos[$i]['Sub_turma'], $data);
				echo "<tr>";
					echo"<td style='vertical-align: middle;' title='".$lista_alunos[$i]['Nome_aluno']."'>";
						echo mstring::corta_string($lista_alunos[$i]['Nome_aluno'], 30);
						echo "<input type='hidden' id='matricula_id$i' name='matricula_id$i' value=".$lista_alunos[$i]['Matricula_id']." />";
						
					echo"</td>";
					$justificativa = "";
					
					for($j = 0; $j < COUNT($lista_horarios); $j++)
					{
						//echo ($lista_horarios[$j]['Horario_id'] ."-".$lista_presenca[$j]['Horario_id'])."<br />";
						$checked = "";
						//echo $lista_horarios[$j]['Horario_id'];
						if(empty($lista_presenca))
							$checked = "checked";
						else if($lista_horarios[$j]['Horario_id'] == $lista_presenca[$j]['Horario_id'] && $lista_presenca[$j]['Presenca'] == 1)
						{
							$checked = "checked";
							$justificativa = $lista_presenca[$j]['Justificativa'];
						}
						else
							$justificativa = $lista_presenca[$j]['Justificativa'];

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
						echo "<input value='".$justificativa."' type='text' id='justificativa$i' name='justificativa$i' class='form-control background_white border_radius' placeholder='Justificativa'>";
					echo "</td>";
				echo "</tr>";
			}
			echo "<input type='hidden' id='qtd_aluno' name='qtd_aluno' value='".COUNT($lista_alunos)."'>";
			echo "<input type='hidden' id='qtd_coluna' name='qtd_coluna' value='".COUNT($lista_horarios)."'>";
		?>
	</table>
	<div class="form-group">
		<input type='hidden' id='conteudo_id' name='conteudo_id' value="<?php echo $conteudo['Conteudo_id']; ?>">
		<textarea id='conteudo_lecionado' name="conteudo_lecionado" style="height: 100px;" class="form-control background_white border_radius" placeholder="Conteúdo lecionado"><?php echo $conteudo['Descricao']; ?></textarea>
	</div>
<?php else : ?>
	Não existe nenhum horário programado para esta data.
<?php endif; ?>
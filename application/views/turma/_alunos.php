<?php $this->load->helper("mstring");?>
<?php
	echo "<table class='table table-striped table-sm table-hover'>";
		echo "<thead>";
			echo "<tr>";
				echo "<td class='text-center'>#</td>";
				echo "<td style='width: 80%;'>Nome</td>";
				echo "<td class='text-center'>#</td>";
			echo "<tr>";
		echo "</thead>";
		echo "<tbody>";
		$limite_aluno = 0;
		for($i = 0; $i < count($lista_alunos); $i++)
		{
			echo "<tr>";
				echo "<td style='vertical-align: middle;' class='text-center'>".($i + 1)."</td>";
				echo"<td title='".$lista_alunos[$i]['Nome_aluno']."'>";
					echo"<div style='margin-top: 5px; height: 25px;' class='checkbox checbox-switch switch-success custom-controls-stacked'>";
						echo "<label for='nome_aluno$i' style='display: block; height: 25px;'>";
							echo "<input type='checkbox' id='nome_aluno$i' name='nome_aluno$i' value='1' /><span></span>";
							echo mstring::corta_string($lista_alunos[$i]['Nome_aluno'], 20);
						echo "</label>";
					echo"</div>";
					echo "<input type='hidden' value='".$lista_alunos[$i]['Aluno_id']."' id='aluno_id$i' name='aluno_id$i'>";
					echo "<input type='hidden' value='".$lista_alunos[$i]['Nome_aluno']."' id='nome_aluno_aux$i' name='nome_aluno_aux$i'>";
				echo"</td>";

				echo "<td style='vertical-align: middle;' class='text-center'>";
					echo "<span title='Detalhes' style='cursor: pointer;' class='glyphicon glyphicon-th text-danger'></span>";
				echo "</td>";

			echo "</tr>";
			$limite_aluno = $limite_aluno + 1;
		}
		echo "</tbody>";
	echo "</table>";
	echo "<input type='hidden' value='".$limite_aluno."' id='limite_aluno' name='limite_aluno'>";
?>
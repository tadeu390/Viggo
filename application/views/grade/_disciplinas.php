<?php $this->load->helper("mstring");?>
<?php
	echo "<table class='table table-striped table-sm table-hover'>";
		echo "<thead>";
			echo "<tr>";
				echo "<td class='text-center'>#</td>";
				echo "<td style='width: 80%;'>Nome da disciplina</td>";
			echo "<tr>";
		echo "</thead>";
		echo "<tbody>";
		$limite_disciplina = 0;
 		for($i = 0; $i < count($lista_disciplinas); $i++)
		{
			echo "<tr>";
				echo "<td style='vertical-align: middle;' class='text-center'>".($i + 1)."</td>";
				echo"<td title='".$lista_disciplinas[$i]['Nome_disciplina']."'>";
					echo"<div style='margin-top: 5px; height: 25px;' class='checkbox checbox-switch switch-success custom-controls-stacked'>";
						echo "<label for='nome_disciplina$i' style='display: block; height: 25px;'>";
							echo "<input type='checkbox' id='nome_disciplina$i' name='nome_disciplina$i' value='1' /><span></span>";
							echo mstring::corta_string($lista_disciplinas[$i]['Nome_disciplina'], 40);
						echo "</label>";
					echo"</div>";
					echo "<input type='hidden' value='".$lista_disciplinas[$i]['Id']."' id='disciplina_id$i' name='disciplina_id$i'>";
					echo "<input type='hidden' value='".$lista_disciplinas[$i]['Nome_disciplina']."' id='nome_disciplina_aux$i' name='nome_disciplina_aux$i'>";
				echo"</td>";
			echo "</tr>";
			$limite_disciplina = $limite_disciplina + 1;
		}
		echo "</tbody>";
	echo "</table>";
	echo "<input type='hidden' value='".$limite_disciplina."' id='limite_disciplina' name='limite_disciplina'>";
?>
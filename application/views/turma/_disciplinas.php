<?php
	echo "<table class='table table-striped table-sm table-hover'>";
		echo "<thead>";
			echo "<tr>";
				echo "<td class='text-center'>#</td>";
				echo "<td>Nome</td>";
				echo "<td style='width: 25%;'>Categoria</td>";
				echo "<td style='width: 25%;'>Professor</td>";
			echo "<tr>";
		echo "</thead>";
		echo "<tbody>";
		$limite_disciplina = 0;
			for($i = 0; $i < count($lista_disc_turma_disciplina); $i++)
			{
				echo "<tr>";
					echo "<td style='vertical-align: middle;' class='text-center'>".($i + 1)."</td>";
					echo "<td>";
						echo"<div class='form-group'>";
							echo"<div style='margin-top: 10px; height: 25px;' class='checkbox checbox-switch switch-success custom-controls-stacked'>";
								echo "<label for='nome_disciplina$i' style='display: block; height: 25px;'>";
								$checked = "";
								if ($lista_disc_turma_disciplina[$i]['Turma_id'] != NULL)
									$checked = "checked";

								echo "<input type='checkbox' $checked id='nome_disciplina$i' name='nome_disciplina$i' value='1' /><span></span> ".$lista_disc_turma_disciplina[$i]['Nome_disciplina'];
								echo "</label>";
							echo"</div>";
							echo "<input type='hidden' id='Disc_curso_id$i' name='Disc_curso_id$i' value='".$lista_disc_turma_disciplina[$i]['Disc_curso_id']."' />";
						echo"</div>";
					echo"</td>";
					echo"<td>";
						echo"<select name='categoria_id$i' id='categoria_id$i' class='form-control' style='padding-left: 0px;'>";
							echo"<option value='0'>Selecione</option>";
							for ($j = 0; $j < count($lista_categorias); $j++)
							{
								$selected = "";
								if ($lista_categorias[$j]['Id'] == $lista_disc_turma_disciplina[$i]['Categoria_id'])
									$selected = "selected";
								echo "<option  $selected value='" . $lista_categorias[$j]['Id'] . "'>" . $lista_categorias[$j]['Nome_categoria'] . "</option>";
							}
						echo"</select>";
						echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-categoria_id$i'></div>";
					echo"</td>";
					echo"<td>";
						echo"<select name='professor_id$i' id='professor_id$i' class='form-control' style='padding-left: 0px;'>";
							echo"<option value='0'>Selecione</option>";
							for ($k = 0; $k < count($lista_professores); $k++)
							{
								$selected = "";
								if ($lista_professores[$k]['Id'] == $lista_disc_turma_disciplina[$i]['Professor_id'])
									$selected = "selected";
								echo "<option $selected value='" . $lista_professores[$k]['Id'] . "'>" . $lista_professores[$k]['Nome_usuario'] . "</option>";
							}
						echo"</select>";
						echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-professor_id$i'></div>";
					echo"</td>";
				echo "</tr>";
				$limite_disciplina++;
			}
		echo "</tbody>";
	echo "</table>";
	echo "<input type='hidden' value='".$limite_disciplina."' id='limite_disciplina' name='limite_disciplina'>";
?>
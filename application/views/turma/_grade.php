<div class='form-group relative'>
	<?php if(empty($obj['Id'])): ?> 
	<select onchange="Main.load_periodo_grade();" name='grade_id' id='grade_id' class='form-control' style='padding-left: 0px;'>
		<option value='0' style='background-color: #393836;'>Selecione a grade</option>
		<?php
		for ($i = 0; $i < count($lista_grades); $i++)
		{
			$selected = "";
			if ($lista_grades[$i]['Id'] == $lista_disc_turma_header['Grade_id'])
				$selected = "selected";
			echo "<option class='background_dark' $selected value='" . $lista_grades[$i]['Id'] . "'>" . $lista_grades[$i]['Nome_grade'] . "</option>";
		}
	?>
	</select>

	<?php else: ?>

	<?php
		for ($i = 0; $i < count($lista_grades); $i++)
		{
			if ($lista_grades[$i]['Id'] == $lista_disc_turma_header['Grade_id'])
			{
				echo"<input readonly id='grade' name='grade' value='".$lista_grades[$i]['Nome_grade']."' type='text' class='input-material'>";
				echo"<input id='grade_id' name='curso_id' value='".$lista_disc_turma_header['Grade_id']."' type='hidden' class='input-material'>";
				echo"<label for='grade' class='label-material'>Grade</label>";
			}
		}
	?>
	<?php endif;?>
</div>
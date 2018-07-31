<div class='form-group relative'>
	<?php if(empty($lista_disc_turma_header['Id'])): ?> 
	<select onchange="Main.load_grade();" name='curso_id' id='curso_id' class='form-control' style='padding-left: 0px;'>
		<option value='0' style='background-color: #393836;'>Selecione um curso</option>
		<?php
		for ($i = 0; $i < count($lista_cursos); $i++)
		{
			$selected = "";
			if ($lista_cursos[$i]['Id'] == $lista_disc_turma_header['Curso_id'])
				$selected = "selected";
			echo "<option class='background_dark' $selected value='" . $lista_cursos[$i]['Id'] . "'>" . $lista_cursos[$i]['Nome_curso'] . "</option>";
		}
	?>
	</select>
	<div class='input-group mb-2 mb-sm-0 text-danger' id='error-curso_id'></div>

	<?php else: ?>

	<?php
		for ($i = 0; $i < count($lista_cursos); $i++)
		{
			if ($lista_cursos[$i]['Id'] == $lista_disc_turma_header['Curso_id'])
			{
				echo"<input readonly id='curso' name='curso' value='".$lista_cursos[$i]['Nome_curso']."' type='text' class='input-material'>";
				echo"<input id='curso_id' name='curso_id' value='".$lista_disc_turma_header['Curso_id']."' type='hidden' class='input-material'>";
				echo"<label for='curso' class='label-material'>Curso</label>";
			}
		}
	?>
	<?php endif;?>
</div>
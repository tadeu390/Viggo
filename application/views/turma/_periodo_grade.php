
<div class='form-group relative'>
	<?php if(empty($lista_disc_turma_header['Id'])): ?> 
		<select onchange="Main.load_grade_disciplina();" name='periodo_grade_id' id='periodo_grade_id' class='form-control' style='padding-left: 0px;'>
			<option value='0' style='background-color: #393836;'>Selecione o período</option>
			<?php
			for ($i = 0; $i < count($lista_periodo_grade); $i++)
			{
				$selected = "";
				if ($lista_periodo_grade[$i]['Periodo'] == $lista_disc_turma_header['Periodo'])
					$selected = "selected";
				echo "<option class='background_dark' $selected value='" . $lista_periodo_grade[$i]['Periodo'] . "'>" . $lista_periodo_grade[$i]['Periodo'] . "º período</option>";
			}
			?>
		</select>
		<div class='input-group mb-2 mb-sm-0 text-danger' id='error-periodo_grade_id'></div>
		<?php else: ?>

		<?php
		for ($i = 0; $i < count($lista_periodo_grade); $i++)
		{
			if ($lista_periodo_grade[$i]['Periodo'] == $lista_disc_turma_header['Periodo'])
			{
				echo"<input readonly id='periodo_g' name='periodo_g' value='".$lista_periodo_grade[$i]['Periodo']."º' type='text' class='input-material'>";
				echo"<input id='periodo_grade_id' name='periodo_grade_id' value='".$lista_disc_turma_header['Periodo']."' type='hidden' class='input-material'>";
				echo"<label for='periodo_g' class='label-material'>Periodo</label>";
			}
		}
		?>
	<?php endif;?>
</div>
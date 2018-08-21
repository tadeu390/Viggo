<?php if(!empty($lista_subturmas) && COUNT($lista_subturmas) > 1) : ?>
	
	<select <?php echo "onchange='Main.get_alunos_chamada(".$url_part['disc_grade_id'].",".$url_part['turma_id'].");'"; ?> name='subturma' id='subturma' class='form-control' style='padding-left: 0px;'>
		<option value='0' style='background-color: #393836;'>Selecione uma subturma</option>
		<option value='all' style='background-color: #393836;'>Turma completa</option>
		<?php
			
			$hora_atual = date('H:i');

		for ($i = 0; $i < count($lista_subturmas); $i++)
		{
			echo "<option  class='background_dark' value='" . $lista_subturmas[$i]['Sub_turma'] . "'>Subturma " . $lista_subturmas[$i]['Sub_turma'] . "</option>";
		}

		/*for ($i = 0; $i < count($lista_horarios); $i++)
		{
			$selected = "";
			if($lista_horarios[0]['Dia_atual_semana'] == $lista_horarios[$i]['Dia_semana'] && 
				strtotime($hora_atual) >= strtotime($lista_horarios[$i]['Inicio']) && 
				strtotime($hora_atual) <= strtotime($lista_horarios[$i]['Fim'])
			  )
			{
				$selected = "selected";
				$disc_hor_id = $lista_horarios[$i]['Disc_hor_id'];
			}
			echo "<option $selected class='background_dark' value='" . $lista_horarios[$i]['Disc_hor_id'] . "'>" . $lista_horarios[$i]['Horario'] . "</option>";
		}*/
		?>
	</select>
<?php endif;?>
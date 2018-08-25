<?php if(!empty($lista_subturmas) && COUNT($lista_subturmas) > 1) : ?>
	
	<select <?php echo "onchange='Main.get_alunos_chamada(".$url_part['disciplina_id'].",".$url_part['turma_id'].");'"; ?> name='subturma' id='subturma' class='form-control' style='padding-left: 0px;'>
		<option value='0' style='background-color: #393836;'>Selecione uma subturma</option>
		<option value='all' style='background-color: #393836;'>Todos os alunos</option>
		<?php
			
			$hora_atual = date('H:i');

		for ($i = 0; $i < count($lista_subturmas); $i++)
		{
			$selected = "";
			if($lista_subturmas[$i]['Sub_turma'] == $sub_turma)
				$selected = "selected";

			echo "<option $selected class='background_dark' value='" . $lista_subturmas[$i]['Sub_turma'] . "'>Subturma " . $lista_subturmas[$i]['Sub_turma'] . "</option>";
		}
		?>
	</select>
<?php endif;?>
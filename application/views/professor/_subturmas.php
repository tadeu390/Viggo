<?php if(!empty($lista_subturmas) && $lista_subturmas[0]['Sub_turma'] != 0) : ?>
	
	<select <?php echo "onchange='Main.get_alunos_chamada(".$url_part['disciplina_id'].",".$url_part['turma_id'].");'"; ?> name='subturma' id='subturma' class='form-control' style='padding-left: 0px;'>
		<option value='x' style='background-color: #393836;'>Selecione uma subturma</option>
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
<?php else: ?>
	<input type="hidden" id="subturma" name="subturma" value="<?php echo 0; ?>" />
<?php endif;?>
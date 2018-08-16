<select onchange="Main.alterar_disciplina(this.value)" id='disciplina_id' class="form-control">
<?php
	for ($i = 0; $i < COUNT($lista_disciplinas); $i++) 
	{
		$selected = "";
		if($lista_disciplinas[$i]['Disc_grade_id'] == $url_part['disc_grade_id'])
			$selected = "selected";
		echo "<option $selected value='".$lista_disciplinas[$i]['Disc_grade_id']."'>";
			echo $lista_disciplinas[$i]['Nome_disciplina'];
		echo "</option>";
	}
?>
</select>
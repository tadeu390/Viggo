<select onchange="Main.alterar_disciplina(this.value)" id='disciplina_id' class="form-control">
<?php
	for ($i = 0; $i < COUNT($lista_disciplinas); $i++) 
	{
		$selected = "";
		if($lista_disciplinas[$i]['Disciplina_id'] == $url_part['disciplina_id'])
			$selected = "selected";
		echo "<option $selected value='".$lista_disciplinas[$i]['Disciplina_id']."'>";
			echo $lista_disciplinas[$i]['Nome_disciplina'];
		echo "</option>";
	}
?>
</select>
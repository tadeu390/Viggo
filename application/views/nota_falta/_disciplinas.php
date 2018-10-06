<div class="padding10 text-center">Disciplinas </div>
<select onchange="Main.alterar_disciplina(this.value)" id='disciplina_id' class="form-control">
<?php
	for ($i = 0; $i < COUNT($disciplinas); $i++) 
	{
		$selected = "";
		if($disciplinas[$i]['Disciplina_id'] == $url_part['disciplina_id'])
			$selected = "selected";
		echo "<option $selected value='".$disciplinas[$i]['Disciplina_id']."'>";
			echo $disciplinas[$i]['Nome_disciplina'];
		echo "</option>";
	}
?>
</select>
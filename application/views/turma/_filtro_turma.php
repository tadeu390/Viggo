<?php
	for($i = 0; $i < count($lista_turmas); $i++)
		echo"<option class='background_dark' value='". $lista_turmas[$i]['Id'] ."'>".$lista_turmas[$i]['Nome_turma']." / ".$lista_turmas[$i]['Pe_modi']."</option>";
?>
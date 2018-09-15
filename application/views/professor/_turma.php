<?php
	for ($i = 0; $i < COUNT($lista_turmas); $i++) 
	{ 
		$partial_class = "btn-danger";
		if($lista_turmas[$i]['Turma_id'] == $url_part['turma_id'])
		{
			$partial_class = "btn-success";
			echo "<input type='hidden' id='turma_selecionada' value='".$url_part['turma_id']."'>";
		}

		echo "<div class='col-lg-12'>";
			echo "<a href='".$url."professor/".$method."/".$url_part['disciplina_id']."/".$lista_turmas[$i]['Turma_id']."/".$url_part['etapa_id']."' class='btn ".$partial_class." btn-block' style='border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;'>";
				echo $lista_turmas[$i]['Nome_turma'];

			echo "</a>";
			echo "<button onclick='Main.horarios_turma(".$lista_turmas[$i]['Turma_id'].");' class='btn-warning btn-block' style='margin-top: 0px; margin-bottom: 10px; border-radius: 0px 0px 5px 5px;'>";
				echo "Horários";
			echo "</button>";
		echo "</div>";
		echo "<br />";
		echo "<br />";
	}
?>
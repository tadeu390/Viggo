<?php 
	for ($i = 0; $i < COUNT($lista_bimestres); $i++) 
	{
		$partial_class = "btn-danger";
		if($lista_bimestres[$i]['Id'] == $url_part['bimestre_id'])
		{
			$partial_class = "btn-success";
			echo "<input type='hidden' id='bimestre_selecionado' value='".$url_part['bimestre_id']."'>";
		}

		echo "<div class='col-lg-3  my-2'>";
			echo "<a href='".$url."professor/".$method."/".$url_part['disciplina_id']."/".$url_part['turma_id']."/".$lista_bimestres[$i]['Id']."' class='btn ".$partial_class." btn-block'>";
				echo $lista_bimestres[$i]['Nome']." (".$lista_bimestres[$i]['Valor']." pts)";
			echo "</a>";
		echo "</div>";
	}

	for ($i = 0; $i < COUNT($lista_notas_especiais); $i++) 
	{
		$partial_class = "btn-danger";
		if($lista_notas_especiais[$i]['Id'] == $url_part['bimestre_id'])
		{
			$partial_class = "btn-success";
			echo "<input type='hidden' id='bimestre_selecionado' value='".$url_part['bimestre_id']."'>";
		}

		echo "<div class='col-lg-4 my-3'>";
			echo "<a href='".$url."professor/".$method."/".$url_part['disciplina_id']."/".$url_part['turma_id']."/".$lista_notas_especiais[$i]['Id']."' class='btn ".$partial_class." btn-block'>";
				echo $lista_notas_especiais[$i]['Nome']." (".$lista_notas_especiais[$i]['Valor']." pts)";
			echo "</a>";
		echo "</div>";
	}
?>
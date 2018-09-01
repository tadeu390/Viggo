<?php 
	$ultimo = 0;
	if($method == "nota_especial")
		$method = "notas";
	for ($i = 0; $i < COUNT($lista_bimestres); $i++) 
	{
		$partial_class = "btn-danger";
		if($i == $url_part['botao'])
		{
			$partial_class = "btn-success";
			echo "<input type='hidden' id='etapa_selecionada' value='".$url_part['etapa_id']."'>";
			echo "<input type='hidden' id='botao_selecionado' value='".$url_part['botao']."'>";
		}

		echo "<div class='col-lg-3  my-2'>";
			echo "<a href='".$url."professor/".$method."/".$url_part['disciplina_id']."/".$url_part['turma_id']."/".$lista_bimestres[$i]['Id']."/".$i."' class='btn ".$partial_class." btn-block'>";
				echo $lista_bimestres[$i]['Nome']." (".$lista_bimestres[$i]['Valor']." pts)";
			echo "</a>";
		echo "</div>";
		$ultimo = $i;
	}

	$ultimo = $ultimo + 1;
	for ($i = 0; $i < COUNT($lista_notas_especiais); $i++)
	{
		$partial_class = "btn-danger";
		if($ultimo == $url_part['botao'])
		{
			$partial_class = "btn-success";
			echo "<input type='hidden' id='etapa_selecionada' value='".$url_part['etapa_id']."'>";
			echo "<input type='hidden' id='botao_selecionado' value='".$url_part['botao']."'>";
		}

		echo "<div class='col-lg-4 my-3'>";
			echo "<a href='".$url."professor/nota_especial/".$url_part['disciplina_id']."/".$url_part['turma_id']."/".$lista_notas_especiais[$i]['Id']."/".$ultimo."' class='btn ".$partial_class." btn-block'>";
				echo $lista_notas_especiais[$i]['Nome']." (".$lista_notas_especiais[$i]['Valor']." pts)";
			echo "</a>";
		echo "</div>";
		$ultimo = $ultimo + 1;
	}
?>
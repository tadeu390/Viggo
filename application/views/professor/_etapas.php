<?php 
	$ultimo = 0;
	if($method == "nota_especial")
		$method = "notas";
	for ($i = 0; $i < COUNT($lista_etapas); $i++) 
	{
		$partial_class = "btn-danger";
		if($lista_etapas[$i]['Id'] == $url_part['etapa_id'])
		{
			$partial_class = "btn-success";
			echo "<input type='hidden' id='etapa_selecionada' value='".$url_part['etapa_id']."'>";
		}

		echo "<div class='col-lg-3  my-2'>";
			echo "<a href='".$url."professor/".$method."/".$url_part['disciplina_id']."/".$url_part['turma_id']."/".$lista_etapas[$i]['Id']."' class='btn ".$partial_class." btn-block'>";
				echo $lista_etapas[$i]['Nome']." (".$lista_etapas[$i]['Valor']." pts)";
			echo "</a>";
		echo "</div>";
		$ultimo = $i;
	}
?>
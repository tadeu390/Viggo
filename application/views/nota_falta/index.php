<?php $this->load->helper("permissao");?>
<?php $this->load->helper("paginacao");?>
<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Notas e faltas</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
	<?php
		echo "<div class='col-lg-10 offset-lg-1 padding background_dark'>";
			echo "<div class='table-responsive'>";
				echo "<table class='table table-striped table-hover text-white'>";
					echo "<thead>";
						echo "<tr>";
							echo "<td colspan='4'>Escolha a turma</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>#</td>";
							echo "<td>Nome</td>";
							echo "<td class='text-right'>Ações</td>";
						echo "<tr>";
					echo "</thead>";
					echo "<tbody>";
						for($i = 0; $i < count($lista_turmas); $i++)
						{
							echo "<tr>";
								echo "<td>".($i + 1)."</td>";
								echo "<td>".$lista_turmas[$i]['Nome_turma']."</td>";
								echo "<td class='text-right'>";
								echo "<a href='".$url."$controller/turma/".$lista_turmas[$i]['Id']."' title='Selecionar' style='cursor: pointer;' class='glyphicon glyphicon-arrow-right text-danger'></a>";
								echo "</td>";
							echo "</tr>";
						}
					echo "</tbody>";
				echo "</table>";
			echo "</div>";
			paginacao::get_paginacao($paginacao,$controller);
		echo "</div>";
	?>
</div>
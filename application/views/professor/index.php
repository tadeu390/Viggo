<?php $this->load->helper("permissao");?>
<?php $this->load->helper("paginacao");?>
<?php $this->load->helper("mstring");?>
<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Minhas disciplinas</li>";
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
							echo "<td>#</td>";
							echo "<td>";
								echo"<a href='".$url."$controller/index/".$paginacao['pg_atual']."/Nome_disciplina/".$paginacao['order']."'>Disciplina</a>";
								if($paginacao['order'] == 'DESC' && $paginacao['field'] == 'Nome_disciplina')
									echo "&nbsp;<div class='fa fa-chevron-down'></div>";
								else if($paginacao['order'] == 'ASC' && $paginacao['field'] == 'Nome_disciplina')
									echo "&nbsp;<div class='fa fa-chevron-up'></div>";
							echo"</td>";
							echo "<td class='text-right'>Ações</td>";
						echo "<tr>";
					echo "</thead>";
					echo "<tbody>";
						for($i = 0; $i < count($lista_disciplinas); $i++)
						{
							echo "<tr>";
								echo "<td>".($i + 1)."</td>";
								echo "<td title='".$lista_disciplinas[$i]['Nome_disciplina']."'>";
									echo mstring::corta_string($lista_disciplinas[$i]['Nome_disciplina'], 50);
								echo "</td>";
								echo "<td class='text-right'>";
								if(permissao::get_permissao(UPDATE, $controller))
									echo "<a href='".$url."$controller/edit/".$lista_disciplinas[$i]['Disc_turma_id']."' title='Notas' style='cursor: pointer;' class='glyphicon glyphicon-edit text-danger'></a> | ";
								if(permissao::get_permissao(UPDATE, $controller))
									echo "<a href='".$url."$controller/edit/".$lista_disciplinas[$i]['Disc_turma_id']."' title='Faltas' style='cursor: pointer;' class='fa fa-calendar-check-o text-danger'></a>";
								echo "</td>";
							echo "</tr>";
						}
					echo "</tbody>";
				echo "</table>";
			echo "</div>";
			paginacao::get_paginacao($paginacao, $controller);
		echo "</div>";
	?>
</div>
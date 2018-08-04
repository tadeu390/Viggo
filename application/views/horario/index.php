<?php $this->load->helper("permissao");?>
<?php $this->load->helper("paginacao");?>
<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Horários</li>";
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
								echo"<a href='".$url."$controller/index/".$paginacao['pg_atual']."/Nome_turma/".$paginacao['order']."'>Turma</a>";
								if($paginacao['order'] == 'DESC' && $paginacao['field'] == 'Nome_turma')
									echo "&nbsp;<div class='fa fa-chevron-down'></div>";
								else if($paginacao['order'] == 'ASC' && $paginacao['field'] == 'Nome_turma')
									echo "&nbsp;<div class='fa fa-chevron-up'></div>";
							echo"</td>";
							echo "<td>";
								echo"<a href='".$url."$controller/index/".$paginacao['pg_atual']."/Nome_modalidade/".$paginacao['order']."'>Modalidade</a>";
								if($paginacao['order'] == 'DESC' && $paginacao['field'] == 'Nome_modalidade')
									echo "&nbsp;<div class='fa fa-chevron-down'></div>";
								else if($paginacao['order'] == 'ASC' && $paginacao['field'] == 'Nome_modalidade')
									echo "&nbsp;<div class='fa fa-chevron-up'></div>";
							echo "</td>";
							echo "<td>";
								echo"<a href='".$url."$controller/index/".$paginacao['pg_atual']."/Periodo/".$paginacao['order']."'>Período letivo</a>";
								if($paginacao['order'] == 'DESC' && $paginacao['field'] == 'Periodo')
									echo "&nbsp;<div class='fa fa-chevron-down'></div>";
								else if($paginacao['order'] == 'ASC' && $paginacao['field'] == 'Periodo')
									echo "&nbsp;<div class='fa fa-chevron-up'></div>";
							echo "</td>";
							echo "<td>";
								echo"<a href='".$url."$controller/index/".$paginacao['pg_atual']."/Ativo_turma/".$paginacao['order']."'>Ativo</a>";
								if($paginacao['order'] == 'DESC' && $paginacao['field'] == 'Ativo_turma')
									echo "&nbsp;<div class='fa fa-chevron-down'></div>";
								else if($paginacao['order'] == 'ASC' && $paginacao['field'] == 'Ativo_turma')
									echo "&nbsp;<div class='fa fa-chevron-up'></div>";
							echo "</td>";
							echo "<td class='text-right'>Ações</td>";
						echo "<tr>";
					echo "</thead>";
					echo "<tbody>";
						for($i = 0; $i < count($lista_turmas); $i++)
						{
							$cor = "";
							if($lista_turmas[$i]['Ativo_turma'] == 0)
								$cor = "class='color-danger'";
							echo "<tr>";
								echo "<td $cor>".($i + 1)."</td>";
								echo "<td $cor>".$lista_turmas[$i]['Nome_turma']."</td>";
								echo "<td $cor>".$lista_turmas[$i]['Nome_modalidade']."</td>";
								echo "<td $cor>".$lista_turmas[$i]['Periodo']."</td>";
								echo "<td $cor>".(($lista_turmas[$i]['Ativo_turma'] == 1) ? 'Sim' : 'Não')."</td>";
								echo "<td class='text-right'>";
								if(permissao::get_permissao(UPDATE, $controller))
									echo "<a href='".$url."$controller/create/".$lista_turmas[$i]['Id']."' title='Alterar horário' style='cursor: pointer;' class='glyphicon glyphicon-edit  text-danger'></a>";
							
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
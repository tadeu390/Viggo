<?php $this->load->helper("permissao");?>
<?php $this->load->helper("paginacao");?>
<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Cursos</li>";
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
						echo"<tr>";
							echo"<td class='text-right' colspan='4'>";
								if(permissao::get_permissao(CREATE, $controller))
									echo"<a class='btn btn-success' href='".$url."$controller/create/'><span class='glyphicon glyphicon-plus'></span> Novo curso</a>";
							echo"</td>";
						echo"</tr>";
						echo "<tr>";
							echo "<td>#</td>";
							echo "<td>";
								echo"<a href='".$url."$controller/index/".$paginacao['pg_atual']."/Nome_curso/".$paginacao['order']."'>Nome</a>";
								if($paginacao['order'] == 'DESC' && $paginacao['field'] == 'Nome_curso')
									echo "&nbsp;<div class='fa fa-chevron-down'></div>";
								else if($paginacao['order'] == 'ASC' && $paginacao['field'] == 'Nome_curso')
									echo "&nbsp;<div class='fa fa-chevron-up'></div>";
							echo"</td>";
							echo "<td>";
								echo"<a href='".$url."$controller/index/".$paginacao['pg_atual']."/Ativo/".$paginacao['order']."'>Ativo</a>";
								if($paginacao['order'] == 'DESC' && $paginacao['field'] == 'Ativo')
									echo "&nbsp;<div class='fa fa-chevron-down'></div>";
								else if($paginacao['order'] == 'ASC' && $paginacao['field'] == 'Ativo')
									echo "&nbsp;<div class='fa fa-chevron-up'></div>";
							echo"</td>";
							echo "<td class='text-right'>Ações</td>";
						echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
						for($i = 0; $i < count($lista_cursos); $i++)
						{
							$cor = "";
							if($lista_cursos[$i]['Ativo'] == 0)
								$cor = "class='color-danger'";
							echo "<tr>";
								echo "<td $cor>".($i + 1)."</td>";
								echo "<td $cor>".$lista_cursos[$i]['Nome_curso']."</td>";
								echo "<td $cor>".(($lista_cursos[$i]['Ativo'] == 1) ? 'Sim' : 'Não')."</td>";

								echo "<td style='text-align: right;'>";
									if(permissao::get_permissao(UPDATE, $controller))
										echo "<a href='".$url."index.php/curso/edit/".$lista_cursos[$i]['Id']."' title='Editar' style='cursor: pointer;' class='glyphicon glyphicon-edit text-danger'></a>  |  ";
										echo "<a href='".$url."$controller/detalhes/".$lista_cursos[$i]['Id']."' title='Detalhes' style='cursor: pointer;' class='glyphicon glyphicon-th text-danger'></a>  |  ";
									if(permissao::get_permissao(DELETE, $controller))
										echo "<span onclick='Main.confirm_delete(". $lista_cursos[$i]['Id'] .");' id='sp_lead_trash' name='sp_lead_trash' title='Apagar' style='cursor: pointer;' class='glyphicon glyphicon-trash text-danger'></span>";
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

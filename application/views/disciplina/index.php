<?php $this->load->helper("permissao");?>
<?php $this->load->helper("paginacao");?>
<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Disciplinas</li>";
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
									echo"<a class='btn btn-success' href='".$url."$controller/create/'><span class='glyphicon glyphicon-plus'></span> Nova disciplina</a>";
							echo"</td>";
						echo"</tr>";
						echo "<tr>";
							echo "<td>#</td>";
							echo "<td>Nome</td>";
							echo "<td>Ativo</td>";
							echo "<td class='text-right'>Ações</td>";
						echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
						for($i = 0; $i < count($lista_disciplinas); $i++)
						{
							$cor = "";
							if($lista_disciplinas[$i]['Ativo'] == 0)
								$cor = "class='color-danger'";
							echo "<tr>";
								echo "<td $cor>".($i + 1)."</td>";
								echo "<td $cor><span title='".$lista_disciplinas[$i]['Nome_disciplina']."'>".
								mstring::corta_string($usuarios[$i]['Nome_disciplina'], 25)
								."</span></td>";
								echo "<td $cor>".(($lista_disciplinas[$i]['Ativo'] == 1) ? 'Sim' : 'Não')."</td>";
								echo "<td style='text-align: right;'>";
									if(permissao::get_permissao(UPDATE, $controller))
										echo "<a href='".$url."index.php/disciplina/edit/".$lista_disciplinas[$i]['Id']."' title='Editar' style='cursor: pointer;' class='glyphicon glyphicon-edit text-danger'></a>  |  ";
									if(permissao::get_permissao(DELETE, $controller))
										echo "<span onclick='Main.confirm_delete(". $lista_disciplinas[$i]['Id'] .");' id='sp_lead_trash' name='sp_lead_trash' title='Apagar' style='cursor: pointer;' class='glyphicon glyphicon-trash text-danger'></span>";
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

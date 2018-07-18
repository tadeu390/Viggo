<?php $this->load->helper("permissao");?>
<?php $this->load->helper("paginacao");?>
<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Matrículas</li>";
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
							echo"<td class='text-right' colspan='7'>";
							if(permissao::get_permissao(CREATE,$controller))
								echo"<a class='btn btn-success' href='".$url."$controller/create/'><span class='glyphicon glyphicon-plus'></span> Nova matrícula</a>";
							echo"</td>";
						echo"</tr>";
						echo "<tr>";
							echo "<td>#</td>";
							echo "<td>Matrícula</td>";
							echo "<td>Aluno</td>";
							echo "<td>Curso</td>";
							echo "<td>Modalidade</td>";
							echo "<td style='width: 10%;'></td>";
							echo "<td class='text-right' style='width: 5%;'>Ações</td>";
						echo "<tr>";
					echo "</thead>";
					echo "<tbody>";
						for($i = 0; $i < count($lista_matriculas); $i++)
						{
							$cor = "";
							if($lista_matriculas[$i]['Ativo'] == 0)
								$cor = "class='color-danger'";

							echo "<tr>";
								echo "<td $cor style='vertical-align:middle'>".($i + 1)."</td>";
								echo "<td $cor style='vertical-align:middle'>".$lista_matriculas[$i]['Id']."</td>";
								echo "<td $cor style='vertical-align:middle'>".$lista_matriculas[$i]['Nome_usuario']."</td>";
								echo "<td $cor style='vertical-align:middle'>".$lista_matriculas[$i]['Nome_curso']."</td>";
								echo "<td $cor style='vertical-align:middle'>".$lista_matriculas[$i]['Nome_modalidade']."</td>";
								echo "<td>";
									echo"<input type='submit' class='btn btn-success btn-block text-white padding0' value='Matricular'>";
								echo "</td>";
								echo "<td class='text-right' style='vertical-align:middle'>";
								if(permissao::get_permissao(UPDATE,$controller))
									echo "<a href='".$url."$controller/edit/".$lista_matriculas[$i]['Id']."' title='Editar' style='cursor: pointer;' class='glyphicon glyphicon-edit  text-danger'></a> | ";
								if(permissao::get_permissao(DELETE,$controller))
									echo "<span onclick='Main.confirm_delete(". $lista_matriculas[$i]['Id'] .");' id='sp_lead_trash' name='sp_lead_trash' title='Apagar' style='cursor: pointer;' class='glyphicon glyphicon-trash text-danger'></span>";
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
<?php $this->load->helper("permissao");?>
<?php $this->load->helper("paginacao");?>
<br /><br />
<div class='row' id='container' name='container' style='padding: 20px;'>
	<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
	<?php
		echo "<div class='col-lg-10 offset-lg-1 padding' style='background: #393836;'>";
			echo "<div class='table-responsive'>";
				echo "<table class='table table-striped table-hover' style='color: white;'>";
					echo "<thead>";
						echo"<tr>";
							echo"<td class='text-center' colspan='5'>";
								echo"<p style='color: white; margin-top: 10px;'>Todas as Disciplinas</p>";
							echo"</td>";
						echo"</tr>";
						echo"<tr>";
						echo "<td colspan='3' class='text-right'>";
						if(permissao::get_permissao(CREATE,$controller))
								echo"<a class='btn btn-success' href='".$url."disciplina/create/0/'>Nova disciplina</a>";
						echo"</td></tr>";
						echo "<tr>";
							echo "<td>Nome</td>";
							echo "<td>Categoria</td>";
							echo "<td>Ações</td>";
						echo "<tr>";
					echo "</thead>";
					echo "<tbody>";
						for($i = 0; $i < count($Disciplinas); $i++)
						{
							$ativo = "";
							if($Disciplinas[$i]['ativo'] == 1)
								$ativo = "Sim";
							else
								$ativo = "Não";
							echo "<tr>";
								echo "<td>".$Disciplinas[$i]['nome_disciplina']."</td>";
								echo "<td>".$Disciplinas[$i]['nome_categoria']."</td>";

								echo "<td>";
									if(permissao::get_permissao(UPDATE,$controller))
										echo "<a href='".$url."index.php/disciplina/edit/".$Disciplinas[$i]['id']."' title='Editar' style='color: #dc3545; cursor: pointer;' class='glyphicon glyphicon-edit'></a>  |  ";
									if(permissao::get_permissao(DELETE,$controller))
										echo "<span onclick='Main.confirm_delete(". $Disciplinas[$i]['id'] .");' id='sp_lead_trash' name='sp_lead_trash' title='Apagar' style='color: #dc3545; cursor: pointer;' class='glyphicon glyphicon-trash'></span>";
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

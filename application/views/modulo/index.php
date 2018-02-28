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
								echo"<p style='color: white; margin-top: 10px;'>Todos os módulos</p>";
							echo"</td>";
						echo"</tr>";
						echo"<tr>";
							echo"<td class='text-right' colspan='6'>";
								if(permissao::get_permissao(CREATE,$controller))
									echo"<a class='btn btn-success' href='".$url."$controller/create/'>Novo módulo</a>";
							echo"</td>";
						echo"</tr>";
						echo "<tr>";
							echo "<td>Nome</td>";
							//echo "<td>Descrição</td>";
							//echo "<td>Ativo</td>";
							//echo "<td>Ordem</td>";
							echo "<td>Menu</td>";
							echo "<td class='text-right'></td>";
						echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
						for($i = 0; $i < count($lista_modulos); $i++)
						{
							$cor = "";
							if($lista_modulos[$i]['ativo'] == 0)
								$cor = "style='background-color: #dc3545;'";
							echo "<tr >";
								echo "<td $cor>".$lista_modulos[$i]['nome_modulo']."</td>";
								//echo "<td $cor>".$lista_modulos[$i]['descricao']."</td>";
								echo "<td $cor>".(($lista_modulos[$i]['ativo'] == 1) ? 'Sim' : 'Não')."</td>";
								//echo "<td $cor>".$lista_modulos[$i]['ordem']."</td>";
								//echo "<td $cor>".$lista_modulos[$i]['nome_menu']."</td>";
								echo "<td class='text-right'>";
								if(permissao::get_permissao(UPDATE,$controller))
									echo "<a href='".$url."$controller/edit/".$lista_modulos[$i]['id']."' title='Editar' style='color: #dc3545; cursor: pointer;' class='glyphicon glyphicon-edit'></a> | ";
									echo "<a href='".$url."$controller/detalhes/".$lista_modulos[$i]['id']."' title='Detalhes' style='color: #dc3545; cursor: pointer;' class='glyphicon glyphicon-th'></a>";
								if(permissao::get_permissao(DELETE,$controller))
									echo " | <span onclick='Main.confirm_delete(". $lista_modulos[$i]['id'] .");' id='sp_lead_trash' name='sp_lead_trash' title='Apagar' style='color: #dc3545; cursor: pointer;' class='glyphicon glyphicon-trash'></span>";
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

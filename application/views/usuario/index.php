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
								echo"<p style='color: white; margin-top: 10px;'>Todos os usuários</p>";
							echo"</td>";
						echo"</tr>";
						echo"<tr>";
							echo"<td class='text-right' colspan='5'>";
								echo"<a class='btn btn-danger' href='#'>PDF</a>";
							if(permissao::get_permissao(CREATE,$controller))
								echo"<a class='btn btn-success' href='".$url."$controller/create/0/".$paginacao['pg_atual']."/'>Novo usuário</a>";
							echo"</td>";
						echo"</tr>";
						echo "<tr>";
							echo "<td>Nome</td>";
							echo "<td>Ativo</td>";
							//echo "<td>E-mail</td>";
							//echo "<td>Grupo</td>";
							echo "<td class='text-right'></td>";
						echo "<tr>";
					echo "</thead>";
					echo "<tbody>";
						for($i = 0; $i < count($usuarios); $i++)
						{
							$cor = "";
							if($usuarios[$i]['ativo'] == 0)
								$cor = "style='background-color: #dc3545;'";
							echo "<tr>";
								echo "<td $cor>".$usuarios[$i]['nome_usuario']."</td>";
								echo "<td $cor>".(($usuarios[$i]['ativo'] == 1) ? 'Sim' : 'Não')."</td>";
								//echo "<td $cor>".$usuarios[$i]['email']."</td>";
								//echo "<td $cor>".$usuarios[$i]['nome_grupo']."</td>";
								echo "<td class='text-right'>";
									if(permissao::get_permissao(UPDATE,$controller))
										echo "<a href='".$url."$controller/edit/".$usuarios[$i]['id']."' title='Editar' style='color: #dc3545; cursor: pointer;' class='glyphicon glyphicon-edit'></a> | ";
									echo "<a href='".$url."$controller/detalhes/".$usuarios[$i]['id']."' title='Detalhes' style='color: #dc3545; cursor: pointer;' class='glyphicon glyphicon-th'></a>";
									if(permissao::get_permissao(DELETE,$controller))
										echo " | <span onclick='Main.confirm_delete(". $usuarios[$i]['id'] .");' id='sp_lead_trash' name='sp_lead_trash' title='Apagar' style='color: #dc3545; cursor: pointer;' class='glyphicon glyphicon-trash'></span>";
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

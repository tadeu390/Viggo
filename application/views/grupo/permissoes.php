<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."grupo'>Grupos</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Permissões dos usuários do grupo ".$grupo."</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<?php
		echo "<div class='col-lg-10 offset-lg-1 background_dark padding20'>";
			echo "<div class='form-group'>";
				echo"<div class='row'>";
				echo"<div class='col-lg-6'>";
					echo"<a href='javascript:window.history.go(-1)' title='Voltar'>";
						echo"<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>";
					echo"</a>";
				echo"</div>";
				echo"<div class='col-lg-6'>";
					echo"<table class='table table-sm table-bordered text-white' style='width: 100%;'>";
						echo"<tr>";
							echo"<td colspan='2'class='text-center'>";
								echo"Legenda";
							echo"</td>";
						echo"</tr>";
						echo"<tr>";
							echo"<td class='text-center'>";
								echo"<span class='btn padding0' style='cursor: default; background-color: #EDC127;;'>";
								echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								echo"</span>";
							echo"</td>";
							echo"<td style='vertical-align: middle;'>";
								echo"Nem todos os usuários possuem permissão.";
							echo"</td>";
						echo"</tr>";
						echo"<tr>";
							echo"<td class='text-center'>";
								echo"<span class='btn padding0' style='cursor: default; background-color: #336CD2;'>";
								echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								echo"</span>";
							echo"</td>";
							echo"<td style='vertical-align: middle;'>";
								echo"Todos os usuários possuem permissão.";
							echo"</td>";
						echo"</tr>";
					echo"</table>";
				echo"</div>";
				echo"</div>";
				
				$atr = array("id" => "form_cadastro_".$controller."_permissoes", "name" => "form_cadastro"); 
				echo form_open("$controller/store_permissoes", $atr);
					
					echo"<input type='hidden' id='grupo_id' name='grupo_id' value='".$grupo_id."'/>";
					echo"<input type='hidden' id='controller' value='".$controller."'/>";
					echo"<input type='hidden' id='method' value='store_permissoes'/>";
					
					echo "<div class='table-responsive'>";
						echo "<table class='table table-striped table-hover text-white'>";
							echo"<thead>";
								echo "<tr>";
									echo "<td>";
										echo"<div title='Habilitar/desabilitar todas as permissões para todos módulos' class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='hab_all'>";
												echo"<input type='checkbox' id='hab_all' name='hab_all'><span></span>";
											echo"</label>";
										echo"</div>";
									echo "</td>";
									echo "<td>";
										echo"<div onclick='Main.habilita_permissoes(\"hab_all_criar\");' title='Habilitar/desabilitar permissão de Criar para todos módulos' class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='hab_all_criar'>";
												echo"<input type='checkbox' id='hab_all_criar' name='hab_all_criar'><span></span>";
											echo"</label>";
										echo"</div>";
									echo "</td>";
									echo "<td>";
										echo"<div title='Habilitar/desabilitar permissão de Leitura para todos módulos' class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='hab_all_ler'>";
												echo"<input type='checkbox' id='hab_all_ler' name='hab_all_ler'><span></span>";
											echo"</label>";
										echo"</div>";
									echo "</td>";
									echo "<td>";
										echo"<div title='Habilitar/desabilitar permissão de Atualizar para todos módulos' class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='hab_all_atualizar'>";
												echo"<input type='checkbox' id='hab_all_atualizar' name='hab_all_atualizar'><span></span>";
											echo"</label>";
										echo"</div>";
									echo "</td>";
									echo "<td>";
										echo"<div title='Habilitar/desabilitar permissão de Remover para todos módulos' class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='hab_all_remover'>";
												echo"<input type='checkbox' id='hab_all_remover' name='hab_all_remover'><span></span>";
											echo"</label>";
										echo"</div>";
									echo "</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Módulo</td>";
									echo "<td>Criar</td>";
									echo "<td>Ler</td>";
									echo "<td>Atualizar</td>";
									echo "<td>Remover</td>";
								echo "</tr>";
							echo"</thead>";
							echo"<tbody>";
								if($lista_grupo_acesso[0]['Qtd_user'] > 0)
								{
									$limite = 0;
									for($i = 0; $i < count($lista_grupo_acesso); $i++)
									{
										echo"<tr>";
											echo"<td>";
												echo $lista_grupo_acesso[$i]['Nome_modulo'];
											echo"</td>";
											echo"<td>";
												echo "<input type='hidden' name='modulo_id".$i."' value='".$lista_grupo_acesso[$i]['Modulo_id']."' />";
												echo "<input type='hidden' name='acesso_id".$i."' value='".$lista_grupo_acesso[$i]['Acesso_id']."' />";
												$p = "success";
												if($lista_grupo_acesso[$i]['Qtd_user'] == $lista_grupo_acesso[$i]['Permissoes_criar'])
													$p = "success";
												else if($lista_grupo_acesso[$i]['Permissoes_criar'] > 0)
													$p = "Warning";
												$idd = 'cr'.$i;
												echo"<div class='checkbox checbox-switch switch-".$p." custom-controls-stacked' id='$idd'>";
													echo"<label for='criar$i'>";
														if($lista_grupo_acesso[$i]['Permissoes_criar'] > 0)
															echo"<input checked type='checkbox' id='criar$i' onclick='Main.troca_status(\"$idd\");' name='linha".$i."col0' value='1'><span></span>";
														else
															echo"<input type='checkbox' id='criar$i' name='linha".$i."col0' value='1'><span></span>";
													echo"</label>";
													echo "<input type='hidden' value='".$p."' id='flag$idd' name='flag$idd'>";
												echo"</div>";
											echo"</td>";
											echo"<td>";
												$p = "success";
												if($lista_grupo_acesso[$i]['Qtd_user'] == $lista_grupo_acesso[$i]['Permissoes_ler'])
													$p = "success";
												else if($lista_grupo_acesso[$i]['Permissoes_ler'] > 0)
													$p = "Warning";
												$idd = 'le'.$i;
												echo"<div class='checkbox checbox-switch switch-".$p." custom-controls-stacked' id='$idd'>";
													echo"<label for='ler$i'>";
														if($lista_grupo_acesso[$i]['Permissoes_ler'] > 0)
															echo"<input checked type='checkbox' id='ler$i' onclick='Main.troca_status(\"$idd\");' name='linha".$i."col1' value='1'><span></span>";
														else
															echo"<input type='checkbox' id='ler$i' name='linha".$i."col1' value='1'><span></span>";
													echo"</label>";
													echo "<input type='hidden' value='".$p."' id='flag$idd' name='flag$idd'>";
												echo"</div>";
											echo"</td>";
											echo"<td>";
												$p = "success";
												if($lista_grupo_acesso[$i]['Qtd_user'] == $lista_grupo_acesso[$i]['Permissoes_atualizar'])
													$p = "success";
												else if ($lista_grupo_acesso[$i]['Permissoes_atualizar'] > 0)
													$p = "Warning";
												$idd = 'at'.$i;
												echo"<div class='checkbox checbox-switch switch-".$p." custom-controls-stacked' id='$idd'>";
													echo"<label for='atualizar$i'>";
														if($lista_grupo_acesso[$i]['Permissoes_atualizar'] > 0)
															echo"<input checked type='checkbox' id='atualizar$i' onclick='Main.troca_status(\"$idd\");' name='linha".$i."col2' value='1'><span></span>";
														else
															echo"<input type='checkbox' id='atualizar$i' name='linha".$i."col2' value='1'><span></span>";
													echo"</label>"; //so falta colocar o input aqui em todos e o usar o conteudo da variavel sp como value
													echo "<input type='hidden' value='".$p."' id='flag$idd' name='flag$idd'>";
												echo"</div>";
											echo"</td>";
											echo"<td>";
												$p = "success";
												if($lista_grupo_acesso[$i]['Qtd_user'] == $lista_grupo_acesso[$i]['Permissoes_remover'])
													$p = "success";
												else if($lista_grupo_acesso[$i]['Permissoes_remover'] > 0)
													$p = "Warning";
												$idd = 're'.$i;
												echo"<div class='checkbox checbox-switch switch-".$p." custom-controls-stacked' id='$idd'>";
													echo"<label for='remover$i'>";
														if($lista_grupo_acesso[$i]['Permissoes_remover'] > 0)
															echo"<input checked type='checkbox' id='remover$i' onclick='Main.troca_status(\"$idd\");' name='linha".$i."col3' value='1'><span></span>";
														else
															echo"<input type='checkbox' id='remover$i' name='linha".$i."col3' value='1'><span></span>";
													echo"</label>";
													echo "<input type='hidden' value='".$p."' id='flag$idd' name='flag$idd'>";
												echo"</div>";
											echo"</td>";
										echo"</tr>";
										$limite = $limite + 1;
									}
								}
								else
								{
									echo "<tr>";
										echo "<td class='text-center' colspan='5'>";
											echo "Não existem usuários neste grupo, portanto, não é possível conceder ou remover Permissões.";
										echo "</td>";
									echo "</tr>";
								}
							echo"</tbody>";
						echo "</table>";
						echo"<input type='hidden' id='qtd' value='$limite'>";
					echo "</div>";

					if($lista_grupo_acesso[0]['Qtd_user'] > 0)
						echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Salvar alterações'>";
					else
						echo"<input type='submit' class='btn btn-danger btn-block disabled' disabled style='width: 200px;' value='Salvar alterações'>";

				echo "</form>";
			echo"</div>";
		echo "</div>";
	?>
</div>
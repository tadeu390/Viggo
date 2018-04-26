<br /><br />
<div class='row padding20' id='container' name='container'>
	<?php
		echo "<div class='col-lg-10 offset-lg-1 background_dark'>";
			echo "<div class='form-group'>";
			echo "<div class='padding20 text-white'>Permissões de: ".$usuario."</div>";
				
				$atr = array("id" => "form_cadastro_".$controller."_permissoes", "name" => "form_cadastro"); 
				echo form_open("$controller/store_permissoes", $atr);
					
					echo"<input type='hidden' id='usuario_id' name='usuario_id' value='".$usuario_id."'/>";
					echo"<input type='hidden' id='controller' value='".$controller."'/>";
					echo"<input type='hidden' id='method' value='store_permissoes'/>";
					
					echo "<div class='table-responsive'>";
						echo "<table class='table table-striped table-hover' style='color: white;'>";
							echo"<thead>";
								echo "<tr>";
									echo "<td>Módulo</td>";
									echo "<td class='text-center'>Criar</td>";
									echo "<td class='text-center'>Ler</td>";
									echo "<td class='text-center'>Atualizar</td>";
									echo "<td class='text-center'>Remover</td>";
								echo "</tr>";
							echo"</thead>";
							echo"<tbody>";
								for($i = 0; $i < count($lista_usuario_acesso); $i++)
								{
									echo"<tr>";
										echo"<td>";
											echo $lista_usuario_acesso[$i]['Nome_modulo'];
										echo"</td>";
										echo"<td class='text-center'>";
											echo "<input type='hidden' name='modulo_id".$i."' value='".$lista_usuario_acesso[$i]['Modulo_id']."' />";
											echo "<input type='hidden' name='acesso_id".$i."' value='".$lista_usuario_acesso[$i]['Acesso_id']."' />";
											echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
												echo"<label for='criar$i'>";
													if($lista_usuario_acesso[$i]['Criar'] == 1)
														echo"<input checked type='checkbox' id='criar$i' name='linha".$i."col0' value='1'><span></span>";
													else
														echo"<input type='checkbox' id='criar$i' name='linha".$i."col0' value='1'><span></span>";
												echo"</label>";
											echo"</div>";
										echo"</td>";
										echo"<td class='text-center'>";
											echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
												echo"<label for='ler$i'>";
													if($lista_usuario_acesso[$i]['Ler'] == 1)
														echo"<input checked type='checkbox' id='ler$i' name='linha".$i."col1' value='1'><span></span>";
													else
														echo"<input type='checkbox' id='ler$i' name='linha".$i."col1' value='1'><span></span>";
												echo"</label>";
											echo"</div>";
										echo"</td>";
										echo"<td class='text-center'>";
											echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
												echo"<label for='atualizar$i'>";
													if($lista_usuario_acesso[$i]['Atualizar'] == 1)
														echo"<input checked type='checkbox' id='atualizar$i' name='linha".$i."col2' value='1'><span></span>";
													else
														echo"<input type='checkbox' id='atualizar$i' name='linha".$i."col2' value='1'><span></span>";
												echo"</label>";
											echo"</div>";
										echo"</td>";
										echo"<td class='text-center'>";
											echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
												echo"<label for='remover$i'>";
													if($lista_usuario_acesso[$i]['Remover'] == 1)
														echo"<input checked type='checkbox' id='remover$i' name='linha".$i."col3' value='1'><span></span>";
													else
														echo"<input type='checkbox' id='remover$i' name='linha".$i."col3' value='1'><span></span>";
												echo"</label>";
											echo"</div>";
										echo"</td>";
									echo"</tr>";
								}
							echo"</tbody>";
						echo "</table>";
					echo "</div>";
					echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Salvar alterações'>";
				echo "</form>";
			echo"</div>";
		echo "</div>";
	?>
</div>

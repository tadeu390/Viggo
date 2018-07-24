<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Usuários</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Permissões do usuário ".$usuario."</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<?php
		echo "<div class='col-lg-10 offset-lg-1 background_dark padding20'>";
			echo "<div class='form-group'>";
				echo"<div>";
					echo"<a href='javascript:window.history.go(-1)' title='Voltar'>";
						echo"<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>";
					echo"</a>";
				echo"</div>";
				echo "<br />";
				echo "<br />";				
				$atr = array("id" => "form_cadastro_".$controller."_permissoes", "name" => "form_cadastro"); 
				echo form_open("$controller/store_permissoes", $atr);
					
					echo"<input type='hidden' id='usuario_id' name='usuario_id' value='".$usuario_id."'/>";
					echo"<input type='hidden' id='controller' value='".$controller."'/>";
					echo"<input type='hidden' id='method' value='store_permissoes'/>";
					
					echo "<div class='table-responsive'>";
						echo "<table class='table table-striped table-hover text-white'>";
							echo"<thead>";
								echo "<tr>";
									echo "<td>Módulo</td>";
									echo "<td>Criar</td>";
									echo "<td>Ler</td>";
									echo "<td>Atualizar</td>";
									echo "<td>Remover</td>";
								echo "</tr>";
							echo"</thead>";
							echo"<tbody>";
								for($i = 0; $i < count($lista_usuario_acesso); $i++)
								{
									echo"<tr>";
										echo"<td>";
											echo $lista_usuario_acesso[$i]['Nome_modulo'];
										echo"</td>";
										echo"<td>";
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
										echo"<td>";
											echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
												echo"<label for='ler$i'>";
													if($lista_usuario_acesso[$i]['Ler'] == 1)
														echo"<input checked type='checkbox' id='ler$i' name='linha".$i."col1' value='1'><span></span>";
													else
														echo"<input type='checkbox' id='ler$i' name='linha".$i."col1' value='1'><span></span>";
												echo"</label>";
											echo"</div>";
										echo"</td>";
										echo"<td>";
											echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
												echo"<label for='atualizar$i'>";
													if($lista_usuario_acesso[$i]['Atualizar'] == 1)
														echo"<input checked type='checkbox' id='atualizar$i' name='linha".$i."col2' value='1'><span></span>";
													else
														echo"<input type='checkbox' id='atualizar$i' name='linha".$i."col2' value='1'><span></span>";
												echo"</label>";
											echo"</div>";
										echo"</td>";
										echo"<td>";
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

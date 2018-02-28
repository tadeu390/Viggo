<br /><br />
<div class='row' id='container' name='container' style='padding: 20px;'>
	<?php
		echo "<div class='col-lg-10 offset-lg-1' style='background: #393836;'>";
			
			echo"<a href='javascript:window.history.go(-1)' class='padding' title='Voltar'>";
				echo"<span class='glyphicon glyphicon-arrow-left' style='font-size: 25px; color: white;'></span>";
			echo"</a>";
			
			echo "<div class='table-responsive'>";
				echo "<table class='table table-striped table-hover' style='color: white;'>";
					echo"<thead>";
						echo"<tr>";
							echo "<td colspan='5'>";
								echo"<p  class='text-center' style='margin-top: 10px; color: white;'>";
									echo"Detalhes do grupo selecionado";
								echo"</p>";
							echo"</td>";
						echo"</tr>";
						echo "<tr>";
							echo "<td>Módulo</td>";
							echo "<td class='text-center'>Criar</td>";
							echo "<td class='text-center'>Visualizar</td>";
							echo "<td class='text-center'>Atualizar</td>";
							echo "<td class='text-center'>Apagar</td>";
						echo "</tr>";
					echo"</thead>";
					echo"<tbody>";
						for($i = 0; $i < count($lista_grupos_acesso); $i++)
						{
							echo"<tr>";
								echo"<td>";
									echo $lista_grupos_acesso[$i]['nome_modulo'];
								echo"</td>";
								echo"<td class='text-center'>";
									echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
										echo"<label for='criar$i'>";
											if($lista_grupos_acesso[$i]['criar'] == 1)
												echo"<input checked type='checkbox' id='criar$i' disabled value='1'><span></span>";
											else
												echo"<input type='checkbox' id='criar$i' disabled value='1'><span></span>";
										echo"</label>";
									echo"</div>";
								echo"</td>";
								echo"<td class='text-center'>";
									echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
										echo"<label for='visualizar$i'>";
											if($lista_grupos_acesso[$i]['visualizar'] == 1)
												echo"<input checked type='checkbox' id='visualizar$i' disabled value='1'><span></span>";
											else
												echo"<input type='checkbox' id='visualizar$i' disabled value='1'><span></span>";
										echo"</label>";
									echo"</div>";
								echo"</td>";
								echo"<td class='text-center'>";
									echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
										echo"<label for='atualizar$i'>";
											if($lista_grupos_acesso[$i]['atualizar'] == 1)
												echo"<input checked type='checkbox' id='atualizar$i' disabled value='1'><span></span>";
											else
												echo"<input type='checkbox' id='atualizar$i' disabled value='1'><span></span>";
										echo"</label>";
									echo"</div>";
								echo"</td>";
								echo"<td class='text-center'>";
									echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
										echo"<label for='apagar$i'>";
											if($lista_grupos_acesso[$i]['apagar'] == 1)
												echo"<input checked type='checkbox' id='apagar$i' disabled value='1'><span></span>";
											else
												echo"<input type='checkbox' id='apagar$i' disabled value='1'><span></span>";
										echo"</label>";
									echo"</div>";
								echo"</td>";
							echo"</tr>";
						}
					echo"</tbody>";
				echo "</table>";
			echo "</div>";
			echo"<div class='form-group'>";
				echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
					$checked = "";
					if($obj['ativo'] == 1)
						$checked = "checked";
					
					echo"<label for='grupo_ativo' style='color: white;'>";
						echo "<input type='checkbox' disabled $checked id='grupo_ativo' name='grupo_ativo' value='1' /><span></span> Grupo ativo";
					echo"</label>";
				echo"</div>";
			echo"</div>";
		echo "</div>";
	?>
</div>
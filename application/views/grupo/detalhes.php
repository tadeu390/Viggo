<br /><br />
<div class='row padding20 text-white'>
		    <?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."grupo'>Grupos</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Detalhes</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<?php
		echo "<div class='col-lg-10 offset-lg-1 background_dark'>";
			
			echo"<a href='javascript:window.history.go(-1)' class='padding' title='Voltar'>";
				echo"<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>";
			echo"</a>";
			echo"<br />";
			echo"<br />";
			echo "<div class='table-responsive'>";
				echo "<table class='table table-striped table-hover text-white'>";
					echo"<thead>";
						echo"<tr>";
							echo"<td>";
								echo"Data de registro";
							echo"</td>";
							echo"<td colspan='4'>";
								echo $obj['Data_registro'];
							echo"</td>";
						echo"</tr>";
						echo"<tr>";
							echo"<td>";
								echo"Quantidade de usuários";
							echo"</td>";
							echo"<td colspan='4'>";
								echo $obj['Qtd_usuario'];
							echo"</td>";
						echo"</tr>";
						echo"<tr>";
							echo"<td>";
								echo"Quantidade de usuários ativos";
							echo"</td>";
							echo"<td colspan='4'>";
								echo $obj['Qtd_usuario_ativo'];
							echo"</td>";
						echo"</tr>";
						echo"<tr>";
							echo"<td colspan='5'>";
								echo"<br />Permissões padrões";
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
									echo $lista_grupos_acesso[$i]['Nome_modulo'];
								echo"</td>";
								echo"<td class='text-center'>";
									echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
										echo"<label for='criar$i'>";
											if($lista_grupos_acesso[$i]['Criar'] == 1)
												echo"<input checked type='checkbox' id='criar$i' disabled value='1'><span></span>";
											else
												echo"<input type='checkbox' id='criar$i' disabled value='1'><span></span>";
										echo"</label>";
									echo"</div>";
								echo"</td>";
								echo"<td class='text-center'>";
									echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
										echo"<label for='visualizar$i'>";
											if($lista_grupos_acesso[$i]['Ler'] == 1)
												echo"<input checked type='checkbox' id='visualizar$i' disabled value='1'><span></span>";
											else
												echo"<input type='checkbox' id='visualizar$i' disabled value='1'><span></span>";
										echo"</label>";
									echo"</div>";
								echo"</td>";
								echo"<td class='text-center'>";
									echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
										echo"<label for='atualizar$i'>";
											if($lista_grupos_acesso[$i]['Atualizar'] == 1)
												echo"<input checked type='checkbox' id='atualizar$i' disabled value='1'><span></span>";
											else
												echo"<input type='checkbox' id='atualizar$i' disabled value='1'><span></span>";
										echo"</label>";
									echo"</div>";
								echo"</td>";
								echo"<td class='text-center'>";
									echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
										echo"<label for='apagar$i'>";
											if($lista_grupos_acesso[$i]['Remover'] == 1)
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
					if($obj['Ativo'] == 1)
						$checked = "checked";
					
					echo"<label for='grupo_ativo'>";
						echo "<input type='checkbox' disabled $checked id='grupo_ativo' name='grupo_ativo' value='1' /><span></span> Grupo ativo";
					echo"</label>";
				echo"</div>";
			echo"</div>";
		echo "</div>";
	?>
</div>
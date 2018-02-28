<br /><br />
<div class='row' id='container' name='container' style='padding: 20px;'>
	<?php
		echo "<div class='col-lg-10 offset-lg-1' style='background: #393836;'>";
			echo"<a href='javascript:window.history.go(-1)' class='padding' title='Voltar'>";
				echo"<span class='glyphicon glyphicon-arrow-left' style='font-size: 25px; color: white;'></span>";
			echo"</a>";
			echo "<div class='table-responsive'>";
				echo "<table class='table table-striped table-hover' style='color: white;'>";
					echo"<tr>";
						echo "<td colspan='2'>";
							echo"<p  class='text-center' style='margin-top: 10px; color: white;'>";
								echo"Detalhes do usuário selecionado";
							echo"</p>";
						echo"</td>";
					echo"</tr>";
					echo"</tr>";
					echo "<tr>";
						echo "<td>Nome</td>";
						echo "<td>".$obj['nome_usuario']."</td>";
					echo"</tr>";
					echo"<tr>";
						echo "<td>Ativo</td>";
						echo "<td>".(($obj['ativo'] == 1) ? 'Sim' : 'Não')."</td>";
					echo "</tr>";
					echo"<tr>";
						echo "<td>Data de registro</td>";
						echo "<td>".$obj['data_registro']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>E-mail</td>";
						echo "<td>".$obj['email']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>Tipo de usuário</td>";
						echo "<td>".$obj['nome_grupo']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>Último acesso</td>";
						echo "<td>".$obj['ultimo_acesso']."</td>";
					echo "</tr>";
				echo "</table>";
			echo "</div>";
		echo "</div>";
	?>
</div>

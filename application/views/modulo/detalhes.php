<br /><br />
<div class='row' id='container' name='container' style='padding: 20px;'>
	<?php
		echo "<div class='col-lg-10 offset-lg-1' style='background: #393836;'>";
			echo"<a href='javascript:window.history.go(-1)' class='link padding' title='Voltar'>";
				echo"<span class='glyphicon glyphicon-arrow-left' style='font-size: 25px; color: white;'></span>";
			echo"</a>";
			echo "<div class='table-responsive'>";
				echo "<table class='table table-striped table-hover' style='color: white;'>";
					echo"<tr>";
						echo "<td colspan='2'>";
							echo"<p  class='text-center' style='margin-top: 10px; color: white;'>";
								echo"Detalhes do módulo selecionado";
							echo"</p>";
						echo"</td>";
					echo"</tr>";
					echo"</tr>";
					echo "<tr>";
						echo "<td>Nome</td>";
						echo "<td>".$obj['nome_modulo']."</td>";
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
						echo "<td>Descrição</td>";
						echo "<td>".$obj['descricao']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>Ordem</td>";
						echo "<td>".$obj['ordem']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>Menu</td>";
						echo "<td>".$obj['nome_menu']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>URL</td>";
						echo "<td>".$obj['url_modulo']."</td>";
					echo "</tr>";
				echo "</table>";
			echo "</div>";
		echo "</div>";
	?>
</div>

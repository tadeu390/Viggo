<br /><br />
<div class='row padding20' id='container' name='container'>
	    <?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."modulo'>Usuários</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Detalhes</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<?php
		echo "<div class='col-lg-10 offset-lg-1 background_dark'>";
			echo"<a href='javascript:window.history.go(-1)' class='padding' title='Voltar'>";
				echo"<span class='glyphicon glyphicon-arrow-left' style='font-size: 25px; color: white;'></span>";
			echo"</a>";
			echo "<div class='table-responsive'>";
				echo "<table class='table table-striped table-hover text-white'>";
					
					echo"</tr>";
					echo "<tr>";
						echo "<td>Nome</td>";
						echo "<td>".$obj['Nome_usuario']."</td>";
					echo"</tr>";
					echo"<tr>";
						echo "<td>Ativo</td>";
						echo "<td>".(($obj['Ativo'] == 1) ? 'Sim' : 'Não')."</td>";
					echo "</tr>";
					echo"<tr>";
						echo "<td>Data de registro</td>";
						echo "<td>".$obj['Data_registro']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>E-mail</td>";
						echo "<td>".$obj['Email']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>Tipo de usuário</td>";
						echo "<td>".$obj['Nome_grupo']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>Último acesso</td>";
						echo "<td>".$obj['Ultimo_acesso']."</td>";
					echo "</tr>";
				echo "</table>";
			echo "</div>";
		echo "</div>";
	?>
</div>
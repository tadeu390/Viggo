<?php
	echo "<table class='table table-striped table-hover text-white'>";
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
			echo "<td>Data de nascimento</td>";
			echo "<td>".$obj['Data_nascimento']."</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Sexo</td>";
			echo "<td>".(($obj['Sexo']) == 1 ? 'Masculino' : 'Feminino')."</td>";
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
?>
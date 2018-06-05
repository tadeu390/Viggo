<br /><br />
<div class='row padding20' id='container' name='container'>
	    <?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."usuario'>Usuários</a></li>";
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
				$this->load->view("usuario/_detalhes",$obj);
			
				echo "<h3 class='text-info'>Dados de matrícula do aluno</h3>";
			
				echo "<table class='table table-striped table-hover text-white'>";
					echo "<tr>";
						echo "<td>Matrícula</td>";
						echo "<td>".$obj_aluno['Matricula']."</td>";
					echo"</tr>";
				echo "</table>";
			echo "</div>";
		echo "</div>";
	?>
</div>
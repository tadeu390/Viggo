<br /><br />
<div class='row padding20 text-white'>
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
				echo"<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>";
			echo"</a>";
			echo "<br />";
			echo "<br />";
			echo "<div class='table-responsive'>";
				echo "<table class='table table-striped table-hover text-white'style='margin-bottom: 0px;'>";
					echo "<tr>";
						echo "<td style='width: 30%;'>";
							echo "Registro acadêmico (RA)";
						echo "</td>";
						echo "<td class='text-left'>";
							echo $obj['Id'];
						echo "</td>";
					echo "</tr>";
				echo "</table>";

				$this->load->view("usuario/_detalhes",$obj);
			echo "</div>";
		echo "</div>";
	?>
</div>
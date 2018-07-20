<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."turma'>Turmas</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Detalhes</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<?php
		echo "<div class='col-lg-10 offset-lg-1 background_dark'>";
			echo"<a href='javascript:window.history.go(-1)' class='link padding' title='Voltar'>";
				echo"<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>";
			echo"</a>";
			echo "<br />";
			echo "<br />";
			echo "<div class='table-responsive'>";
				echo "<table class='table table-striped table-hover text-white'>";
					echo"<tr>";
						echo "<td>Ativo</td>";
						echo "<td>".(($obj['Ativo'] == 1) ? 'Sim' : 'Não')."</td>";
					echo "</tr>";
					echo"<tr>";
						echo "<td>Data de registro</td>";
						echo "<td>".$obj['Data_registro']."</td>";
					echo "</tr>";
					echo"<tr>";
						echo "<td>Nome da turma</td>";
						echo "<td>".$lista_disc_turma_header['Nome_turma']."</td>";
					echo "</tr>";
					echo"<tr>";
						echo "<td>Período letivo</td>";
						echo "<td>".$lista_disc_turma_header['Nome_periodo']."</td>";
					echo "</tr>";
					echo"<tr>";
						echo "<td>Modalidade</td>";
						echo "<td>".$lista_disc_turma_header['Nome_modalidade']."</td>";
					echo "</tr>";
					echo"<tr>";
						echo "<td>Curso</td>";
						echo "<td>".$lista_disc_turma_header['Nome_curso']."</td>";
					echo "</tr>";
				echo "</table>";
			echo "</div>";
			echo "<br />";
			echo "<fieldset>";
				echo "<legend class='text-white'>&nbsp;Disciplinas</legend>";
				echo "<div class='disciplinas' id='disciplinas'>";
					$data['lista_disc_turma_disciplina'] = $lista_disc_turma_disciplina;
					$data['lista_disc_turma_profesor'] = $lista_disc_turma_professor;

					echo "<table class='table table-striped table-hover'>";
						echo "<thead>";
							echo "<tr>";
								echo "<td class='text-center'>Nome</td>";
								echo "<td class='text-center'>Categoria</td>";
								echo "<td class='text-center'>Professor</td>";
							echo "<tr>";
						echo "</thead>";
						echo "<tbody>";
							for ($i = 0; $i < count($lista_disc_turma_disciplina); $i++)
							{
								echo "<tr>";
									echo "<td class='text-center'>".$lista_disc_turma_disciplina[$i]['Nome_disciplina']."</td>";
									echo "<td class='text-center'>".$lista_disc_turma_disciplina[$i]['Nome_categoria']."</td>";
									if($lista_disc_turma_disciplina[$i]['Disciplina_id'] == $lista_disc_turma_professor[$i]['Disciplina_id'])
										echo "<td class='text-center'>".$lista_disc_turma_professor[$i]['Nome_professor']."</td>";
									else
										echo "<td class='text-center'>#</td>";
								echo "<tr>";
							}
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
			echo "</fieldset>";
			echo "<br />";
			echo "<fieldset>";
				echo "<legend class='text-white'>&nbsp;Alunos</legend>";
				echo "<div class='alunos' id='alunos'>";
					if (!empty($obj['Id']))
					{
						$data['lista_disc_turma_disciplina'] = $lista_disc_turma_disciplina;
						$data['lista_categorias'] = $lista_categorias;
						$data['lista_professores'] = $lista_professores;

						$this->load->view("turma/_alunos",$data);
					}
					else
					{
						echo "<table class='table table-striped table-hover'>";
							echo "<thead>";
								echo "<tr>";
									echo "<td class='text-center'>Nome</td>";
									echo "<td class='text-center'>Categoria</td>";
									echo "<td class='text-center'>Professor</td>";
								echo "<tr>";
							echo "</thead>";
							echo "<tbody>";
							echo "</tbody>";
						echo "</table>";
					}
				echo "</div>";
			echo "</fieldset>";
		echo "</div>";
	?>
</div>
<?php $this->load->helper("mstring");?>
<?php $this->load->helper("notas");?>
<?php $this->load->helper("faltas");?>

<table class="table table-bordered">
	<thead>
		<tr>
			<td rowspan="3" style="width: 20%;" class="align-middle text-center" >
				Aluno
				<?php 
					$count = 0;
					for ($i = 0; $i < COUNT($lista_etapas); $i++)
					{
						if($lista_etapas[$i]['Tipo'] == ETAPA_NORMAL)
						$count = $count + 1;
					}
				?>
			</td>
			<td class="text-center" colspan="<?php echo(($count * 2) + 1); ?>">Etapas</td>
			<td class="text-center" colspan="<?php echo(COUNT($lista_etapas) - $count); ?>">Etapas extras</td>
			<td rowspan="3" class="align-middle text-center">
				Status
			</td>
		</tr>
		<tr>
			<?php 
				$trava = 0;
				for ($i = 0; $i < COUNT($lista_etapas); $i++) 
				{
					$colspan = "";
					if($lista_etapas[$i]['Tipo'] == ETAPA_NORMAL)
						$colspan = "colspan='2'";
					else
					{
						if($trava == 0)
						{
							$trava = 1;
							echo "<td $colspan class='text-center align-middle' rowspan='2'>";
								echo "Total";
							echo "</td>";
						}
					}
					echo "<td $colspan class='text-center'>";
						echo $lista_etapas[$i]['Nome']." (".$lista_etapas[$i]['Valor']." pts)";
					echo "</td>";
				}
			?>
		</tr>
		<tr>
			<?php
				for ($i = 0; $i < COUNT($lista_etapas); $i++) 
				{ 
					echo "<td class='text-center' title='Notas'>";
						echo "N";
					echo "</td>";
					if($lista_etapas[$i]['Tipo'] == ETAPA_NORMAL)
					{
						echo "<td class='text-center' title='Faltas'>";
							echo "F";
						echo "</td>";
					}
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php 
			
			for($i = 0; $i < COUNT($lista_alunos); $i++)
			{
				$trava = 0;
				$total_nota = 0;
				echo "<tr>";
					echo "<td title='".$lista_alunos[$i]['Nome_aluno']."'>";
						echo mstring::corta_string($lista_alunos[$i]['Nome_aluno'], 25);
					echo "</td>";
					$situacao = "Matriculado";

					for($j = 0; $j < COUNT($lista_etapas); $j++)
					{
						$data_fim; //DATA EM QUE TERMINAR A ETAPA.

						if($lista_etapas[$j]['Tipo'] == ETAPA_NORMAL)
							$data_fim = $lista_etapas[$j]['Data_fim'];
						else
							$data_fim = $lista_etapas[$j]['Data_fechamento'];

						$timeZone = new DateTimeZone('UTC');
						
						$data_fim = DateTime::createFromFormat ('d/m/Y', $data_fim, $timeZone);
						$data_atual = DateTime::createFromFormat ('d/m/Y', '13/12/2018', $timeZone);
						
						$nota_etapa = notas::get_total_nota_etapa($lista_alunos[$i]['Matricula_id'], $lista_etapas[$j]['Id']);
						if($lista_etapas[$j]['Tipo'] == ETAPA_NORMAL)
						{
							$media_etapa = ($regra_letiva['Media'] / 100) * $lista_etapas[$j]['Valor'];

							$total_nota = $total_nota + $nota_etapa;

							$status = "text-info";
							if($nota_etapa < $media_etapa)
								$status = "text-danger";

							echo "<td class='text-center $status'>";
								echo $nota_etapa;
							echo "</td>";	

							echo "<td class='text-center'>";
								echo faltas::get_faltas_etapa($lista_etapas[$j]['Data_inicio2'], $lista_etapas[$j]['Data_fim2'], $lista_alunos[$i]['Matricula_id']);
							echo "</td>";

							//se estiver imprimindo a última etapa e o bimestre estiver fechado então determina o status do aluno.
							if($j == ($count - 1) && $data_atual > $data_fim)
							{
								$total_faltas = faltas::get_total_faltas($lista_alunos[$i]['Aluno_id'], $turma_id);

								if($total_faltas > $regra_letiva['Limite_falta'])
									$situacao = "Recuperação por faltas";
								else if($total_nota < $regra_letiva['Media'])
									$situacao = "Recuperação";
								else
									$situacao = "Aprovado";
							}
						}
						else
						{
							if($data_atual > $data_fim)
							{
								//pegar a primeira etapa extra, esta é a etapa de recuperaçao final, onde os alunos devem carregar passar carregando no máximo
								//o limite de disciplinas estabelecidos na regra do período letivo.



								$nota_etapa_extra = notas::get_total_nota_etapa($lista_alunos[$i]['Matricula_id'], $lista_etapas[$j]['Id']);
								if(!empty($nota_etapa_extra)) //MEXER NO STATUS DE SOMENTE ALUNOS QUE FORAM PARA A PRÓXIMA ETAPA.
								{
									//AO ENTRAR PELA PRIMEIRA VEZ SIGNIFICA QUE A PRIMEIRA ETAPA EXTRA ACABOU, ENTÃO PARA O QUE O ALUNO POSSA IR PARA A
									//PŔOXIMA ETAPA CASO NÃO TENHA PASSADO, ESTE SÓ PODE CARREGAR NO MÁXIMO O LIMITE DE DISICPLINAS ESTABELECIDOS NA REGRA
									//DO PERÍODO LETIVO.


									if($nota_etapa_extra >= $lista_etapas[$j]['Media'])
										$situacao = "Aprovado";
									else
									{
										$situacao = (($j < COUNT($lista_etapas)) ? $lista_etapas[($j + 1)]['Nome'] : "Reprovado"); //imprime o próximo status, se não houver, então reprovou

										//buscar a situação do aluno em todas as disciplinas que ele faz, e verificar quantas ele não passou
										//trocar isso com o if, pq se ele jaá reprovu em mais do que o limite permitido e aprovar em uma disciplina, vai aparecer aprovado direto
										//pro professor, sendo que o aluno passou nessa disciplina e só nessa por exemplo.
									}
								}
							}

							if($trava == 0)
							{
								$trava = 1;
								
								$status = "text-info";
								if($total_nota < $regra_letiva['Media'])
									$status = "text-danger";
								echo "<td class='text-center $status'>";
									echo $total_nota;
								echo "</td>";
							}//IMPRIME O TOTAL DE NOTA DAS ETAPAS/BIMESTRES

							//PRA BAIXO IMPRIME AS NOTAS EXTRAS, RECUPERAÇÃO.
							echo "<td class='text-center $status'>";
								echo $nota_etapa;
							echo "</td>";
						}
					}
					echo "<td class='text-center'>";
						echo $situacao;
					echo "</td>";
				echo"</tr>";
			}
		?>
	</tbody>
</table>
<br />
<br />
<br />
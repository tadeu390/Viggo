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
			<td class="text-center" colspan="<?php echo(($count * 2) + 2); ?>">Etapas</td>
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
							echo "<td title='Total da nota' $colspan class='text-center align-middle' rowspan='2'>";
								echo "Total N";
							echo "</td>";
							echo "<td title='Total de faltas' $colspan class='text-center align-middle' rowspan='2'>";
								echo "Total F";
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
					$total_falta_disc = 0;
						$falta_disc = 0;
					for($j = 0; $j < COUNT($lista_etapas); $j++)
					{
						$data_fim; //DATA EM QUE TERMINAR A ETAPA.

						if($lista_etapas[$j]['Tipo'] == ETAPA_NORMAL)
							$data_fim = $lista_etapas[$j]['Data_fim'];
						else
							$data_fim = $lista_etapas[$j]['Data_fechamento'];//etapas extas não possuem data fim

						$timeZone = new DateTimeZone('UTC');
						
						$data_fim = DateTime::createFromFormat ('d/m/Y', $data_fim, $timeZone);
						$data_atual = DateTime::createFromFormat ('d/m/Y', '10/06/2019', $timeZone);
						
						$nota_etapa = notas::get_total_nota_etapa($lista_alunos[$i]['Matricula_id'], $lista_etapas[$j]['Id']);
						if($lista_etapas[$j]['Tipo'] == ETAPA_NORMAL)
						{
							$media_etapa = ($regra_letiva['Media'] / 100) * $lista_etapas[$j]['Valor'];

							$total_nota = $total_nota + $nota_etapa;

							$status = "text-info";
							if($nota_etapa < $media_etapa)//aluno perdeu media
							{
								$status = "text-danger";
								//buscar a nota de recuperação da etapa.
								$nota_etapa_rec = notas::get_nota(RECUPERACAO_PARALELA, $lista_alunos[$i]['Matricula_id'], $lista_etapas[$j]['Id']);
								if($nota_etapa_rec >= $media_etapa)
								{
									$nota_etapa = $media_etapa;
									$status = "text-info";
								}
							}
							$falta_disc = faltas::get_faltas_etapa($lista_etapas[$j]['Data_inicio2'], $lista_etapas[$j]['Data_fim2'], $lista_alunos[$i]['Matricula_id']);
							
							$total_falta_disc = $total_falta_disc + $falta_disc;

							echo "<td class='text-center $status'>";
								echo $nota_etapa;
							echo "</td>";	

							echo "<td class='text-center'>";
								echo $falta_disc;
							echo "</td>";

							//se estiver imprimindo a última etapa e a etapa estiver fechada então determina o status do aluno.
							if($j == ($count - 1) && $data_atual > $data_fim)
							{
								$situacao = faltas::situacao_falta_aluno($lista_alunos[$i]['Aluno_id'], $turma_id, $regra_letiva, ETAPA_NORMAL);
								
								if($total_nota < $regra_letiva['Media'])
									$situacao = "Recuperação";
							}
						}
						else
						{
							if($trava == 0)
							{
								$trava = 1;
								
								$status = "text-info";
								if($total_nota < $regra_letiva['Media'])
									$status = "text-danger";
								echo "<td class='text-center $status'>";
									echo $total_nota;
								echo "</td>";
								echo "<td class='text-center'>";
									echo $total_falta_disc;
								echo "</td>";
							}//IMPRIME O TOTAL DE NOTA DAS ETAPAS/BIMESTRES

							if($data_atual > $data_fim)
							{
								//pegar a primeira etapa extra, esta é a etapa de estudos independentes, onde os alunos pode passar carregando no máximo
								//o limite de disciplinas estabelecidos na regra do período letivo.

								$nota_etapa_extra = notas::get_total_nota_etapa($lista_alunos[$i]['Matricula_id'], $lista_etapas[$j]['Id']);
								if(!empty($nota_etapa_extra)) //MEXER NO STATUS DE SOMENTE ALUNOS QUE FORAM PARA A PRÓXIMA ETAPA.
								{
									//AO ENTRAR PELA PRIMEIRA VEZ SIGNIFICA QUE A PRIMEIRA ETAPA EXTRA ACABOU, ENTÃO PARA O QUE O ALUNO POSSA IR PARA A
									//PŔOXIMA ETAPA CASO NÃO TENHA PASSADO, ESTE SÓ PODE CARREGAR NO MÁXIMO O LIMITE DE DISICPLINAS ESTABELECIDOS NA REGRA
									//DO PERÍODO LETIVO.


									if($nota_etapa_extra >= $lista_etapas[$j]['Media'])
									{	
										$status = "text-info";
										$situacao = "Aprovado";
									}
									else
									{
										$status = "text-danger";
										$situacao = (($j < COUNT($lista_etapas)) ? $lista_etapas[($j + 1)]['Nome'] : "Reprovado"); //imprime o próximo status, se não houver, então reprovou

										//buscar a situação do aluno em todas as disciplinas que ele faz, e verificar quantas ele não passou
										//trocar isso com o if, pq se ele jaá reprovu em mais do que o limite permitido e aprovar em uma disciplina, vai aparecer aprovado direto
										//pro professor, sendo que o aluno passou nessa disciplina e só nessa por exemplo.
									}
								}
							}
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
<?php $this->load->helper("notas");?>
<?php $this->load->helper("mstring");?>
<?php $this->load->helper("permissao");?>
<?php $this->load->view("/shared/_periodo");?>
<br />
<div class='row padding20 text-white relative' style="width: 98%; left: 2%">
	<?php
    	echo"<div class='col-lg-12 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Minhas disciplinas</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
	<input type='hidden' id='method' value='<?php echo $method; ?>'/>
	<div class='col-lg-12 padding background_dark'>
		<div class="row">
			<div class="col-lg-2 padding10" style="border-right: 1px solid white; border-bottom: 1px solid white">
				<?php
					$this->load->view("professor/_disciplina");
				?>
			</div>
			<div class="col-lg-10" style="border-bottom: 1px solid white">
				<div class="row padding10">
					<?php
						$this->load->view("professor/_etapas");
					?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-2" style="border-right: 1px solid white">
				<div class="row padding10">
					<?php
						$this->load->view("professor/_turma");
					?>
				</div>
			</div>
			<div class="col-lg-10">
				<div class="row padding10">
					<div class="col-lg-6">
						<a href="<?php echo $url; ?>professor/notas/<?php echo $url_part['disciplina_id']."/".$url_part['turma_id']."/".$url_part['etapa_id']; ?>" class="btn btn-success" style="width: 100px">Notas</a>
						<a href="<?php echo $url; ?>professor/faltas/<?php echo $url_part['disciplina_id']."/".$url_part['turma_id']."/".$url_part['etapa_id']; ?>" class="btn btn-danger" style="border-left: 1px solid white; width: 100px; margin-left: -8px; border-radius: 0px 5px 5px 0px;">Faltas</a>
						<a href="#" onclick="Main.visao_geral(<?php echo $url_part['disciplina_id'].",".$url_part['turma_id']; ?>);" class="btn btn-danger" style="border-left: 1px solid white; width: 100px; margin-left: -8px; border-radius: 0px 5px 5px 0px;">Visão geral</a>
					</div>
					<div class="col-lg-6 text-right">
						<?php 
							echo "Aberto a partir de ".(!empty($etapa['Data_abertura']) ? $etapa['Data_abertura'] : '')." até ".(!empty($etapa['Data_fechamento']) ? $etapa['Data_fechamento'] : '');
						?>
					</div>
				</div>
				<div class="row padding10" style="padding-top: 0px; padding-bottom: 0px;">
					<div class="col-lg-12">
						<hr style="background-color: white">
					</div>
				</div>
				<div class="row padding10">
					<div class="col-lg-4">
						<select <?php echo $status_etapa; ?> name='descricao_nota_id' id='descricao_nota_id' class='form-control' style='padding-left: 0px;'>
							<option value='0' style='background-color: #393836;'>Descrição de nota</option>
							<?php
							for ($i = 0; $i < count($lista_descricao_nota); $i++)
								echo "<option class='background_dark' value='" . $lista_descricao_nota[$i]['Id'] . "'>" . $lista_descricao_nota[$i]['Descricao'] . "</option>";
							?>
						</select>
					</div>
					<div class="col-lg-4">
						<?php if(permissao::get_permissao(CREATE, $controller) && permissao::get_permissao(UPDATE, $controller)): ?>
							<button class="btn btn-danger" <?php echo $status_etapa; ?> onclick="Main.add_coluna_nota();">Adicionar coluna</button>
						<?php endif; ?>
					</div>
				</div>
				<div class="row padding10">
					<div class="col-lg-12">
						<table class="table table-striped table-bordered text-white" style="width: auto;">
							<thead>
								<tr id='cabecalho_nota'>
									<td class="text-center align-middle">Aluno</td>
									<?php
										$limite_descricao_nota = 0;
										for ($i = 0; $i < COUNT($lista_colunas_nota) ; $i++)
										{
											if($lista_colunas_nota[$i]['Descricao_nota_id'] != RECUPERACAO_PARALELA)
											{
												echo "<td style='width: 10%; position: relative;' class='text-center align-middle'>";
													echo $lista_colunas_nota[$i]['Descricao'];
													echo "<input type='hidden' id='descricao_nota_id_hidden$i' value='".$lista_colunas_nota[$i]['Descricao_nota_id']."' />";
													if(empty($status_etapa))
														if(permissao::get_permissao(DELETE, $controller))
															echo "<span onclick='Main.confirm_remover_coluna_nota(".$lista_colunas_nota[$i]['Descricao_nota_id'].",",$url_part['turma_id'].",".$url_part['disciplina_id'].",".$url_part['etapa_id'].");' title='Remover coluna' style='cursor: pointer; position: absolute; right: 0px;' class='glyphicon glyphicon-remove text-danger'></span>";
												echo "</td>";
												$limite_descricao_nota = $limite_descricao_nota + 1;
											}
										}
									?>
									<td id='total' style="width: 10%;" class="text-center align-middle">Total</td style="width: auto;">
									<?php 
										for ($i = 0; $i < COUNT($lista_colunas_nota) ; $i++)
										{	
											if($lista_colunas_nota[$i]['Descricao_nota_id'] == RECUPERACAO_PARALELA)
											{
												echo "<td style='width: 10%; position: relative;' class='text-center'>";
													echo $lista_colunas_nota[$i]['Descricao'];
													echo "<input type='hidden' id='descricao_nota_id_hidden$i' value='".$lista_colunas_nota[$i]['Descricao_nota_id']."' />";
													if(empty($status_etapa))
														if(permissao::get_permissao(DELETE, $controller))
															echo "<span onclick='Main.confirm_remover_coluna_nota(".$lista_colunas_nota[$i]['Descricao_nota_id'].",",$url_part['turma_id'].",".$url_part['disciplina_id'].",".$url_part['etapa_id'].");' title='Remover coluna' style='cursor: pointer; position: absolute; right: 0px;' class='glyphicon glyphicon-remove text-danger'></span>";
												echo "</td>";
											}
										}
									?>
								</tr>
							</thead>
							<tbody>
								<?php
									$limite = 0;
									for ($i=0; $i < COUNT($lista_alunos); $i++)
									{
										echo "<tr id='linha".$i."'>";
											echo"<td class='align-middle' title='".$lista_alunos[$i]['Nome_aluno']."'>";
												echo mstring::corta_string($lista_alunos[$i]['Nome_aluno'], 30);
												echo "<input type='hidden' value='".$lista_alunos[$i]['Matricula_id']."' id='matricula_id$i' name='matricula_id$i' />";
											echo"</td>";
											$total = 0;
											for ($j = 0; $j < COUNT($lista_colunas_nota) ; $j++)
											{
												if($lista_colunas_nota[$j]['Descricao_nota_id'] != RECUPERACAO_PARALELA)
												{
													$nota = notas::get_nota($lista_colunas_nota[$j]['Descricao_nota_id'], $lista_alunos[$i]['Matricula_id'], $url_part['etapa_id']);
													$total = $total + $nota;
													echo"<td class='text-center' style='width: 10%;'>";
														if(permissao::get_permissao(CREATE, $controller) == false || permissao::get_permissao(UPDATE, $controller) == false)
															$status_etapa = '';
														echo"<input min='0' $status_etapa min='0' onblur='Main.altera_nota(\"total".$i."\", this.value,".$lista_colunas_nota[$j]['Descricao_nota_id'].",\"".$lista_alunos[$i]['Matricula_id']."\",".$url_part['etapa_id'].",\"aluno".$i."_nota".$j."\",".$url_part['disciplina_id'].",".$url_part['turma_id'].");' name='aluno".$i."_nota".$j."' type='number' value='".$nota."' class='form-control border_radius text-info' style='background-color: white;' />";
													echo"</td>";
												}
											}

											$status = notas::status_nota($url_part['etapa_id'], $periodo_letivo_id, $total);

											echo"<td class='text-center text-danger' id='td_total".$i."' style='vertical-align: middle; width: 10%;'>";
												 echo "<input type='text' id='total".$i."' value='".number_format($total,2)."' disabled class='border-".$status." form-control border_radius text-center text-".$status."' style=' background-color: white;' />";
											echo"</td>";
											for ($j = 0; $j < COUNT($lista_colunas_nota) ; $j++)
											{
												if($lista_colunas_nota[$j]['Descricao_nota_id'] == RECUPERACAO_PARALELA)
												{
													$nota = notas::get_nota($lista_colunas_nota[$j]['Descricao_nota_id'], $lista_alunos[$i]['Matricula_id'], $url_part['etapa_id']);
													$status = notas::status_nota($url_part['etapa_id'], $periodo_letivo_id , $nota);
													echo"<td class='text-center' style='width: 10%;'>";
														if(permissao::get_permissao(CREATE, $controller) == false || permissao::get_permissao(UPDATE, $controller) == false)
															$status_etapa = 'disabled';
														echo"<input min='0' $status_etapa min='0' onblur='Main.altera_nota(\"total".$i."\", this.value,".$lista_colunas_nota[$j]['Descricao_nota_id'].",\"".$lista_alunos[$i]['Matricula_id']."\",".$url_part['etapa_id'].",\"aluno".$i."_nota".$j."\",".$url_part['disciplina_id'].",".$url_part['turma_id'].");' id='aluno".$i."_nota".$j."'  name='aluno".$i."_nota".$j."' type='number' value='".$nota."' class='form-control border_radius text-".$status."' style='background-color: white;' />";
													echo"</td>";
												}
											}
										echo "</tr>";
										$limite = $limite + 1;
									}
									echo "<input type='hidden' value='".$limite."' id='linha_disponivel'>";
									echo "<input type='hidden' value='".$limite_descricao_nota."' id='limite_descricao_nota'>";
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
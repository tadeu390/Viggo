<?php $this->load->helper("notas");?>
<?php $this->load->helper("mstring");?>
<br /><br />
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
	<input type='hidden' id='method' value='notas_geral'/>
	<div class='col-lg-12 padding background_dark'>
		<div class="row">
			<div class="col-lg-2 padding10" style="border-right: 1px solid white; border-bottom: 1px solid white">
				<?php
					$data['lista_disciplinas'] = $lista_disciplinas;
					$this->load->view("professor/_disciplina", $data);
				?>
			</div>
			<div class="col-lg-10" style="border-bottom: 1px solid white">
				<div class="row padding10">
					<?php
						$data['lista_bimestres'] = $lista_bimestres;
						$this->load->view("professor/_bimestre", $data);
					?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-2" style="border-right: 1px solid white">
				<div class="row padding10">
					<?php
						$data['lista_turmas'] = $lista_turmas;
						$data['url_part'] = $url_part;
						$this->load->view("professor/_turma", $data);
					?>
				</div>
			</div>
			<div class="col-lg-10">
				<div class="row padding10">
					<div class="col-lg-4">
						<a href="<?php echo $url; ?>professor/notas/<?php echo $url_part['disciplina_id']."/".$url_part['turma_id']."/".$url_part['bimestre_id']; ?>" class="btn btn-success" style="width: 100px">Notas</a>
						<a href="<?php echo $url; ?>professor/faltas/<?php echo $url_part['disciplina_id']."/".$url_part['turma_id']."/".$url_part['bimestre_id']; ?>" class="btn btn-danger" style="width: 100px; margin-left: -8px; border-radius: 0px 5px 5px 0px;">Faltas</a>
					</div>
					<div class="col-lg-8 text-right">
						<?php 
							echo "Aberto a partir de ".(!empty($bimestre['Data_abertura']) ? $bimestre['Data_abertura'] : '')." até ".(!empty($bimestre['Data_fechamento']) ? $bimestre['Data_fechamento'] : '');
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
						
					</div>
					<div class="col-lg-4">

					</div>
					<div class="col-lg-4 text-right">
						<a href="<?php echo $url; ?>professor/notas_geral/<?php echo $url_part['disciplina_id']."/".$url_part['turma_id']."/".$url_part['bimestre_id']; ?>" class="btn btn-success" style="width: 100px">Geral</a>
						<a href="<?php echo $url; ?>professor/notas/<?php echo $url_part['disciplina_id']."/".$url_part['turma_id']."/".$url_part['bimestre_id']; ?>" class="btn btn-danger" style="width: 100px; margin-left: -8px; border-radius: 0px 5px 5px 0px;">Detalhado</a>
					</div>
				</div>
				<div class="row padding10">
					<div class="col-lg-12">
						<table class="table table-striped table-bordered text-white">
							<thead>
								<tr id='cabecalho_nota'>
									<td style="width: 25%;" class="text-center">Aluno</td>
									
									<td id='total' style="width: 10%;" class="text-center" title="Inclui a soma de todas as notas de todos os bimestres">Total</td style="width: auto;">
								</tr>
							</thead>
							<tbody>
								<?php
									for ($i=0; $i < COUNT($lista_alunos); $i++)
									{
										echo "<tr>";
											echo"<td style='vertical-align: middle;' title='".$lista_alunos[$i]['Nome_aluno']."'>";
												echo mstring::corta_string($lista_alunos[$i]['Nome_aluno'], 30);
												echo "<input type='hidden' value='".$lista_alunos[$i]['Matricula_id']."' id='matricula_id$i' name='matricula_id$i' />";
											echo"</td>";
											
											$total = notas::get_nota_total_aluno($lista_bimestres, $lista_alunos[$i]['Matricula_id'], $url_part['disciplina_id'], $url_part['turma_id']);
											$status = notas::status_nota_total($total, $periodo_letivo_id);

											echo"<td class='text-center text-danger' id='td_total".$i."' style='vertical-align: middle; width: 10%;'>";
												
												echo "<input type='text' id='total".$i."' value='".$total."' readonly='readonly' class='border-".$status." form-control border_radius text-center text-".$status."' style=' background-color: white;' />";
											echo"</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
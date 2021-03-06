<?php $this->load->helper("permissao");?>
<?php $this->load->helper("paginacao");?>
<?php $this->load->helper("faltas");?>
<?php $this->load->helper("mstring");?>
<br /><br />
<div class='row padding20 text-white relative' style="width: 95%; left: 3.5%">
	<?php
    	echo"<div class='col-lg-12 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Notas e faltas</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".$disc_turma_header['Nome_turma']."</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
    <input type='hidden' id='method' value='<?php echo $method; ?>'/>
    <input type='hidden' id='turma_selecionada' value='<?php echo $url_part['turma_id']; ?>'/>
	<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
	<div class='col-lg-12 padding background_dark'>
		<div>
			<a href='javascript:window.history.go(-1)' title='Voltar'>
				<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
			</a>
		</div>
		<br />

		<div class="row" style="border-bottom: 1px solid white;">
			<div class="col-lg-2" style="border-right: 1px solid white; ">
				<?php $this->load->view("/nota_falta/_disciplinas"); ?>
			</div>
			<div class="col-lg-10">
				<div class="row padding10">
					<?php $this->load->view("/nota_falta/_etapas"); ?>
				</div>
			</div>
		</div>
		<div class="row padding10" style="border-bottom: 1px solid white;">
			<div class="col-lg-12">
				<div class="row padding10">
					<div class="col-lg-6 text-center">
						<a href="<?php echo $url; ?>nota_falta/notas/<?php echo $url_part['disciplina_id']."/".$url_part['turma_id']."/".$url_part['etapa_id']; ?>" class="btn btn-danger" style="width: 100px">Notas</a>
						<a href="<?php echo $url; ?>nota_falta/faltas/<?php echo $url_part['disciplina_id']."/".$url_part['turma_id']."/".$url_part['etapa_id']; ?>" class="btn btn-success" style="border-left: 1px solid white; width: 100px; margin-left: -8px; border-radius: 0px 5px 5px 0px;">Faltas</a>
						<a href="#" onclick="Main.visao_geral(<?php echo $url_part['disciplina_id'].",".$url_part['turma_id']; ?>);" class="btn btn-danger" style="border-left: 1px solid white; width: 100px; margin-left: -8px; border-radius: 0px 5px 5px 0px;">Visão geral</a>
					</div>
					<div class="col-lg-6 text-center" style="margin-top: 7px;">
						<?php 
							echo "Inicia em ".(!empty($etapa['Data_inicio']) ? $etapa['Data_inicio'] : '')." e termina em ".(!empty($etapa['Data_fim']) ? $etapa['Data_fim'] : '');
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="row padding10">
			<div class="col-lg-4">
				<?php if(permissao::get_permissao(CREATE, $controller) && permissao::get_permissao(UPDATE, $controller)): ?>
					<a href="<?php echo $url; ?>nota_falta/chamada/<?php echo $url_part['disciplina_id']."/".$url_part['turma_id']."/".$url_part['etapa_id']; ?>" class="btn btn-primary">Fazer chamada</a>
				<?php endif; ?>
			</div>
		</div>
		<div class="row padding10">
			<div class="col-lg-12">
				<table class="table table-bordered text-white">
					<thead>
						<tr>
							<td>Alunos</td>
							<?php 
								$total_col = COUNT($meses) + 1;
								$percentual = 50 / $total_col;

								for($i = 0; $i < COUNT($meses); $i ++)
								{
									echo "<td class='text-center' title='Total de faltas do mês de ".$meses[$i]['Mes']."'>";
										echo $meses[$i]['Mes'];
									echo"</td>";
								}
							?>
							<td class="text-center" title="Total de faltas no bimestre">Total</td>
						</tr>
					</thead>
					<tbody>
					<?php 
						for($i = 0; $i < COUNT($lista_alunos); $i++)
						{
							echo "<tr>";
								echo "<td class='w-50 align-middle' title='".$lista_alunos[$i]['Nome_aluno']."'>";
									echo mstring::corta_string($lista_alunos[$i]['Nome_aluno'], 30);
								echo "</td>";
								$total_faltas = 0;
								for($j = 0; $j < COUNT($meses); $j ++)
								{
									$faltas = faltas::get_faltas($lista_alunos[$i]['Matricula_id'], $etapa['Data_inicio2'], $etapa['Data_fim2'], $meses[$j]['Mes_numero'])['Faltas']; 
									echo "<td class='text-center' style='width: ".$percentual."%;'>";
										echo "<input disabled  type='text' value='".$faltas."' class='form-control background_white border_radius text-center text-info'>";
									echo"</td>";
									$total_faltas = $total_faltas + $faltas;
								}
								echo "<td class='text-center'>";
									echo "<input disabled  type='text' value='".$total_faltas."' class='form-control background_white border_radius text-center text-info'>";
									
								echo "</td>";
							echo"</tr>";
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
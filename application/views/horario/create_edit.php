<?php $this->load->helper("mtime");?>
<br /><br />
<div class='row padding20 text-white relative' style="width: 98%; left: 2%">
	<?php
    	echo"<div class='col-lg-12 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Horários</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Alterar horário' : 'Alterar horário')."</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<div class='col-lg-12 padding background_dark'>
		<div>
			<a href='javascript:window.history.go(-1)' title='Voltar'>
				<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
			</a>
		</div>
		<br /><br />
			<?php $atr = array("id" => "form_cadastro_$controller", "name" => "form_cadastro"); 
				echo form_open("$controller/store", $atr);
			?>
				<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['Id'])) echo $obj['Id']; ?>'/>
				<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group relative">
							<input spellcheck="false" maxlength="20" id="nome" name="nome" value='<?php echo (!empty($obj['Nome_turma']) ? $obj['Nome_turma']:''); ?>' type="text" class="input-material">
							<label for="nome" class="label-material">Nome</label>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class='form-group relative'>
							<?php
							for ($i = 0; $i < count($lista_modalidades); $i++)
							{
								if ($lista_modalidades[$i]['Id'] == $lista_disc_turma_header['Modalidade_id'])
								{
									echo"<input readonly id='modalidade' name='modalidade' value='".$lista_modalidades[$i]['Nome_modalidade']."' type='text' class='input-material'>";
									echo"<input id='modalidade_id' name='modalidade_id' value='".$lista_disc_turma_header['Modalidade_id']."' type='hidden' class='input-material'>";
									echo"<label for='modalidade' class='label-material active'>Modalidade</label>";
								}
							}
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group relative">
							<input readonly="true" id="nome_periodo_letivo" name="nome_periodo_letivo" value='<?php echo (!empty($lista_disc_turma_header['Nome_periodo']) ? $lista_disc_turma_header['Nome_periodo']:''); ?>' type="text" class="input-material">
							<label for="nome_periodo_letivo" class="label-material active">Período letivo</label>
						</div>
					</div>
					<div class="col-lg-6">
						<div class='form-group relative'>
							<?php
							for ($i = 0; $i < count($lista_cursos); $i++)
							{
								if ($lista_cursos[$i]['Id'] == $lista_disc_turma_header['Curso_id'])
								{
									echo"<input readonly id='curso' name='curso' value='".$lista_cursos[$i]['Nome_curso']."' type='text' class='input-material'>";
									echo"<input id='curso_id' name='curso_id' value='".$lista_disc_turma_header['Curso_id']."' type='hidden' class='input-material'>";
									echo"<label for='curso' class='label-material active'>Curso</label>";
								}
							}
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6" id='grade'>
						<?php
							$data['lista_grades'] = $lista_grades;

							$data['lista_disc_turma_header']['Grade_id'] = $lista_disc_turma_header['Grade_id'];
							$this->load->view("turma/_grade", $data);
						?>
					</div>
					<div class="col-lg-6" id='periodo_grade'>
						<?php
							$data['lista_periodo_grade'] = $lista_periodo_grade;
							$data['lista_disc_turma_header']['Periodo'] = $lista_disc_turma_header['Periodo'];;

							$this->load->view("turma/_periodo_grade", $data);
						?>
					</div>
				</div>
				<br />
				<fieldset>
					<legend>&nbsp;Quadro de horários</legend>
					<div style="overflow: auto;">
					<table class="table table-bordered text-white">
						<thead>
							<tr>
								<td style="width: 5%;" class="text-center">Aula</td>
								<td>Horário</td>
								<td>Segunda</td>
								<td>Terça</td>
								<td>Quarta</td>
								<td>Quinta</td>
								<td>Sexta</td>
								<td>Sábado</td>
								<td>Domingo</td>
							</tr>
						</thead>
						<tbody>
							<?php
								
								
								$Hora_inicio_aula = $regras['Hora_inicio_aula'];
								for($i = 0; $i < $regras['Quantidade_aula']; $i++)
								{
									echo "<tr>";
										echo "<td class='text-center'>";
											echo ($i + 1);
										echo "</td>";
										echo "<td>";
											echo $Hora_inicio_aula." | ";
											$Hora_inicio_aula =  mtime::add_time($Hora_inicio_aula, $regras['Duracao_aula']);			
											echo $Hora_inicio_aula;
										echo "</td>";
										echo "<td>";
											echo "teste";
										echo "</td>";
										echo "<td>";
											echo "teste";
										echo "</td>";
										echo "<td>";
											echo "teste";
										echo "</td>";
										echo "<td>";
											echo "teste";
										echo "</td>";
										echo "<td>";
											echo "teste";
										echo "</td>";
										echo "<td>";
											echo "teste";
										echo "</td>";
										echo "<td>";
											echo "teste";
										echo "</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
				</fieldset>
				<br />
				<?php
					if(empty($obj['Id']))
						echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
					else
						echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
				?>
			</form>
	</div>
</div>
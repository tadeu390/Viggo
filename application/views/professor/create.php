<?php $this->load->helper("mstring");?>
<?php $this->load->helper("faltas");?>
<br /><br />
<div class='row padding20 text-white relative' style="width: 98%; left: 2%">
	<?php
    	echo"<div class='col-lg-12 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Minhas disciplinas</li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar chamada' : 'Nova chamada')."</li>";
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
						<a href="<?php echo $url; ?>professor/notas/<?php echo $url_part['disc_grade_id']."/".$url_part['turma_id']."/".$url_part['bimestre_id']; ?>" class="btn btn-danger" style="width: 100px">Notas</a>
						<a href="<?php echo $url; ?>professor/faltas/<?php echo $url_part['disc_grade_id']."/".$url_part['turma_id']."/".$url_part['bimestre_id']; ?>" class="btn btn-success" style="width: 100px; margin-left: -8px; border-radius: 0px 5px 5px 0px;">Faltas</a>
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
				<div class="row padding10" style="padding-top: 0px">
					<div class="col-lg-12">
						<a href='javascript:window.history.go(-1)' title='Voltar'>
							<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
						</a>
					</div>
				</div>
				<?php $atr = array("id" => "form_cadastro_chamada", "name" => "form_cadastro"); 
					echo form_open("$controller/store_chamada", $atr);
					echo "<input type='hidden' id='turma_id_form' name='turma_id_form' value='".$url_part['turma_id']."'>";
					echo "<input type='hidden' id='disc_grade_id_form' name='disc_grade_id_form' value='".$url_part['disc_grade_id']."'>";
				?>	
				<div class="row padding10">
					<div class="col-lg-6">
						<div class="form-group relative" id="data1">
							<input <?php echo "onchange='Main.get_alunos_chamada(".$url_part['disc_grade_id'].",".$url_part['turma_id'].");'"; ?> id="data_atual" name="data_atual" value="<?php echo date('d/m/Y');?>" type="text" class="input-material">
							<label for="data_nascimento" class="label-material active">Data</label>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-data_atual'></div>
						</div>
					</div>
					<div class="col-lg-6" id='subturmas'>
						<?php 
							$data['lista_subturmas'] = $lista_subturmas;
							$data['url_part'] = $url_part;

							$this->load->view("professor/_subturmas", $data);
						?>
					</div>
					<div class="col-lg-12" id='alunos_chamada'>
						<?php 
						if(!empty($lista_subturmas) && COUNT($lista_subturmas) == 1)
						{
							$subturma = $lista_subturmas[0]['Sub_turma']; 
							$data['lista_alunos'] = faltas::get_alunos_chamada($url_part['disc_grade_id'], $url_part['turma_id'], $subturma);
							$data['lista_horarios'] = $lista_horarios;
							$data['lista_subturmas'] = $lista_subturmas;
							$this->load->view("professor/_alunos", $data);
						}	
						?>
					</div>
					<div class="col-lg-12">
						<div class="form-group">
							<textarea id='conteudo_lecionado' name="conteudo_lecionado" style="height: 100px;" class="form-control background_white border_radius" placeholder="Conteúdo lecionado"></textarea>
						</div>
					</div>
				</div>
				<div class="row padding10" style="padding-top: 0px; padding-bottom: 0px;">
					<div class="col-lg-12">
						<?php
							if (empty($obj['Id']))
								echo "<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
							else
								echo "<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
							?>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
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
    <input type='hidden' id='disciplina_selecionada' value='<?php echo $url_part['disciplina_id']; ?>'/>
    <input type='hidden' id='etapa_selecionada' value='<?php echo $url_part['etapa_id']; ?>'/>
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
			<div class="col-lg-12">
				
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
					echo "<input type='hidden' id='disciplina_id_form' name='disciplina_id_form' value='".$url_part['disciplina_id']."'>";
				?>	

				<div class="row padding10">
					<div class="col-lg-6">
						<div class="form-group relative" id="data1">
							<input <?php echo "onchange='Main.get_sub_turmas(".$url_part['disciplina_id'].",".$url_part['turma_id'].", this.value);'"; ?> id="data_atual" name="data_atual" value="<?php echo date('d/m/Y');?>" type="text" class="input-material">
							<label for="data_atual" class="label-material active">Data</label>
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-data_atual'></div>
						</div>
					</div>
					<div class="col-lg-6" id='subturmas'>
						<?php 
							$this->load->view("nota_falta/_subturmas");
						?>
					</div>
					<div id='chamada' class="col-lg-12">
					<?php
						$this->load->view("nota_falta/_alunos");
					?>
					</div>
				</div>
				<div class="row padding10" style="padding-top: 0px; padding-bottom: 0px;">
					<div class="col-lg-12" id="div_btn_save">
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
<br /><br />
<div class='row padding20 text-white'>
    <?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."regras'>Regras letivas</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar regra' : 'Nova regra')."</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<div class='col-lg-10 offset-lg-1 padding background_dark'>
		<div>
			<a href='javascript:window.history.go(-1)' title='Voltar'>
				<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
			</a>
		</div>
		<br /><br />
		<?php $atr = array("id" => "form_cadastro_$controller", "name" => "form_cadastro"); 
			echo form_open("$controller/store", $atr); 
		?>
		<input type='hidden' id='id' name='id' value='<?php echo (!empty($obj['Id']) ? $obj['Id'] :'' )?>'/>
		<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
		
		<div class="row">
			<div class="col-lg-6">
				<div class='form-group'>
					<div class='input-group-addon' style="color: #8a8d93;">Modalidade</div>
					<select name='modalidade_id' id='modalidade_id' class='form-control' style='padding-left: 0px;'>
						<option value='0' style='background-color: #393836;'>Selecione</option>
						<?php
						for ($i = 0; $i < count($modalidades); $i++)
						{
							$selected = "";
							if ($modalidades[$i]['Id'] == $obj['Modalidade_id'])
								$selected = "selected";
							echo "<option class='background_dark' $selected value='" . $modalidades[$i]['Id'] . "'>" . $modalidades[$i]['Nome_modalidade'] . "</option>";
						}
						?>
					</select>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-modalidade_id'></div>
				</div>

				<div class="form-group relative">
					<input id="periodo" name="periodo" value='<?php echo (!empty($obj['Periodo']) ? $obj['Periodo']:''); ?>' type="text" class="input-material">
					<label for="periodo" class="label-material">Periodo</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-periodo'></div>
				</div>

				<div class='form-group'>
					<div class='input-group-addon' style="color: #8a8d93;">Avaliar faltas por: 
						<span class='glyphicon glyphicon-question-sign text-danger pointer'  data-toggle="tooltip" title="Se o limite de falta for por disciplina, deverá ser especificado o limite para cada disciplina ao criar a turma."></span></div>
					<select id="avaliar_faltas" name="avaliar_faltas" class='form-control padding0'>
						<option class='background_dark' value="0">Selecione</option>
						<option class='background_dark' value="1">Disciplina</option>
						<option class='background_dark' value="2">Todas</option>
					</select>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-avaliar_faltas'></div>
				</div>

				<div class="form-group relative">
					<input id="limite_falta" name="limite_falta" value='<?php echo (!empty($obj['Limite_falta']) ? $obj['Limite_falta']:''); ?>' type="text" class="input-material">
					<label for="limite_falta" class="label-material">Limite de falta</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-limite_falta'></div>
				</div>

				<div class="form-group relative">
					<input id="dias_letivos" name="dias_letivos" value='<?php echo (!empty($obj['Dias_letivos']) ? $obj['Dias_letivos']:''); ?>' type="text" class="input-material">
					<label for="dias_letivos" class="label-material">Dias letivos</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-dias_letivos'></div>
				</div>

				<div class="form-group relative">
					<input id="media" name="media" value='<?php echo (!empty($obj['Media']) ? $obj['Media']:''); ?>' type="text" class="input-material">
					<label for="media" class="label-material">Média de aprovação</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-media'></div>
				</div>

				<div class="form-group relative">
					<input id="duracao_aula" name="duracao_aula" value='<?php echo (!empty($obj['Duracao_aula']) ? $obj['Duracao_aula']:''); ?>' type="text" class="input-material">
					<label for="duracao_aula" class="label-material">Duração da aula</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-duracao_aula'></div>
				</div>

				<div class="form-group relative">
					<input id="hora_inicio_aula" name="hora_inicio_aula" value='<?php echo (!empty($obj['Hora_inicio_aula']) ? $obj['Hora_inicio_aula']:''); ?>' type="time" class="input-material">
					<label for="hora_inicio_aula" class="label-material">Hora de início da aula</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-hora_inicio_aula'></div>
				</div>

				<div class="form-group relative">
					<input id="quantidade_aula" name="quantidade_aula" value='<?php echo (!empty($obj['Quantidade_aula']) ? $obj['Quantidade_aula']:''); ?>' type="text" class="input-material">
					<label for="quantidade_aula" class="label-material">Quantidade de aulas</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-quantidade_aula'></div>
				</div>

				<div class="form-group relative">
					<input id="reprovas" name="reprovas" value='<?php echo (!empty($obj['Reprovas']) ? $obj['Reprovas']:''); ?>' type="text" class="input-material">
					<label for="reprovas" class="label-material">Reprovas <span class='glyphicon glyphicon-question-sign text-danger pointer'  data-toggle="tooltip" title="Define o limite de disciplinas que o aluno poderá carregar."></span></label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-reprovas'></div>
				</div>
			</div>
			<div class="col-lg-6">
				<fieldset>
					<legend>Intervalos <span class='glyphicon glyphicon-question-sign text-danger pointer'  data-toggle="tooltip" title="Define os intervalos que terão em cada dia da semana para o horário de aula da turma."></span></legend>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group relative">
								<input id="hora_inicio" name="hora_inicio" type="time" class="input-material">
								<label for="hora_inicio" class="label-material active">Início</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-hora_inicio'></div>
							</div>						
						</div>
						<div class="col-lg-6">
							<div class="form-group relative">
								<input id="hora_fim" name="hora_fim" type="time" class="input-material">
								<label for="hora_fim" class="label-material active">Fim</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-hora_fim'></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class='form-group'>
								<select id="dia" name="dia" class='form-control padding0'>
									<option class='background_dark' value="0">Selecione o dia</option>
									<option class='background_dark' value="1">Segunda</option>
									<option class='background_dark' value="2">Terça</option>
									<option class='background_dark' value="3">Quarta</option>
									<option class='background_dark' value="4">Quinta</option>
									<option class='background_dark' value="5">Sexta</option>
									<option class='background_dark' value="6">Sábado</option>
									<option class='background_dark' value="7">Domingo</option>
								</select>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-dia'></div>
							</div>
						</div>
						<div class="col-lg-6">
							<input type='button' class='btn btn-danger btn-block' value='Adicionar intervalo'>
						</div>
					</div>

					<div class="intervalos">
					</div>
				</fieldset>
				<fieldset>
					<legend>Bimestres <span class='glyphicon glyphicon-question-sign text-danger pointer'  data-toggle="tooltip" title="Define a quantidade de bimestres, data em que começa e inicia, valor."></span></legend>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group relative">
								<input id="nome_bimestre" name="nome_bimestre" type="text" class="input-material">
								<label for="nome_bimestre" class="label-material">Nome</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome_bimestre'></div>
							</div>						
						</div>
						<div class="col-lg-6">
							<div class="form-group relative">
								<input id="valor" name="valor" type="text" class="input-material">
								<label for="valor" class="label-material">Valor</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-valor'></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group relative" id="data1">
								<input id="data_inicio" name="data_inicio" type="text" class="input-material">
								<label for="data_inicio" class="label-material">Data de início</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-data_inicio'></div>
							</div>						
						</div>
						<div class="col-lg-6">
							<div class="form-group relative" id="data1">
								<input id="data_fim" name="data_fim" type="text" class="input-material">
								<label for="data_fim" class="label-material">Data de fim</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-data_fim'></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group relative" id="data1">
								<input id="data_abertura" name="data_abertura" type="text" class="input-material">
								<label for="data_abertura" class="label-material">Data de abertura</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-data_abertura'></div>
							</div>						
						</div>
						<div class="col-lg-6">
							<div class="form-group relative" id="data1">
								<input id="data_fechamento" name="data_fechamento" type="text" class="input-material">
								<label for="data_fechamento" class="label-material">Data de fechamento</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-data_fechamento'></div>
							</div>
						</div>
					</div>
					<div class="row" style="padding-bottom: 15px;">
						<div class="col-lg-6">
							<input type='button' class='btn btn-danger btn-block' value='Adicionar bimestre'>			
						</div>
					</div>
					<div class="bimestres">
					</div>
				</fieldset>
			</div>
		</div>

		
		<div class='form-group'>
			<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
				<?php
				$checked = "";
				if ($obj['Ativo'] == 1)
					$checked = "checked";

				echo "<label for='regra_ativa' style='color: #8a8d93;'>";
				echo "<input type='checkbox' $checked id='regra_ativa' name='regra_ativa' value='1' /><span></span> Regra ativa";
				echo "</label>";
				?>
			</div>
		</div>
		<?php
		if (empty($obj['Id']))
			echo "<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
		else
			echo "<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
		?>
		</form>
	</div>
</div>
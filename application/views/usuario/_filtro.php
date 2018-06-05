<div class="row" style="margin-right: 0px;">
	<div class="col-lg-4">
		<div class='form-group'>
				<?php

					echo"<select name='grupo_id' id='grupo_id' class='form-control padding0'>";
					echo"<option value='0' class='background_dark'>Tipo de usuário</option>";
				
					for($i = 0; $i < count($grupos); $i++)
					{
						$selected = "";
						if($grupos[$i]['Id'] == $outros['grupo_id'])
							$selected = "selected";

						echo"<option $selected class='background_dark' value='". $grupos[$i]['Id'] ."'>".$grupos[$i]['Nome_grupo']."</option>";
					}
					echo "</select>";
				?>
			<div class='input-group mb-2 mb-sm-0 text-danger' id='error-grupo_id'></div>
		</div>
	</div>
	<div class="col-lg-4" title="Data de início do período de registro">
		<div class="form-group relative" id="data1">
			<input id="data_registro_inicio" name="data_registro_inicio" value='<?php echo (!empty($outros['data_registro_inicio']) ? $outros['data_registro_inicio']:''); ?>' type="text" class="input-material">
			<label for="data_registro_inicio" class="label-material active">Data início</label>
		</div>
	</div>

	<div class="col-lg-4" title="Data de fim do período de registro">
		<div class="form-group relative" id="data1">
			<input id="data_registro_fim" name="data_registro_fim" value='<?php echo (!empty($outros['data_registro_fim']) ? $outros['data_registro_fim']:''); ?>' type="text" class="input-material">
			<label for="data_registro_fim" class="label-material active">Data fim</label>
		</div>
	</div>
</div>
<div class="row" style="margin-right: 0px;">
	<div class="col-lg-4">
		<div class="form-group relative">
			<input id="nome" name="nome" value='<?php echo (!empty($outros['nome']) ? $outros['nome']:''); ?>' type="text" class="input-material">
			<label for="nome" class="label-material active">Nome</label>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="form-group relative">
			<input id="email" spellcheck="false" name="email" value='<?php echo (!empty($outros['email']) ? $outros['email']:''); ?>' type="text" class="input-material">
			<label for="email" class="label-material active">E-mail</label>
			<div class='input-group mb-2 mb-sm-0 text-danger' id='error-email'></div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class='form-group'>
				<?php
					echo"<select name='ativo' id='ativo' class='form-control padding0'>";
					echo"<option value='0' class='background_dark'>Ativo</option>";
					if($outros['ativo'] == 1)
						echo"<option value='1' selected class='background_dark'>Sim</option>";
					else
						echo"<option value='1' class='background_dark'>Sim</option>";
					if($outros['ativo'] == 2)
						echo"<option value='2' selected class='background_dark'>Não</option>";
					else
						echo"<option value='2' class='background_dark'>Não</option>";
					echo "</select>";
				?>
		</div>
	</div>
</div>
<div class="row" style="margin-right: 0px;">
	<div class="col-lg-4" title="Data de nascimento início">
		<div class="form-group relative" id="data1">
			<input id="data_nascimento_inicio" name="data_nascimento_inicio" value='<?php echo (!empty($outros['data_nascimento_inicio']) ? $outros['data_nascimento_inicio']:''); ?>' type="text" class="input-material">
			<label for="data_nascimento_inicio" class="label-material active">Data início</label>
		</div>
	</div>
	<div class="col-lg-4" title="Data de nascimento fim">
		<div class="form-group relative" id="data1">
			<input id="data_nascimento_fim" name="data_nascimento_fim" value='<?php echo (!empty($outros['data_nascimento_fim']) ? $outros['data_nascimento_fim']:''); ?>' type="text" class="input-material">
			<label for="data_nascimento_fim" class="label-material active">Data fim</label>
		</div>
	</div>
	<div class="col-lg-4 text-right" style="padding-right: 7px">
		<button type="submit" class='btn btn-success btn-block'>
			<span class='glyphicon glyphicon-search'></span> Pesquisar
		</button>
	</div>
</div>
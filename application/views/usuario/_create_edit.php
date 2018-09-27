<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['Id'])) echo $obj['Id']; ?>'/>
<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
	<div class="col-lg-6">
		<div class="form-group relative">
			<input maxlength="100" id="nome" name="nome" value='<?php echo (!empty($obj['Nome_usuario']) ? $obj['Nome_usuario']:''); ?>' type="text" class="input-material">
			<label for="nome" class="label-material">Nome</label>
			<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
		</div>
	</div>
</div><!--FECHA A ROW QUE ABRE O CREATE DE USUARIO OU DE ALUNO-->
<div class="row">
	<div class="col-lg-6">
		<div class="form-group relative">
			<input maxlength="100" id="email" spellcheck="false" name="email" value='<?php echo (!empty($obj['Email']) ? $obj['Email']:''); ?>' type="text" class="input-material">
			<label for="email" class="label-material">E-mail</label>
			<div class='input-group mb-2 mb-sm-0 text-danger' id='error-email'></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="form-group relative" id="data1">
			<input id="data_nascimento" name="data_nascimento" value='<?php echo (!empty($obj['Data_nascimento']) ? $obj['Data_nascimento']:''); ?>' type="text" class="input-material">
			<label for="data_nascimento" class="label-material">Data de nascimento</label>
			<div class='input-group mb-2 mb-sm-0 text-danger' id='error-data_nascimento'></div>
		</div>
	</div>
</div>
<div class='form-group'>
	<fieldset>
		<legend>&nbsp;Sexo</legend>
				<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked'>
					<label for="masculino">
						<input name='sexo' id='masculino' value='1' <?php if(!empty($obj['Sexo'])) 
							if($obj['Sexo'] == 1)
								echo "checked";
						 ?> type='radio'/> <span></span>Masculino
					</label>
				</div>
		
	
				<div class='form-check form-check-inline checkbox checbox-switch switch-success custom-controls-stacked'>
					<label for="feminino">
						<input name='sexo' id='feminino' value='0' <?php if(!empty($obj['Sexo']) ||(isset($obj['Sexo']) && $obj['Sexo'] == 0)) 
							if($obj['Sexo'] == 0)
								echo "checked";
						 ?> type='radio'/> <span></span>Feminino
					</label>
				</div>
		<div class='input-group mb-2 mb-sm-0 text-danger' id='error-sexo'></div>
	</fieldset>
</div>
<div class="row">
	<?php 
		if(empty($obj['Id']))
		{
			echo "<div class='col-lg-4'>";
				echo "<div class='form-group relative'>";
					echo "<input id='senha' name='senha' value='".(!empty($obj['Senha']) ? $obj['Senha']:'')."' type='password' class='input-material'>";
					echo "<label for='senha' id='label_senha' class='label-material'>Senha</label>";
					echo "<div class='input-group mb-2 mb-sm-0 text-danger' id='error-senha'></div>";
				echo "</div>";
			echo "</div>";
			echo "<div class='col-lg-4'>";
				echo"<div class='form-group relative'>";
					echo"<input id='confirmar_senha' name='confirmar_senha' value='".(!empty($obj['Senha']) ? $obj['Senha']:'')."' type='password' class='input-material'>";
					echo"<label for='confirmar_senha' id='label_confirmar_senha' class='label-material'>Confirmar senha</label>";
					echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-confirmar_senha'></div>";
				echo"</div>";
			echo"</div>";
		}
		else
		{
			echo "<div class='col-lg-8'>";
				echo"<fieldset>";
				echo"<legend class='text-white'>&nbsp;Alterar senha</legend>";
					echo "<div class='row'>";
						echo "<div class='col-lg-6'>";		
							echo"<div class='form-group relative'>";
								echo"<input id='nova_senha' name='nova_senha' value='' type='password' class='input-material'>";
								echo"<label for='nova_senha' class='label-material'>Nova senha</label>";
								echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nova_senha'></div>";
							echo"</div>";
						echo"</div>";
						echo "<div class='col-lg-6'>";
							echo"<div class='form-group' style='position: relative;''>";
								echo"<input id='confirmar_nova_senha' name='confirmar_nova_senha' value='' type='password' class='input-material'>";
								echo"<label for='confirmar_nova_senha' class='label-material'>Confirmar senha</label>";
								echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-confirmar_nova_senha'></div>";
							echo"</div>";
						echo"</div>";
					echo"</div>";
				echo"</fieldset>";
			echo "</div>";
		}
	?>
	
	<div class="col-lg-4">
		<?php 
			if(!empty($obj['Id']))
				echo "<br /><br />";
		?>
		<button type="button" class="btn btn-info btn-block" onclick="Main.gerador_senha()">Gerar senha</button>
	</div>
</div>
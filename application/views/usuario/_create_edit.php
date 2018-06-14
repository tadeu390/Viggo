<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['Id'])) echo $obj['Id']; ?>'/>
<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>



<div class="form-group relative">
	<input id="nome" name="nome" value='<?php echo (!empty($obj['Nome_usuario']) ? $obj['Nome_usuario']:''); ?>' type="text" class="input-material">
	<label for="nome" class="label-material">Nome</label>
	<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
</div>

<div class="form-group relative">
	<input id="email" spellcheck="false" name="email" value='<?php echo (!empty($obj['Email']) ? $obj['Email']:''); ?>' type="text" class="input-material">
	<label for="email" class="label-material">E-mail</label>
	<div class='input-group mb-2 mb-sm-0 text-danger' id='error-email'></div>
</div>
<div class="form-group relative" id="data1">
	<input id="data_nascimento" name="data_nascimento" value='<?php echo (!empty($obj['Data_nascimento']) ? $obj['Data_nascimento']:''); ?>' type="text" class="input-material">
	<label for="data_nascimento" class="label-material">Data de nascimento</label>
	<div class='input-group mb-2 mb-sm-0 text-danger' id='error-data_nascimento'></div>
</div>
<div class='form-group'>
	<div class="card" style="border: 1px solid #8a8d93; background-color: transparent;">
	  <h4 class="card-header">Sexo</h4>
	  <div class="card-body" style="border-top: 1px solid #8a8d93;">
		<ul class="list-group">
			<li class='list-group-item' style=' background-color: transparent;'>
				<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
					<label for="masculino">
						<input name='sexo' id='masculino' value='1' <?php if(!empty($obj['Sexo'])) 
							if($obj['Sexo'] == 1)
								echo "checked";
						 ?> type='radio'/> <span></span>Masculino
					</label>
				</div>
			</li>
			<li class='list-group-item' style=' background-color: transparent;'>
				<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
					<label for="feminino">
						<input name='sexo' id='feminino' value='0' <?php if(!empty($obj['Sexo']) ||(isset($obj['Sexo']) && $obj['Sexo'] == 0)) 
							if($obj['Sexo'] == 0)
								echo "checked";
						 ?> type='radio'/> <span></span>Feminino
					</label>
				</div>
			</li>
		</ul>
	  </div>
	</div>
	<div class='input-group mb-2 mb-sm-0 text-danger' id='error-sexo'></div>
</div>
<?php 
	if(empty($obj['Id']))
	{
		echo "<div class='form-group relative'>";
			echo "<input id='senha' name='senha' value='".(!empty($obj['Senha']) ? $obj['Senha']:'')."' type='password' class='input-material'>";
			echo "<label for='senha' class='label-material'>Senha</label>";
			echo "<div class='input-group mb-2 mb-sm-0 text-danger' id='error-senha'></div>";
		echo "</div>";

		echo"<div class='form-group relative'>";
			echo"<input id='confirmar_senha' name='confirmar_senha' value='".(!empty($obj['Senha']) ? $obj['Senha']:'')."' type='password' class='input-material'>";
			echo"<label for='confirmar_senha' class='label-material'>Confirmar senha</label>";
			echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-confirmar_senha'></div>";
		echo"</div>";
	}
?>
<?php 
	if(!empty($obj['Id']))
	{
		echo"<fieldset>";
			echo"<legend class='text-white'>Alterar senha</legend>";
			
			echo"<div class='form-group relative'>";
				echo"<input id='nova_senha' name='nova_senha' value='' type='password' class='input-material'>";
				echo"<label for='nova_senha' class='label-material'>Nova senha</label>";
				echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nova_senha'></div>";
			echo"</div>";

			echo"<div class='form-group' style='position: relative;''>";
				echo"<input id='confirmar_nova_senha' name='confirmar_nova_senha' value='' type='password' class='input-material'>";
				echo"<label for='confirmar_nova_senha' class='label-material'>Confirmar senha</label>";
				echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-confirmar_nova_senha'></div>";
			echo"</div>";

		echo"</fieldset>";
	}
?>
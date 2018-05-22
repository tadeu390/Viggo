<br /><br />
<div class='row padding20'>
	    <?php
    	echo"<div class='col-lg-8 offset-lg-2 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."usuario'>Usuários</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar usuário' : 'Novo usuário')."</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<div class='col-lg-8 offset-lg-2 padding background_dark'>
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

			<div class="form-group relative">
				<input id="senha" name="senha" value='<?php echo (!empty($obj['Senha']) ? $obj['Senha']:''); ?>' type="password" class="input-material">
				<label for="senha" class="label-material">Senha</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-senha'></div>
			</div>
			<?php 
				if(empty($obj['Id']))
				{
					echo"<div class='form-group relative'>";
						echo"<input id='confirmar_senha' name='confirmar_senha' value='".(!empty($obj['Senha']) ? $obj['Senha']:'')."' type='password' class='input-material'>";
						echo"<label for='confirmar_senha' class='label-material'>Confirmar senha</label>";
						echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-confirmar_senha'></div>";
					echo"</div>";
				}
			?>
			<div class='form-group'>
					<div style="color: #8a8d93;">Tipo de usuário</div>
					<select name='grupo_id' id='grupo_id' class='form-control'>
						<option value='0' style='background-color: #393836;'>Selecione</option>
						<?php
							for($i = 0; $i < count($grupos_usuario); $i++)
							{
								$selected = "";
								if($grupos_usuario[$i]['Id'] == $obj['Grupo_id'])
									$selected = "selected";
								echo"<option style='background-color: #393836;' $selected value='". $grupos_usuario[$i]['Id'] ."'>".$grupos_usuario[$i]['Nome_grupo']."</option>";
							}
						?>
					</select>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-grupo_id'></div>
			</div>
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
			<div class='form-group'>
				<br />
				<div class="row">
					<div class="col-lg-2">
						<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
							<?php
								$checked = "";
								if($obj['Ativo'] == 1)
									$checked = "checked";
								
								echo"<label for='conta_ativa' class='text-white'>";
									echo "<input type='checkbox' $checked id='conta_ativa' name='conta_ativa' value='1' /><span></span> Conta ativa";
								echo"</label>";
							?>
						</div>
					</div>
					<div class="col-lg-4">
						<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
							<?php
								$checked = "";
								if($obj['Email_notifica_nova_conta'] == 1)
									$checked = "checked";
								
								echo"<label for='email_notifica_nova_conta' class='text-white'>";
									echo "<input type='checkbox' $checked id='email_notifica_nova_conta' name='email_notifica_nova_conta' value='1' /><span></span> Enviar e-mail de notificação?";
								echo"</label>";
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
				if(empty($obj['Id']))
					echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
				else
					echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
			?>
		</form>
	</div>
</div> 
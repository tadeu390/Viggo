
<div class='row padding30'>
	<div class='col-lg-8 offset-lg-2 padding' style="background: #393836;">
		<div>
			<a href='javascript:window.history.go(-1)' title='Voltar'>
				<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
			</a>
		</div>
		<div>
			<p class="text-center padding text-white"><?php echo((isset($obj['id'])) ? 'Editar usuário' : 'Novo usuário'); ?></p>					
		</div>
		<?php $atr = array("id" => "form_cadastro_$controller", "name" => "form_cadastro"); 
			echo form_open("$controller/store", $atr); 
		?>
			<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['id'])) echo $obj['id']; ?>'/>
			<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
			
			<div class="form-group relative">
				<input id="nome" name="nome" value='<?php echo (!empty($obj['nome_usuario']) ? $obj['nome_usuario']:''); ?>' type="text" class="input-material">
				<label for="nome" class="label-material">Nome</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
			</div>

			<div class="form-group relative">
				<input id="email" spellcheck="false" name="email" value='<?php echo (!empty($obj['email']) ? $obj['email']:''); ?>' type="text" class="input-material">
				<label for="email" class="label-material">E-mail</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-email'></div>
			</div>

			<div class="form-group relative">
				<input id="senha" name="senha" value='<?php echo (!empty($obj['senha']) ? $obj['senha']:''); ?>' type="password" class="input-material">
				<label for="senha" class="label-material">Senha</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-senha'></div>
			</div>
			<?php 
				if(empty($obj['id']))
				{
					echo"<div class='form-group relative'>";
						echo"<input id='confirmar_senha' name='confirmar_senha' value='".(!empty($obj['senha']) ? $obj['senha']:'')."' type='password' class='input-material'>";
						echo"<label for='confirmar_senha' class='label-material'>Confirmar senha</label>";
						echo"<div class='input-group mb-2 mb-sm-0 text-danger' id='error-confirmar_senha'></div>";
					echo"</div>";
				}
			?>
			<?php if(isset($this->load->session->id)): ?>
				<div class='form-group'>
						<div class='input-group-addon'>Tipo de usuário</span></div>
						<select name='grupo_id' id='grupo_id' class='form-control text-white'>
							<option value='0' style='background-color: #393836;'>Selecione</option>
							<?php
								for($i = 0; $i < count($grupos_usuario); $i++)
								{
									$selected = "";
									if($grupos_usuario[$i]['id'] == $obj['grupo_id'])
										$selected = "selected";
									echo"<option style='background-color: #393836;' $selected value='". $grupos_usuario[$i]['id'] ."'>".$grupos_usuario[$i]['nome_grupo']."</option>";
								}
							?>
						</select>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-grupo_id'></div>
				</div>
			<?php endif; ?>
			<?php 
				if(!empty($obj['id']))
				{
					echo"<fieldset>";
						echo"<legend class='text-white'>Alterar senha</legend>";
						
						echo"<div class='form-group' style='position: relative;''>";
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
			<?php if(isset($this->load->session->id)): ?>
			<div class='form-group'>
				<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
					<?php
						$checked = "";
						if($obj['ativo'] == 1)
							$checked = "checked";
						
						echo"<label for='conta'class='text-white'>";
							echo "<input type='checkbox' $checked id='conta' name='conta_ativa' value='1' /><span></span> Conta ativa";
						echo"</label>";
					?>
				</div>
			</div>
			<?php endif; ?>
			<?php
				if(empty($obj['id']))
					echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
				else
					echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
			?>
		</form>
	</div>
</div> 
<div class='row padding30'>
		<div class='col-lg-8 offset-lg-2 padding background_dark'>
			<div>
				<a href='javascript:window.history.go(-1)' title='Voltar'>
					<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
				</a>
			</div>
			<div>
				<p class="text-center padding text-white"><?php echo((isset($obj['Id'])) ? 'Editar grupo' : 'Novo grupo'); ?></p>					
			</div>
			<?php $atr = array("id" => "form_cadastro_$controller", "name" => "form_cadastro"); 
				echo form_open("$controller/store", $atr); 
			?>
			
				<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['Id'])) echo $obj['Id']; ?>'/>
				<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
				
				<div class="form-group relative">
					<input id="nome" name="nome" value='<?php echo (!empty($obj['Nome_grupo']) ? $obj['Nome_grupo']:''); ?>' type="text" class="input-material">
					<label for="nome" class="label-material">Nome</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
				</div>
				<div class='form-group'>
					<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
						<?php
							$checked = "";
							if($obj['Ativo'] == 1)
								$checked = "checked";
							
							echo"<label for='grupo_ativo' style='color: white;'>";
								echo "<input type='checkbox' $checked id='grupo_ativo' name='grupo_ativo' value='1' /><span></span> Grupo ativo";
							echo"</label>";
						?>
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
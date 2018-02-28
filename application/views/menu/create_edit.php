<div class='row padding30'>
		<div class='col-lg-8 offset-lg-2 padding' style="background: #393836;">
			<div>
				<a href='javascript:window.history.go(-1)' title='Voltar'>
					<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
				</a>
			</div>
			<div>
				<p class="text-center padding text-white"><?php echo((isset($obj['id'])) ? 'Editar menu' : 'Novo menu'); ?></p>					
			</div>
			<?php $atr = array("id" => "form_cadastro_$controller", "name" => "form_cadastro"); 
				echo form_open("$controller/store", $atr); 
			?>
				<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['id'])) echo $obj['id']; ?>'/>
				<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
				
				<div class="form-group relative">
					<input id="nome" name="nome" value='<?php echo (!empty($obj['nome']) ? $obj['nome']:''); ?>' type="text" class="input-material">
					<label for="nome" class="label-material">Nome</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
				</div>

				<div class='form-group'>
						<div class='input-group-addon'>Ordem</div>
						<input name='ordem' min="1" id='ordem' value='<?php echo (!empty($obj['ordem']) ? $obj['ordem']:''); ?>' type='number' class='form-control' />
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-ordem'></div>
				</div>
				<div class='form-group'>
					<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
						<?php
							$checked = "";
							if($obj['ativo'] == 1)
								$checked = "checked";
							
							echo"<label for='menu_ativo' class='text-white'>";
								echo "<input type='checkbox' $checked id='menu_ativo' name='menu_ativo' value='1' /><span></span> Menu ativo";
							echo"</label>";
						?>
					</div>
				</div>
				
				<?php
					if(empty($obj['id']))
						echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
					else
						echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
				?>
			</form>
	</div>
</div>

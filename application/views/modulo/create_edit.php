<div class='row padding30'>
		<div class='col-lg-8 offset-lg-2 padding' style="background: #393836;">
			<div>
				<a href='javascript:window.history.go(-1)' title='Voltar'>
					<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
				</a>
			</div>
			<div>
				<p class="text-center padding text-white"><?php echo((isset($obj['id'])) ? 'Editar módulo' : 'Novo módulo'); ?></p>					
			</div>
			<?php $atr = array("id" => "form_cadastro_$controller", "name" => "form_cadastro"); 
				echo form_open("$controller/store", $atr); 
			?>
			<input type='hidden' id='id' name='id' value='<?php echo (!empty($obj['id']) ? $obj['id'] :'' )?>'/>
			<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
			
			<div class="form-group relative">
				<input id="nome" name="nome" value='<?php echo (!empty($obj['nome_modulo']) ? $obj['nome_modulo']:''); ?>' type="text" class="input-material">
				<label for="nome" class="label-material">Nome</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
			</div>

			<div class="form-group relative">
				<input id="descricao" name="descricao" value='<?php echo (!empty($obj['descricao']) ? $obj['descricao']:''); ?>' type="text" class="input-material">
				<label for="descricao" class="label-material">Descrição</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-descricao'></div>
			</div>

			<div class="form-group relative">
				<input id="url_modulo" name="url_modulo" value='<?php echo (!empty($obj['url_modulo']) ? $obj['url_modulo']:''); ?>' type="text" class="input-material">
				<label for="url_modulo" class="label-material">URL</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-url_modulo'></div>
			</div>

			<div class="form-group relative">
				<input id="ordem" name="ordem" value='<?php echo (!empty($obj['ordem']) ? $obj['ordem']:''); ?>' type="number" class="input-material">
				<label for="ordem" class="label-material">Ordem</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-ordem'></div>
			</div>

			<div class="form-group relative">
				<input id="icone" name="icone" value='<?php echo (!empty($obj['icone']) ? $obj['icone']:''); ?>' type="text" class="input-material">
				<label for="icone" class="label-material">Ícone</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-icone'></div>
			</div>

			<div class='form-group'>
					<div class='input-group-addon'>Menu</span></div>
					<select name='menu_id' id='menu_id' class='form-control text-white' style='padding-left: 0px;'>
						<option value='0' style='background-color: #393836;'>Selecione</option>
						<?php
						for ($i = 0; $i < count($lista_menus); $i++)
						{
							$selected = "";
							if ($lista_menus[$i]['id'] == $obj['menu_id'])
								$selected = "selected";
							echo "<option style='background-color: #393836;' $selected value='" . $lista_menus[$i]['id'] . "'>" . $lista_menus[$i]['nome'] . "</option>";
						}
						?>
					</select>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-menu_id'></div>
			</div>
			<div class='form-group'>
				<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
					<?php
					$checked = "";
					if ($obj['ativo'] == 1)
						$checked = "checked";

					echo "<label for='modulo_ativo' class='text-white'>";
					echo "<input type='checkbox' $checked id='modulo_ativo' name='modulo_ativo' value='1' /><span></span> Módulo ativo";
					echo "</label>";
					?>
				</div>
			</div>
			<?php
			if (empty($obj['id']))
				echo "<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
			else
				echo "<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
			?>
		</form>
	</div>
</div>
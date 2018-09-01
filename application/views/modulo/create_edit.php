<br /><br />
<div class='row padding20 text-white'>
    <?php
    	echo"<div class='col-lg-8 offset-lg-2 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Módulos</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar módulo' : 'Novo módulo')."</li>";
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
		<input type='hidden' id='id' name='id' value='<?php echo (!empty($obj['Id']) ? $obj['Id'] :'' )?>'/>
		<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
		
		<div class="form-group relative">
			<input maxlength="20" id="nome" name="nome" value='<?php echo (!empty($obj['Nome_modulo']) ? $obj['Nome_modulo']:''); ?>' type="text" class="input-material">
			<label for="nome" class="label-material">Nome</label>
			<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
		</div>

		<div class="form-group relative">
			<input maxlength="50" id="descricao" name="descricao" value='<?php echo (!empty($obj['Descricao']) ? $obj['Descricao']:''); ?>' type="text" class="input-material">
			<label for="descricao" class="label-material">Descrição</label>
			<div class='input-group mb-2 mb-sm-0 text-danger' id='error-descricao'></div>
		</div>

		<div class="form-group relative">
			<input maxlength="100" id="url_modulo" name="url_modulo" spellcheck="false" value='<?php echo (!empty($obj['Url_modulo']) ? $obj['Url_modulo']:''); ?>' type="text" class="input-material">
			<label for="url_modulo" class="label-material">URL</label>
			<div class='input-group mb-2 mb-sm-0 text-danger' id='error-url_modulo'></div>
		</div>

		<div class="form-group relative">
			<input id="ordem" name="ordem" value='<?php echo (!empty($obj['Ordem']) ? $obj['Ordem']:''); ?>' type="number" class="input-material">
			<label for="ordem" class="label-material">Ordem</label>
			<div class='input-group mb-2 mb-sm-0 text-danger' id='error-ordem'></div>
		</div>

		<div class="form-group relative">
			<input maxlength="50" id="icone" name="icone" spellcheck="false" value='<?php echo (!empty($obj['Icone']) ? $obj['Icone']:''); ?>' type="text" class="input-material">
			<label for="icone" class="label-material">Ícone</label>
			<div class='input-group mb-2 mb-sm-0 text-danger' id='error-icone'></div>
		</div>

		<div class='form-group'>
				<div class='input-group-addon' style="color: #8a8d93;">Menu</span></div>
				<select name='menu_id' id='menu_id' class='form-control' style='padding-left: 0px;'>
					<option value='0' style='background-color: #393836;'>Selecione</option>
					<?php
					for ($i = 0; $i < count($lista_menus); $i++)
					{
						$selected = "";
						if ($lista_menus[$i]['Id'] == $obj['Menu_id'])
							$selected = "selected";
						echo "<option class='background_dark' $selected value='" . $lista_menus[$i]['Id'] . "'>" . $lista_menus[$i]['Nome'] . "</option>";
					}
					?>
				</select>
			<div class='input-group mb-2 mb-sm-0 text-danger' id='error-menu_id'></div>
		</div>
		<div class='form-group'>
			<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
				<?php
				$checked = "";
				if ($obj['Ativo'] == 1)
					$checked = "checked";

				echo "<label for='modulo_ativo' style='color: #8a8d93;'>";
				echo "<input type='checkbox' $checked id='modulo_ativo' name='modulo_ativo' value='1' /><span></span> Módulo ativo";
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
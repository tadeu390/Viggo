<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-8 offset-lg-2 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."disciplina'>Disciplinas</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar disciplina' : 'Nova disciplina')."</li>";
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
	<div class='table-responsive'>
		<?php
			$atr = array('id' => 'form_cadastro_disciplina','name' => 'form_cadastro');
			echo form_open("$controller/store",$atr);
		?>
		<br />
			<input type='hidden' id='id' name='id' value='<?= set_value($obj['Id']) ? : (isset($obj['Id']) ? $obj['Id'] : '') ?>'/>
			<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
			<div class='form-group relative'>
					<input maxlength="100" type='text'  class="input-material" name='nome' id='nome' value='<?php echo (!empty($obj['Nome_disciplina']) ? $obj['Nome_disciplina']:''); ?>'>
					<label for="nome" class="label-material">Nome</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
			</div>
			<div class='form-group relative'>
					<input maxlength="40" type='text' class="input-material" name='apelido' id='apelido' value='<?php echo (!empty($obj['Apelido']) ? $obj['Apelido']:''); ?>'>
					<label for="apelido" class="label-material">Apelido</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-apelido'></div>
			</div>
			<div class='form-group'>
				<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
					<?php
						$checked = "";
						if($obj['Ativo'] == 1)
							$checked = "checked";
						
						echo"<label for='disciplina_ativa' >";
							echo "<input type='checkbox' $checked id='disciplina_ativa' name='disciplina_ativa' value='1' /><span></span> Disciplina ativa";
						echo"</label>";
					?>
				</div>
			</div>
			<?php
				if(!isset($obj['Id']))
					echo"<input type='submit' id='bt_cadastro_disciplina' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
				else
					echo"<input type='submit' id='bt_cadastro_disciplina' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
			?>
		</form>
	</div>
</div>

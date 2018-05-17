<br /><br />
<div class='row padding20'>
	<?php
    	echo"<div class='col-lg-8 offset-lg-2 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Configurações</li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Geral</li>";
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
		<div>
			<p class="text-center padding text-white">Configurações gerais</p>
		</div>
		<?php $atr = array("id" => "form_cadastro_geral_$controller", "name" => "form_cadastro"); 
			echo form_open("$controller/store", $atr); 
		?>
			<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['Id'])) echo $obj['Id']; ?>'/>
			<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>

			<div class='form-group'>
				<div class='input-group-addon'>Ítens por página</div>
				<input name='itens_por_pagina' min="1" id='itens_por_pagina' value='<?php echo (!empty($obj['Itens_por_pagina']) ? $obj['Itens_por_pagina']:''); ?>' type='number' class='form-control' />
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-itens_por_pagina'></div>
			</div>
			<br />
			<?php
				if(empty($obj['Id']))
					echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
				else
					echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
			?>
		</form>
	</div>
</div>
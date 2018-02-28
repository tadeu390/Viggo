<div class='row padding30'>
		<div class='col-lg-8 offset-lg-2 padding' style="background: #393836;">
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
				<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['id'])) echo $obj['id']; ?>'/>
				<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
				
				<div class='form-group'>
						<div class='input-group-addon'>ítens por página</div>
						<input name='itens_por_pagina' min="1" id='itens_por_pagina' value='<?php echo (!empty($obj['itens_por_pagina']) ? $obj['itens_por_pagina']:''); ?>' type='number' class='form-control' />
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-itens_por_pagina'></div>
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

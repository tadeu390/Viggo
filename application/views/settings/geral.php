<div class='row padding30'>
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
				<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['id'])) echo $obj['id']; ?>'/>
				<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>

				<div class='form-group'>
					<div class='input-group-addon'>Média</div>
					<input name='media' min="1" id='media' value='<?php echo (!empty($obj['media']) ? $obj['media']:''); ?>' type='number' class='form-control' />
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-media'></div>
				</div>
				
				<div class='form-group'>
					<div class='input-group-addon'>Ítens por página</div>
					<input name='itens_por_pagina' min="1" id='itens_por_pagina' value='<?php echo (!empty($obj['itens_por_pagina']) ? $obj['itens_por_pagina']:''); ?>' type='number' class='form-control' />
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-itens_por_pagina'></div>
				</div>

				<div class='form-group'>
					<div class='input-group-addon'>Limite de faltas</div>
					<input name='total_faltas' min="1" id='total_faltas' value='<?php echo (!empty($obj['total_faltas']) ? $obj['total_faltas']:''); ?>' type='number' class='form-control' />
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-total_faltas'></div>
				</div>

				<fieldset>
					<legend class="text-white">&nbsp;Bimestres</legend>
					<div class='form-group'>
						<div class='input-group-addon'>1° Bimestre</div>
						<input name='primeiro_bimestre' min="1" id='primeiro_bimestre' value='<?php echo (!empty($obj['primeiro_bimestre']) ? $obj['primeiro_bimestre']:''); ?>' type='number' class='form-control' />
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-primeiro_bimestre'></div>
					</div>

					<div class='form-group'>
						<div class='input-group-addon'>2° Bimestre</div>
						<input name='segundo_bimestre' min="1" id='segundo_bimestre' value='<?php echo (!empty($obj['segundo_bimestre']) ? $obj['segundo_bimestre']:''); ?>' type='number' class='form-control' />
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-segundo_bimestre'></div>
					</div>

					<div class='form-group'>
						<div class='input-group-addon'>3° Bimestre</div>
						<input name='terceiro_bimestre' min="1" id='terceiro_bimestre' value='<?php echo (!empty($obj['terceiro_bimestre']) ? $obj['terceiro_bimestre']:''); ?>' type='number' class='form-control' />
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-terceiro_bimestre'></div>
					</div>

					<div class='form-group'>
						<div class='input-group-addon'>4° Bimestre</div>
						<input name='quarto_bimestre' min="1" id='quarto_bimestre' value='<?php echo (!empty($obj['quarto_bimestre']) ? $obj['quarto_bimestre']:''); ?>' type='number' class='form-control' />
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-quarto_bimestre'></div>
					</div>
				</fieldset>
				<br />
				<?php
					if(empty($obj['id']))
						echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
					else
						echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
				?>
			</form>
	</div>
</div>

<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-8 offset-lg-2 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Configurações</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<div class='col-lg-8 offset-lg-2 padding background_dark '>
		<div>
			<a href='javascript:window.history.go(-1)' title='Voltar'>
				<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
			</a>
		</div>
		<br /><br />
		
		<div class="row padding10">
			<div class="nav flex-column nav-pills" id="v-pills-tab" style="border-right: 1px solid white" role="tablist" aria-orientation="vertical">
				<a class="nav-link active text-white" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
					<span class="glyphicon glyphicon-cog"></span> Geral
				</a>
				<a class="nav-link text-white" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
					<span class="glyphicon glyphicon-envelope"></span> Contas de e-mail
				</a>
				<?php 
					echo"<a class='nav-link text-white' href='".$url."usuario/edit'>";
						echo"<span class='glyphicon glyphicon-user'></span>&nbsp; Meus dados";
					echo"</a>";
				?>
			</div>
			<div class="tab-content col-lg-8" style="padding-left: 20px" id="v-pills-tabContent">
				<div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
					<?php $atr = array("id" => "form_cadastro_".$controller."_geral", "name" => "form_cadastro_".$controller."_geral"); 
						echo form_open("$controller/store", $atr); 
					?>
						<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['Id'])) echo $obj['Id']; ?>'/>
						<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>

						<div class='form-group'>
							<div class='input-group-addon'>Ítens por página</div>
							<input name='itens_por_pagina' min="1" id='itens_por_pagina' value='<?php echo (!empty($obj['Itens_por_pagina']) ? $obj['Itens_por_pagina']:''); ?>' type='number' class='input-material' />
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-itens_por_pagina'></div>
						</div>
						<br />
						<?php
							echo"<input type='submit' class='btn btn-danger' value='Atualizar'>";
						?>
					</form>
				</div>
				<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
					<?php $atr = array("id" => "form_cadastro_".$controller."_email", "name" => "form_cadastro_".$controller."_email"); 
						echo form_open("$controller/store_email", $atr);
					?>
						<input type='hidden' id='id' name='id' value='<?php if(!empty($obj_email['Id'])) echo $obj_email['Id']; ?>'/>
						<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>

						<div class='form-group'>
							<div class='input-group-addon'>E-mail</div>
							<input spellcheck="false" name='email' id='email' value='<?php echo (!empty($obj_email['Email']) ? $obj_email['Email']:''); ?>' type='text' class='input-material' />
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-email'></div>
						</div>
						<div class='form-group'>
							<div class='input-group-addon'>Descrição</div>
							<input name='descricao' id='descricao' value='<?php echo (!empty($obj_email['Descricao']) ? $obj_email['Descricao']:''); ?>' type='text' class='input-material' />
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-descricao'></div>
						</div>
						<div class='form-group'>
							<div class='input-group-addon'>Usuario</div>
							<input name='usuario' id='usuario' spellcheck='false' value='<?php echo (!empty($obj_email['Usuario']) ? $obj_email['Usuario']:''); ?>' type='text' class='input-material' />
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-usuario'></div>
						</div>
						<div class='form-group'>
							<div class='input-group-addon'>Senha</div>
							<input name='senha' id='senha' value='<?php echo (!empty($obj_email['Senha']) ? $obj_email['Senha']:''); ?>' type='password' class='input-material' />
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-senha'></div>
						</div>
						<div class='form-group'>
							<div class='input-group-addon'>Protocolo</div>
							<input name='protocolo' id='protocolo' value='<?php echo (!empty($obj_email['Protocolo']) ? $obj_email['Protocolo']:''); ?>' type='text' class='input-material' />
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-protocolo'></div>
						</div>
						<div class='form-group'>
							<div class='input-group-addon'>Host</div>
							<input name='host' spellcheck='false' id='host' value='<?php echo (!empty($obj_email['Host']) ? $obj_email['Host']:''); ?>' type='text' class='input-material' />
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-host'></div>
						</div>
						<div class='form-group'>
							<div class='input-group-addon'>Porta</div>
							<input name='porta' id='porta' value='<?php echo (!empty($obj_email['Porta']) ? $obj_email['Porta']:''); ?>' type='text' class='input-material' />
							<div class='input-group mb-2 mb-sm-0 text-danger' id='error-porta'></div>
						</div>
						<br />
						<?php
							echo"<input type='submit' class='btn btn-danger' value='Atualizar'>";
						?>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
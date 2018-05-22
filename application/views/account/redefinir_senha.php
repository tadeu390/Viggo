<div class="login-page">
	<div class="container d-flex align-items-center">
	<div class="form-holder has-shadow">
		<div class="row">
			<div class="col-lg-5 bg-white shadow-basic">
				<div class="form d-flex align-items-center">
					<div class="content" id="login">
					  <?php
							$atr = array('id' => 'form_redefinir_senha','name' => 'form_cadastro');
							echo form_open('account/valida_redefinir_senha',$atr);
							echo "<input type='hidden' id='controller' value='$controller'>";
						?> 
							<img class="mx-auto d-block img-senha" src="<?php echo $url;?>/content/imagens/logo.png">	<span class="text-info" style='font-size: 17px;'>Recuperar senha</span><br /><br />
							<div class="form-group">
								<input id="email" autocomplete="off" autofocus="true" spellcheck="false" name="email" type="text" class="input-material">
								<label for="email" class="label-material active">E-mail</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-email'></div>
							</div>
							<div class="text-left">
								<button type="submit" class="btn btn-success col-lg-5">Enviar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
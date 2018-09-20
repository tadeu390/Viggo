<div class="login-page">
	<div class="container d-flex align-items-center">
	<div class="form-holder has-shadow">
		<div class="row">
			<div class="col-lg-5 bg-white shadow-basic">
				<div class="form d-flex align-items-center">
					<div class="content" id="login">
					  <?php
							$atr = array('id' => 'form_login','name' => 'form_login');
							echo form_open('account/validar',$atr);
							echo"<input type='hidden' value='".$url_redirect."' id='url_redirect'>";
						?> 
							<img class="mx-auto d-block img-login" src="<?php echo $url;?>/content/imagens/logo.png">	
							<div class="form-group">
								<input id="email-login" autofocus="true" autocomplete="off" spellcheck="false" name="email-login" type="text" class="input-material">
								<label for="email-login" class="label-material active">E-mail</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-email-login'></div>
							</div>
							<div class="form-group">
								<input id="senha-login" name="senha-login" type="password" class="input-material">
								<label for="senha-login" class="label-material">Senha</label>
								<div class='text-right' style="margin-top: -26px; ">
								<span class="glyphicon glyphicon-eye-open" id="espiar" title="Espiar senha" style="color:#336CD2; cursor: pointer;"></span>
								</div>
								<div class='input-group mb-2 mb-sm-0 text-danger' style="margin-top: 12px;" id='error-senha-login'></div>
							</div>
							<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
								<label for='conectado' class="text-dark">
									<input type='checkbox' id='conectado' name='conectado' value='1'><span ></span>Manter conectado
								</label>
							</div><br />
							<div class="text-right">
								<?php
									echo "<a href='".$url."account/redefinir_senha' class='text-info'>Esqueceu sua senha?</a><br /><br />";
								?>
								
							</div>
							<div class="text-left">
								<button type="submit" class="btn btn-success col-lg-5">Login</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
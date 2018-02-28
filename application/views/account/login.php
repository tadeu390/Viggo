<div class="modal fade" id="login_modal_aguardar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      </div>
      <div class="modal-body text-center">
		Aguarde... validando seus dados.
      </div>
      <div class="modal-footer text-center" style='display: block;'>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="login_modal_erro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      </div>
      <div class="modal-body text-center">
		E-mail e/ou senha inválidos
      </div>
      <div class="modal-footer text-center" style='display: block;'>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<div class="login-page">
	<div class="container d-flex align-items-center">
	<div class="form-holder has-shadow">
		<div class="row">
			<div class="col-lg-6 bg-white shadow-basic">
				<div class="form d-flex align-items-center">
					<div class="content">
					  <?php
							$atr = array('id' => 'form_login','name' => 'form_login');
							echo form_open('account/validar',$atr);
						?>
							<div class="form-group">
								<input id="email-login" autocomplete="false" spellcheck="false" name="email-login" type="text" class="input-material">
								<label for="email-login" class="label-material">E-mail</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-email-login'></div>
							</div>
							<div class="form-group">
								<input id="senha-login" name="senha-login" type="password" class="input-material">
								<label for="senha-login" class="label-material">Senha</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-senha-login'></div>
							</div>
							<div class='checkbox checbox-switch switch-success custom-controls-stacked text-white'>
								<label for='conectado'>
									<input type='checkbox' id='conectado' name='conectado' value='1'><span></span>Manter conectado
								</label>
							</div><br />
							<button type="submit" class="btn btn-primary">Login</button>
						</form>
						<a href="#" class="forgot-pass">Esqueceu sua senha?</a><br><small>Não tem uma conta? </small><a href="<?php echo $url."Account/registrar"; ?>" class="signup">Crie aqui</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	<div class="copyrights text-center">
		<p> <?php echo date("Y");?>  - Developed by Tadeu R. Torres</p>
	</div>
</div>
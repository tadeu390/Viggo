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
		E-mail não cadastrado.
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
			<div class="col-lg-5 bg-white shadow-basic">
				<div class="form d-flex align-items-center">
					<div class="content" id="login">
					  <?php
							$atr = array('id' => 'form_redefinir_senha','name' => 'form_cadastro');
							echo form_open('account/valida_redefinir_senha',$atr);
							echo "<input type='hidden' id='controller' value='$controller'>";
							echo "<input type='hidden' id='method' value='valida_redefinir_senha'>";
						?> 
							<img class="mx-auto d-block img-senha" src="<?php echo $url;?>/content/imagens/logo.png">	<span class="text-info" style='font-size: 17px;'>Recuperar senha</span><br /><br />
							<div class="form-group">
								<input id="email" autocomplete="false" autofocus="true" spellcheck="false" name="email" type="text" class="input-material">
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
	<div class="copyrights text-center">
		<p> <?php echo date("Y");?>  - Developed By Tadeu R. Torres</p>
	</div>
</div>
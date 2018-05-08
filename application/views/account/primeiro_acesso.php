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
							$atr = array('id' => 'form_redefinir_senha_primeiro_acesso','name' => 'form_cadastro');
							echo form_open('Account/altera_senha_primeiro_acesso',$atr);
							echo "<input type='hidden' id='method' value='altera_senha_primeiro_acesso'>";
							echo "<input type='hidden' id='controller' value='$controller'>";
						?> 
							<img class="mx-auto d-block img-senha" src="<?php echo $url;?>/content/imagens/logo.png">	
							<span class="text-info text-justify" style='font-size: 17px;'>
								Seja bem vindo <b><?php echo $sessao_primeiro_acesso['nome_primeiro_acesso']; ?>.</b>
								Este é o seu primeiro acesso,
								foi enviado um código de ativação da sua conta para o e-mail: <b><?php echo $sessao_primeiro_acesso['email_primeiro_acesso']; ?></b>. Verifique sua caixa de entrada. 
								<?php echo "<a href='".$url."account/gera_codigo_ativacao/".$sessao_primeiro_acesso['id_primeiro_acesso']."/redirect'>Reenviar código</a>"; ?>
							</span><br /><br />
							<div class="form-group">
								<input id="codigo_ativacao" autofocus="true" maxlength="6" autocomplete="false" spellcheck="false" name="codigo_ativacao" type="text" class="input-material">
								<label for="codigo_ativacao" class="label-material active">Código</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-codigo_ativacao'></div>
							</div>
							<div class="form-group">
								<input id="nova_senha" autocomplete="false" spellcheck="false" name="nova_senha" type="password" class="input-material">
								<label for="nova_senha" class="label-material">Nova senha (mínimo 8 caracteres)</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nova_senha'></div>
							</div>
							<div class="form-group">
								<input id="confirmar_nova_senha" autocomplete="false" spellcheck="false" name="confirmar_nova_senha" type="password" class="input-material">
								<label for="confirmar_nova_senha" class="label-material">Confirme sua senha</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-confirmar_nova_senha'></div>
							</div>
							<div class="row text-center">
								<div class="col-lg-6"><button type="submit" class="btn btn-success col-lg-11">Alterar senha</button><br /><br/></div>
								<div class="col-lg-6"><a href="javascript:window.history.go(-1)" class="btn btn-danger col-lg-11">Voltar</a></div>
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
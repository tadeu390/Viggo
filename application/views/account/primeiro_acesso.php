<div class="login-page">
	<div class="container d-flex align-items-center">
	<div class="form-holder has-shadow">
		<div class="row">
			<div class="col-lg-5 bg-white shadow-basic">
				<div class="form d-flex align-items-center">
					<div class="content" id="login">
					  <?php
							$atr = array('id' => 'form_redefinir_senha_primeiro_acesso','name' => 'form_cadastro');
							echo form_open('account/altera_senha_primeiro_acesso',$atr);
							echo "<input type='hidden' id='controller' value='$controller'>";
						?> 
							<img class="mx-auto d-block img-senha" src="<?php echo $url;?>/content/imagens/logo.png">	
							<span class="text-info text-justify" style='font-size: 17px;'>
								Seja bem vindo <b><?php echo $sessao_primeiro_acesso['nome_troca_senha']; ?>.</b>
								Este é o seu primeiro acesso,
								foi enviado um código de ativação da sua conta para o e-mail: <b><?php echo $sessao_primeiro_acesso['email_troca_senha']; ?></b>. Verifique sua caixa de entrada. 
								<?php echo "<a href='".$url."account/gera_codigo_ativacao/".$sessao_primeiro_acesso['id_troca_senha']."/redirect'>Reenviar código</a>"; ?>
							</span><br /><br />
							<div class="form-group">
								<input id="codigo_ativacao" autofocus="true" maxlength="6" autocomplete="off" spellcheck="false" name="codigo_ativacao" type="text" class="input-material">
								<label for="codigo_ativacao" class="label-material active">Código</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-codigo_ativacao'></div>
							</div>
							<div class="form-group">
								<input id="nova_senha" name="nova_senha" type="password" class="input-material">
								<label for="nova_senha" class="label-material">Nova senha (mínimo 8 caracteres)</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nova_senha'></div>
							</div>
							<div class="form-group">
								<input id="confirmar_nova_senha" name="confirmar_nova_senha" type="password" class="input-material">
								<label for="confirmar_nova_senha" class="label-material">Confirme sua senha</label>
								<div class='input-group mb-2 mb-sm-0 text-danger' id='error-confirmar_nova_senha'></div>
							</div>
							<div class="row text-center">
								<div class="col-lg-6"><button type="submit" class="btn btn-success col-lg-11">Alterar senha</button><br /><br/></div>
								<div class="col-lg-6"><a href="<?php echo $url; ?>account/login" class="btn btn-danger col-lg-11">Voltar</a></div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
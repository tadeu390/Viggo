<div class="login-page">
	<div class="container d-flex align-items-center">
	<div class="form-holder has-shadow">
		<div class="row">
			<div class="col-lg-5 bg-white shadow-basic">
				<div class="form d-flex align-items-center">
					<div class="content" id="login">
					  <?php
							$atr = array('id' => 'form_alterar_senha','name' => 'form_cadastro');
							echo form_open('Account/alterar_senha',$atr);
							echo "<input type='hidden' id='controller' value='$controller'>";
						?> 
							<img class="mx-auto d-block img-senha" src="<?php echo $url;?>/content/imagens/logo.png">	
							<span class="text-info text-justify" style='font-size: 17px;'>
								Olá <b><?php echo $sessao_primeiro_acesso['nome_troca_senha']; ?>.</b>
								Insira abaixo a sua nova senha
							</span><br /><br />
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
								<div class="col-lg-6"><a href="javascript:window.history.go(-1)" class="btn btn-danger col-lg-11">Voltar</a></div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
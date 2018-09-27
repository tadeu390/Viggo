<br /><br />
<div class='row padding20 text-white'>
	    <?php
    	echo"<div class='col-lg-8 offset-lg-2 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Usuários</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar usuário' : 'Novo usuário')."</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<div class='col-lg-8 offset-lg-2 padding background_dark'>
		<div>
			<a href='javascript:window.history.go(-1)' title='Voltar'>
				<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
			</a>
		</div>
		<br /><br />
		<?php $atr = array("id" => "form_cadastro_$controller", "name" => "form_cadastro"); 
			echo form_open("$controller/store", $atr); 
			
		?>	
		<div class="row"><!--ABRE A ROW QUE FECHA O CREATE_EDIT DE USUARIO-->
			<div class="col-lg-6">
				<div class='form-group'>
						<div style="color: #8a8d93;">Tipo de usuário</div>
							<?php
								if(empty($obj['Id']))
									$method = "\"create\"";
								else
									$method = "\"edit\"";
								
								if(!empty($obj['Id']))
									$id = $obj['Id'];
								else
									$id = 0;

								echo"<select name='grupo_id' id='grupo_id' class='form-control padding0' onchange='Main.altera_tipo_cadastro_usuario(this.value,$id,$method)'>";
								echo"<option value='0' class='background_dark'>Selecione</option>";
							
								for($i = 0; $i < count($grupos_usuario); $i++)
								{
									$selected = "";
									if($grupos_usuario[$i]['Id'] == $type)
										$selected = "selected";
			
									echo"<option class='background_dark' $selected value='". $grupos_usuario[$i]['Id'] ."'>".$grupos_usuario[$i]['Nome_grupo']."</option>";
								}
								echo "</select>";
							?>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-grupo_id'></div>
				</div>
			</div>
		<?php
			$this->load->view("usuario/_create_edit",$obj);
		?>
		<br />
		<div class="row">
			<div class="col-lg-4">
				<div class='form-group'>
					<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
						<?php
							$checked = "";
							if($obj['Ativo'] == 1)
								$checked = "checked";
							
							echo"<label for='conta_ativa' class=''>";
								echo "<input type='checkbox' $checked id='conta_ativa' name='conta_ativa' value='1' /><span></span> Conta ativa";
							echo"</label>";
						?>
					</div>
				</div>
			</div>
			<div class="col-lg-8 ">
				<div class='form-group'>
					<?php
						if($obj['Email_notifica_nova_conta'] == 0)
						{
							echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
								echo"<label for='email_notifica_nova_conta' class=''>";
									echo "<input type='checkbox' id='email_notifica_nova_conta' name='email_notifica_nova_conta' value='1' /><span></span> Enviar e-mail de notificação";
								echo"</label>";
							echo "</div>";
						}
						else
							echo "<span class='glyphicon glyphicon-ok-sign'></span> O E-mail de notificação já foi enviado para este usuário.";
					?>
				</div>
			</div>
		</div>
		<?php
			if(empty($obj['Id']))
				echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
			else
				echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
		?>
		</form>
	</div>
</div> 
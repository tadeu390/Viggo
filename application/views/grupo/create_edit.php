<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-8 offset-lg-2 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url."grupo'>Grupos</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar grupo' : 'Novo grupo')."</li>";
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
			
			<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['Id'])) echo $obj['Id']; ?>'/>
			<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
			
			<div class="form-group relative">
				<input id="nome" name="nome" value='<?php echo (!empty($obj['Nome_grupo']) ? $obj['Nome_grupo']:''); ?>' type="text" class="input-material">
				<label for="nome" class="label-material">Nome</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
			</div>
			<div class="label-material ">
				<br />Permissões padrões <span class='glyphicon glyphicon-question-sign text-danger pointer'  data-toggle="tooltip" title="Abaixo é possível alterar as permissões padrões de cada grupo, isto é, as permissões que serão atribuídas a cada usuário que for cadastrado no sistema."></span> <br /><br />
			</div>
			<?php
				echo "<div class='table-responsive'>";
					echo "<table class='table table-striped table-hover text-white'>";
						echo"<thead>";
							echo "<tr>";
								echo "<td>Módulo</td>";
								echo "<td class='text-center'>Criar</td>";
								echo "<td class='text-center'>Ler</td>";
								echo "<td class='text-center'>Atualizar</td>";
								echo "<td class='text-center'>Remover</td>";
							echo "</tr>";
						echo"</thead>";
						echo"<tbody>";
							for($i = 0; $i < count($lista_acesso_padrao); $i++)
							{
								echo"<tr>";
									echo"<td>";
										echo $lista_acesso_padrao[$i]['Nome_modulo'];
									echo"</td>";
									echo"<td class='text-center'>";
										echo "<input type='hidden' name='modulo_id".$i."' value='".$lista_acesso_padrao[$i]['Modulo_id']."' />";
										echo "<input type='hidden' name='acesso_padrao_id".$i."' value='".$lista_acesso_padrao[$i]['Acesso_padrao_id']."' />";
										echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='criar$i'>";
												if($lista_acesso_padrao[$i]['Criar'] == 1)
													echo"<input checked type='checkbox' id='criar$i' name='linha".$i."col0' value='1'><span></span>";
												else
													echo"<input type='checkbox' id='criar$i' name='linha".$i."col0' value='1'><span></span>";
											echo"</label>";
										echo"</div>";
									echo"</td>";
									echo"<td class='text-center'>";
										echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='ler$i'>";
												if($lista_acesso_padrao[$i]['Ler'] == 1)
													echo"<input checked type='checkbox' id='ler$i' name='linha".$i."col1' value='1'><span></span>";
												else
													echo"<input type='checkbox' id='ler$i' name='linha".$i."col1' value='1'><span></span>";
											echo"</label>";
										echo"</div>";
									echo"</td>";
									echo"<td class='text-center'>";
										echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='atualizar$i'>";
												if($lista_acesso_padrao[$i]['Atualizar'] == 1)
													echo"<input checked type='checkbox' id='atualizar$i' name='linha".$i."col2' value='1'><span></span>";
												else
													echo"<input type='checkbox' id='atualizar$i' name='linha".$i."col2' value='1'><span></span>";
											echo"</label>";
										echo"</div>";
									echo"</td>";
									echo"<td class='text-center'>";
										echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='remover$i'>";
												if($lista_acesso_padrao[$i]['Remover'] == 1)
													echo"<input checked type='checkbox' id='remover$i' name='linha".$i."col3' value='1'><span></span>";
												else
													echo"<input type='checkbox' id='remover$i' name='linha".$i."col3' value='1'><span></span>";
											echo"</label>";
										echo"</div>";
									echo"</td>";
								echo"</tr>";
							}
						echo"</tbody>";
					echo "</table>";
				echo "</div>";
			?>
				<div class='form-group'>
					<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
						<?php
							$checked = "";
							if($obj['Ativo'] == 1)
								$checked = "checked";
							
							echo"<label for='grupo_ativo' >";
								echo "<input type='checkbox' $checked id='grupo_ativo' name='grupo_ativo' value='1' /><span></span> Grupo ativo";
							echo"</label>";
						?>
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
<div class='row padding30'>
		<div class='col-lg-8 offset-lg-2 padding' style="background: #393836;">
			<div>
				<a href='javascript:window.history.go(-1)' title='Voltar'>
					<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
				</a>
			</div>
			<div>
				<p class="text-center padding text-white"><?php echo((isset($obj['id'])) ? 'Editar grupo' : 'Novo grupo'); ?></p>					
			</div>
			<?php $atr = array("id" => "form_cadastro_$controller", "name" => "form_cadastro"); 
				echo form_open("$controller/store", $atr); 
			?>
			
				<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['id'])) echo $obj['id']; ?>'/>
				<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
				
				<div class="form-group relative">
					<input id="nome" name="nome" value='<?php echo (!empty($obj['nome_grupo']) ? $obj['nome_grupo']:''); ?>' type="text" class="input-material">
					<label for="nome" class="label-material">Nome</label>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
				</div>

				<div class='form-group'>
					<div class='input-group-addon'>Permissões</div>
				<?php
				echo "<div class='table-responsive'>";
					echo "<table class='table table-striped table-hover' style='color: white;'>";
						echo"<thead>";
							echo "<tr>";
								echo "<td>Módulo</td>";
								echo "<td class='text-center'>Criar</td>";
								echo "<td class='text-center'>Visualizar</td>";
								echo "<td class='text-center'>Atualizar</td>";
								echo "<td class='text-center'>Apagar</td>";
							echo "</tr>";
						echo"</thead>";
						echo"<tbody>";
							for($i = 0; $i < count($lista_grupos_acesso); $i++)
							{
								echo"<tr>";
									echo"<td>";
										echo $lista_grupos_acesso[$i]['nome_modulo'];
									echo"</td>";
									echo"<td class='text-center'>";
										echo "<input type='hidden' name='modulo_id".$i."' value='".$lista_grupos_acesso[$i]['modulo_id']."' />";
										echo "<input type='hidden' name='acesso_id".$i."' value='".$lista_grupos_acesso[$i]['acesso_id']."' />";
										echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='criar$i'>";
												if($lista_grupos_acesso[$i]['criar'] == 1)
													echo"<input checked type='checkbox' id='criar$i' name='linha".$i."col0' value='1'><span></span>";
												else
													echo"<input type='checkbox' id='criar$i' name='linha".$i."col0' value='1'><span></span>";
											echo"</label>";
										echo"</div>";
									echo"</td>";
									echo"<td class='text-center'>";
										echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='visualizar$i'>";
												if($lista_grupos_acesso[$i]['visualizar'] == 1)
													echo"<input checked type='checkbox' id='visualizar$i' name='linha".$i."col1' value='1'><span></span>";
												else
													echo"<input type='checkbox' id='visualizar$i' name='linha".$i."col1' value='1'><span></span>";
											echo"</label>";
										echo"</div>";
									echo"</td>";
									echo"<td class='text-center'>";
										echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='atualizar$i'>";
												if($lista_grupos_acesso[$i]['atualizar'] == 1)
													echo"<input checked type='checkbox' id='atualizar$i' name='linha".$i."col2' value='1'><span></span>";
												else
													echo"<input type='checkbox' id='atualizar$i' name='linha".$i."col2' value='1'><span></span>";
											echo"</label>";
										echo"</div>";
									echo"</td>";
									echo"<td class='text-center'>";
										echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
											echo"<label for='apagar$i'>";
												if($lista_grupos_acesso[$i]['apagar'] == 1)
													echo"<input checked type='checkbox' id='apagar$i' name='linha".$i."col3' value='1'><span></span>";
												else
													echo"<input type='checkbox' id='apagar$i' name='linha".$i."col3' value='1'><span></span>";
											echo"</label>";
										echo"</div>";
									echo"</td>";
								echo"</tr>";
							}
						echo"</tbody>";
					echo "</table>";
				echo "</div>";
				?>
				</div>
				<div class='form-group'>
					<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
						<?php
							$checked = "";
							if($obj['ativo'] == 1)
								$checked = "checked";
							
							echo"<label for='grupo_ativo' style='color: white;'>";
								echo "<input type='checkbox' $checked id='grupo_ativo' name='grupo_ativo' value='1' /><span></span> Grupo ativo";
							echo"</label>";
						?>
					</div>
				</div>
				<?php
					if(empty($obj['id']))
						echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Cadastrar'>";
					else
						echo"<input type='submit' class='btn btn-danger btn-block' style='width: 200px;' value='Atualizar'>";
				?>
			</form>
	</div>
</div>
<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-8 offset-lg-2 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Curso</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar curso' : 'Novo curso')."</li>";
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
		<?php $atr = array('id' => 'form_cadastro_curso','name' => 'form_cadastro'); echo form_open("$controller/store",$atr); ?>
			<br />
			<input type='hidden' id='id' name='id' value='<?php if(!empty($obj['Id'])) echo $obj['Id']; ?>'/>
			<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
			<div class='form-group relative'>
				<input maxlength="100" type='text'  class="input-material"  name='nome' id='nome' value='<?php if(!empty($obj['Nome_curso'])) echo $obj['Nome_curso']; ?>'>
				<label for="nome" class="label-material">Nome</label>
				<div class='input-group mb-2 mb-sm-0 text-danger' id='error-nome'></div>
			</div>
			<div class='form-group'>
				<div class="card text-dark bg-dark mb-3">  
				  <h3 class="card-header text-white background_dark" style="border: 1px solid #8a8d93;">Disciplinas</h3>
					<div class="card-body background_dark" style="border: 1px solid #8a8d93; border-radius: 0px; border-top: none;">
						<ul class="list-group">
						<?php
							for($i = 0; $i < count($Disciplinas); $i ++)
							{
								$checked = "";
								for($j = 0; $j < count($Disciplinas_curso); $j++)
									if($Disciplinas_curso[$j]['Id'] == $Disciplinas[$i]['Id'])
										$checked = "checked";
								
								echo"<li class='list-group-item background_dark text-white'>";
									echo"<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
										echo"<label for='".$Disciplinas[$i]['Id']."'>";
											echo "<input $checked  id='". $Disciplinas[$i]['Id'] ."' value='". $Disciplinas[$i]['Id'] ."' type='checkbox' name='disciplinas[]' /><span></span>".$Disciplinas[$i]["Nome_disciplina"];
										echo"</label>";
									echo"</div>";
								echo"</li>";
							}
						?>
						</ul>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-discip'></div>
					</div>
				</div>
			</div>
			<div class='form-group'>
				<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
					<?php
						$checked = "";
						if($obj['Ativo'] == 1)
							$checked = "checked";
						
						echo"<label for='curso_ativo' >";
							echo "<input type='checkbox' $checked id='curso_ativo' name='curso_ativo' value='1' /><span></span> Curso ativo";
						echo"</label>";
					?>
				</div>
			</div>
			<?php
				if(!isset($obj['Id']))
					echo"<input  type='submit' id='bt_cadastro_obj' class='btn btn-danger btn-block' style='width: 200px' value='Cadastrar'>";
				else
					echo"<input type='submit' id='bt_cadastro_obj' class='btn btn-danger btn-block' style='width: 200px;'  value='Atualizar'>";
			?>
		</form>
	</div>
</div>

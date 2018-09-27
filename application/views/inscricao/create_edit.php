<br /><br />
<?php $this->load->helper("mstring");?>
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Inscrição</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".((isset($obj['Id'])) ? 'Editar inscrição' : 'Nova inscrição')."</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<div class='col-lg-10 offset-lg-1 padding background_dark'>
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
			


		<div class="row">
			<div class="col-lg-6">
				<div class='form-group text-dark'>
					<div class='input-group-addon' style="color: #8a8d93;">Aluno</div>
					<select name='aluno_id' id='aluno_id' class='form-control chosen-select ' style='padding-left: 0px;'>
						<option value='0'>Selecione</option>
						<?php
						for ($i = 0; $i < count($lista_alunos); $i++)
						{
							$selected = "";
							if ($lista_alunos[$i]['Usuario_id'] == $obj['Usuario_id'] || $lista_alunos[$i]['Usuario_id'] == $this->input->cookie('inscricao_aluno'))
								$selected = "selected";
							echo "<option title='".$lista_alunos[$i]['Nome_ra']."' $selected value='" . $lista_alunos[$i]['Id'] . "'>";
								echo mstring::corta_string($lista_alunos[$i]['Nome_ra'], 45);
							echo "</option>";
						}
						?>
					</select>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-aluno_id'></div>
				</div>
			</div>
			<div class="col-lg-6">
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				<div class='form-group'>
					<div class='input-group-addon' style="color: #8a8d93;">Curso</div>
					<select name='curso_id' id='curso_id' class='form-control' style='padding-left: 0px;'>
						<option value='0' style='background-color: #393836;'>Selecione</option>
						<?php
						for ($i = 0; $i < count($lista_cursos); $i++)
						{
							$selected = "";
							if ($lista_cursos[$i]['Id'] == $obj['Curso_id'])
								$selected = "selected";
							echo "<option class='background_dark' $selected value='" . $lista_cursos[$i]['Id'] . "'>" . $lista_cursos[$i]['Nome_curso'] . "</option>";
						}
						?>
					</select>
					<div class='input-group mb-2 mb-sm-0 text-danger' id='error-curso_id'></div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group relative">
					<div class='form-group'>
						<div class='input-group-addon' style="color: #8a8d93;">Modalidade</div>
						<select name='modalidade_id' id='modalidade_id' class='form-control' style='padding-left: 0px;'>
							<option value='0' style='background-color: #393836;'>Selecione</option>
							<?php
							for ($i = 0; $i < count($lista_modalidades); $i++)
							{
								$selected = "";
								if ($lista_modalidades[$i]['Id'] == $obj['Modalidade_id'])
									$selected = "selected";
								echo "<option class='background_dark' $selected value='" . $lista_modalidades[$i]['Id'] . "'>" . $lista_modalidades[$i]['Nome_modalidade'] . "</option>";
							}
							?>
						</select>
						<div class='input-group mb-2 mb-sm-0 text-danger' id='error-modalidade_id'></div>
					</div>
				</div>
			</div>
		</div>
		<div class='form-group'>
			<div class='checkbox checbox-switch switch-success custom-controls-stacked'>
				<?php
				$checked = "";
				if (!empty($obj['Renovacao_matricula_id']))
					$checked = "checked";

				echo "<label for='matricular' style='color: #8a8d93;'>";
				echo "<input type='checkbox' $checked id='matricular' name='matricular' value='1' /><span></span> Confirmar matrícula";
				echo "</label>";
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
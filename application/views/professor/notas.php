<?php $this->load->helper("permissao");?>
<?php $this->load->helper("paginacao");?>
<?php $this->load->helper("mstring");?>
<br /><br />
<div class='row padding20 text-white relative' style="width: 98%; left: 2%">
	<?php
    	echo"<div class='col-lg-12 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Minhas disciplinas</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
	<input type='hidden' id='method' value='<?php echo $method; ?>'/>
	<div class='col-lg-12 padding background_dark'>
		<div class="row">
			<div class="col-lg-2 padding10" style="border-right: 1px solid white; border-bottom: 1px solid white">
				<?php
					$data['lista_disciplinas'] = $lista_disciplinas;
					$this->load->view("professor/_disciplina", $data);
				?>
			</div>
			<div class="col-lg-10" style="border-bottom: 1px solid white">
				<div class="row padding10">
					<?php
						$data['lista_bimestres'] = $lista_bimestres;
						$this->load->view("professor/_bimestre", $data);
					?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-2" style="border-right: 1px solid white">
				<div class="row padding10">
					<?php
						$data['lista_turmas'] = $lista_turmas;
						$data['url_part'] = $url_part;
						$this->load->view("professor/_turma", $data);
					?>
				</div>
			</div>
			<div class="col-lg-10">
				teste
			</div>
		</div>
	</div>
</div>
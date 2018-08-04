<?php $this->load->helper("permissao");?>
<?php $this->load->helper("paginacao");?>
<br /><br />
<div class='row padding20 text-white relative' style="width: 95%; left: 3.5%">
	<?php
    	echo"<div class='col-lg-12 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo"<li class='breadcrumb-item'><a href='".$url.$controller."'>Notas e faltas</a></li>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>".$disc_turma_header['Nome_turma']."</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
    ?>
	<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
	<div class='col-lg-12 padding background_dark'>
		<div>
			<a href='javascript:window.history.go(-1)' title='Voltar'>
				<span class='glyphicon glyphicon-arrow-left text-white' style='font-size: 25px;'></span>
			</a>
		</div>
		<br /><br />

		<div class="row">
			<div class="col-lg-3" style="border-right: 1px solid white; ">
				<div class="text-center">Disciplinas</div><br />
				<?php
					echo"<div class='list-group'>";
					for($i = 0; $i < COUNT($disciplinas); $i++)
					{
						$active = "";
						if($disc_turma == 0 && $i == 0)
							$active = 'active';
						if($disciplinas[$i]['Disc_turma_id'] == $disc_turma)
							$active = "active";

						echo" <a href='".$url.$controller."/turma/".$disc_turma_header['Id']."/".$disciplinas[$i]['Disc_turma_id']."' class='list-group-item list-group-item-action $active'>";
							echo $disciplinas[$i]['Nome_disciplina'];
						echo "</a>";	

					}
					echo"</div>";
					echo "<br />";
				?>
			</div>
			<div class="col-lg-9">
				t
			</div>
		</div>
	</div>
</div>
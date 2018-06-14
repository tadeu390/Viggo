<?php $this->load->helper("permissao");?>
<?php $this->load->helper("paginacao");?>
<?php $this->load->helper("mstring");?>
<br /><br />
<div class='row padding20 text-white'>
	<?php
    	echo"<div class='col-lg-10 offset-lg-1 padding0'>";
			echo"<nav aria-label='breadcrumb'>";
  				echo"<ol class='breadcrumb'>";
    				echo "<li class='breadcrumb-item active' aria-current='page'>Usuários</li>";
    			echo "</ol>";
			echo"</nav>";
		echo "</div>";
		 
		$atr = array("id" => "form_filtros", "name" => "form_filtros","class" => "col-lg-10 offset-lg-1 padding20 background_dark", "method" => "get"); 
		echo form_open("$controller/index", $atr);

		$this->load->view("usuario/_filtro", $filtros);
				
		echo "</form>";
    ?>
	<input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
	<?php
		echo "<div class='col-lg-10 offset-lg-1 padding background_dark' style='margin-top: 10px;'>";
			echo "<div class='table-responsive'>";
				echo "<table class='table table-striped table-hover text-white'>";
					echo "<thead>";
						echo"<tr>";
							echo"<td colspan='4' style='font-size: 12px;'>";
							if(!empty($usuarios[0]['Size']))
								echo "A busca retornou ".$usuarios[0]['Size']." registro(s)";
							else
								echo "A busca não obteve resultados.";
							echo"</td>";
						echo"</tr>";
						echo"<tr>";
							echo"<td class='text-right' colspan='4'>";
							if(permissao::get_permissao(CREATE, $controller))
								echo"<a class='btn btn-success' href='".$url."$controller/create/0/'><span class='glyphicon glyphicon-plus'></span> Novo usuário</a>";
							echo"</td>";
						echo"</tr>";
						echo "<tr>";
							echo "<td>#</td>";
							echo "<td>Nome</td>";
							echo "<td>Ativo</td>";
							//echo "<td>E-mail</td>";
							//echo "<td>Grupo</td>";
							echo "<td class='text-right'></td>";
						echo "<tr>";
					echo "</thead>";
					echo "<tbody>";
						for($i = 0; $i < count($usuarios); $i++)
						{
							$cor = "";
							if($usuarios[$i]['Ativo'] == 0)
								$cor = "style='background-color: #dc3545;'";
							echo "<tr>";
								echo "<td $cor>".($i + 1)."</td>";
								echo "<td $cor><span title='".$usuarios[$i]['Nome_usuario']."'>".
								mstring::corta_string($usuarios[$i]['Nome_usuario'], 25)
								."</span></td>";
								echo "<td $cor>".(($usuarios[$i]['Ativo'] == 1) ? 'Sim' : 'Não')."</td>";
								//echo "<td $cor>".$usuarios[$i]['email']."</td>";
								//echo "<td $cor>".$usuarios[$i]['nome_grupo']."</td>";
								echo "<td class='text-right'>";
									if(permissao::get_permissao(UPDATE, $controller))
										echo "<a href='".$url.$usuarios[$i]['Method']."/edit/".$usuarios[$i]['Id']."' title='Editar' style='cursor: pointer;' class='glyphicon glyphicon-edit text-danger'></a> | ";
									echo "<a href='".$url.$usuarios[$i]['Method']."/detalhes/".$usuarios[$i]['Id']."' title='Detalhes' style='cursor: pointer;' class='glyphicon glyphicon-th text-danger'></a> | ";
									if($this->session->grupo_id == 1 || $this->input->cookie('grupo_id') == 1)
										echo "<a href='".$url."$controller/permissoes/".$usuarios[$i]['Id']."' title='Permissões' style='color:cursor: 	pointer;' class='glyphicon glyphicon-ok-sign text-danger'></a> | ";
									if(permissao::get_permissao(DELETE, $controller))
										echo " <span onclick='Main.confirm_delete(". $usuarios[$i]['Id'] .");' id='sp_lead_trash' name='sp_lead_trash' title='Apagar' style='cursor: pointer;' class='glyphicon glyphicon-trash text-danger'></span>";
								echo "</td>";
							echo "</tr>";
						}
					echo "</tbody>";
				echo "</table>";
			echo "</div>";
			paginacao::get_paginacao($paginacao, $controller);
		echo "</div>";
	?>
</div>

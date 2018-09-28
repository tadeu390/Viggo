<html lang="pt-br">
	<head> 
		<?php echo"<link rel='shortcut icon' href='".$url."content/imagens/favicon.ico'>"; ?>
		<title><?php echo $title; ?></title>
		<meta charset="utf-8">
		<?= link_tag('content/css/bootstrap.css') ?>
		<?= link_tag('content/css/normalize.css') ?>
		<?= link_tag('content/css/font-awesome.css') ?>
		<?= link_tag('content/css/glyphicons.css') ?>
		<?= link_tag('content/css/site.css') ?>
		<?= link_tag('content/css/default.css') ?>
		<?= link_tag('content/css/bootstrap-datepicker.min.css') ?>
		<?= link_tag('content/css/bootstrap-datepicker3.min.css') ?>
		<?= link_tag('content/css/chosen.css') ?>
		<style>
			.form-control, .form-control:focus, .form-control:hover {
			border: none;
				border-radius: 0px;
				border-bottom: 1px solid #444951;
				background-color: rgba(255,255,255,0);
				outline: 0 none !important;
				color: #8a8d93;
			}
			.form-control:focus {
			  border-color: none;
			    box-shadow: none;
			    -webkit-box-shadow: none;
			    outline: -webkit-focus-ring-color auto 5px;
			}
			
			.card-center-x{
				margin: 0 auto; 
				float: none; 
				margin-bottom: 10px; 
			}
			.table tbody tr:hover 
			{
				background-color: #EDC127 !important;
			}
		</style>
		<script type="text/javascript">
			window.onload = function()
			{
				//normalmente é a id de todo registro que vai pra tela(id de usuario, id de grupo, etc)
		       	if($("#id").val() != 0 && $("#id").val() != '' && $("#id").val() != undefined)
		    		$(".input-material").siblings('.label-material').addClass('active');
			}
		</script>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	</head>
	<body>
		<?php $this->load->helper("mstring");?>
		<div class='container-fluid'>
			<nav class="side-navbar">
				<div class="sidenav-header d-flex align-items-center justify-content-center">
					<div class="sidenav-header-inner  text-center">
						<a class='logotipo' href="<?php echo $url; ?>academico/dashboard"><img class="img-fluid rounded-circle" src="<?php echo $url;?>/content/imagens/logo.png" title='CEP - Centro de Educação Profissional "Tancredo Neves"'></a>
						<h3 class='line-height' title='CEP - Centro de Educação Profissional "Tancredo Neves"'>ACADÊMICO</h3>

					</div>
					<div style="margin-top: 15px;" class="sidenav-header-logo"><a href="<?php echo $url; ?>academico/dashboard" class="brand-small text-center">
						<strong title='CEP - Centro de Educação Profissional "Tancredo Neves"'>CEP</strong></a>
					</div>
				</div>
				<div class="main-menu">
					<ul id="side-main-menu" class="side-menu list-unstyled">
					<?php
						for ($i = 0; $i < count($menu); $i++) {
							$status = "false";
							$classe = "collapse list-unstyled";
							//if($menu_selectd == $menu[$i]['Menu_id'])
							//{
								$status = "true";
								$classe = "collapse list-unstyled show";
							//}
							echo "<li>";
							echo "<a href='#pages-nav-list" . $i . "' data-toggle='collapse' aria-expanded='".$status."'>";
							echo "<i class='icon-interface-windows'></i>";
							echo "<span>" . $menu[$i]['Nome_menu'] . "</span>";
							echo "<div class='arrow pull-right'>";
							echo "<i class='fa fa-angle-down'></i>";
							echo "</div>";
							echo "</a>";
							echo "<ul id='pages-nav-list" . $i . "' class='".$classe."'>";
							for ($j = 0; $j < count($modulo); $j++)
								if ($menu[$i]['Menu_id'] == $modulo[$j]['Menu_id'])
									echo "<li><a href='" . $url . $modulo[$j]['Url_modulo'] . "'><i class='" . $modulo[$j]['Icone'] . "' style='margin-bottom: 10px;'></i>" . $modulo[$j]['Nome_modulo'] . "</a></li>";
							echo "</ul>";
							echo "</li>";
						}
						//ABAIXO EXIBE OS MÓDULOS QUE NÃO PERTECEM A NENHUM MENU
						for ($i = 0; $i < count($modulo); $i++)
							if (empty($modulo[$i]['Menu_id']))
								echo "<li><a href='" .$url. $modulo[$i]['Url_modulo'] . "'><i class='" . $modulo[$i]['Icone'] . "' style='margin-bottom: 10px;'></i><span>" . $modulo[$i]['Nome_modulo'] . "</span></a></li>";
					?>
					</ul>
				</div>
			</nav>
			
			<?php $this->load->view("templates/modal"); ?>
			
			<div class='page home-page'>
				<header class="header">
					<nav class="navbar">
						<div class="container-fluid">
							<div class="navbar-holder d-flex align-items-center justify-content-between">
								<div class="navbar-header">
									<a id="toggle-btn" href="#" class="menu-bt">
										<span class="glyphicon glyphicon-align-justify menu-color" style="transform: scale(2);" > </span>
									</a>
								</div>
								<ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
									<li class="nav-item" style="padding-right: 10px;">
										<?php
											if(($this->input->cookie('grupo_id') == ADMIN || $this->session->grupo_id == ADMIN) OR 
											   ($this->input->cookie('grupo_id') == SECRETARIA || $this->session->grupo_id == SECRETARIA))
											{
												$atr = array("id" => "form_filtros", "name" => "form_filtros", "method" => "get", "style" => "margin-bottom: 0em;"); 
												echo form_open("usuario/index", $atr);
													echo"<input id='nome_pesquisa_rapida' value='".((!empty($filtros['outros']['nome'])) ? $filtros['outros']['nome'] : '').((!empty($filtros['outros']['nome_pesquisa_rapida'])) ? $filtros['outros']['nome_pesquisa_rapida'] : '')."' placeholder='Pesquisar usuários' name='nome_pesquisa_rapida' type='text' class='form-control relative mr-2'  style='top:1px; background-color: white;'>";
												echo"</form>";
											}
										?>
									</li>
									<li class="nav-item">
										<div class="dropdown">
										  	<button class="btn btn-primary dropdown-toggle" title="<?php echo $usuario_logado; ?>" type="button" data-toggle="dropdown"><?php echo mstring::corta_string($usuario_logado, 10); ?>
										  	<span class="caret"></span></button>
										  	<ul class="dropdown-menu">
										    	<?php
										    	if(($this->input->cookie('grupo_id') == ADMIN || $this->session->grupo_id == ADMIN) OR 
												   ($this->input->cookie('grupo_id') == SECRETARIA || $this->session->grupo_id == SECRETARIA))
												{
											    	echo "<li>";
											    		echo"<a class='btn-block' href='".$url."usuario/edit'>";
											    			echo"<span class='glyphicon glyphicon-user'></span>&nbsp; Meus dados";
											    		echo"</a>";
											    	echo"</li>";
									    		}
									    		else
									    		{
									    			echo "<li>";
											    		echo"<a class='btn-block' href='".$url."account/meus_dados'>";
											    			echo"<span class='glyphicon glyphicon-user'></span>&nbsp; Meus dados";
											    		echo"</a>";
											    	echo"</li>";	
									    		}
										    	if($this->input->cookie('grupo_id') == ADMIN || $this->session->grupo_id == ADMIN)
										    	{
											    	echo "<li>";
											    		echo "<a class='btn-block' href='".$url."configuracoes/geral'>";
											    			echo "<span class='glyphicon glyphicon-cog'></span>&nbsp; Configurações";
											    		echo "</a>";
											    	echo "</li>";
											    }
											    if(($this->input->cookie('grupo_id') == PROFESSOR || $this->session->grupo_id == PROFESSOR) || ($this->input->cookie('grupo_id') == ALUNO || $this->session->grupo_id == ALUNO))
										    	{
											    	echo "<li>";
											    		echo "<a class='btn-block' href='".$url."academico/delete_periodo_letivo'>";
											    			echo "<span class='glyphicon glyphicon-tag'></span>&nbsp; Alterar período letivo";
											    		echo "</a>";
											    	echo "</li>";
											    }
										    	?>
										    	<li>
										    		<a class="btn-block" href="<?php echo $url; ?>account/logout" id="bt_logout">
										    			<span class="glyphicon glyphicon-log-out"></span>&nbsp; &nbsp; Sair
										    		</a>
										    	</li>
										  	</ul>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</nav>
				</header>
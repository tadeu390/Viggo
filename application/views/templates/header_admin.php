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
		<style>
			.form-control, .form-control:focus, .form-control:hover {
			border: none;
				border-radius: 0px;
				border-bottom: 1px solid #444951;
				background-color: rgba(255,255,255,0);
				outline: none;
				color: #8a8d93;
			}
			.form-control:focus{
				border-bottom: 1px solid #dc3545;
				outline: none;
			}
		</style>
		<script type="text/javascript">
			window.onload = function()
			{
		       	if($("#id").val() != 0 && $("#id").val() != '')
		    		$(".input-material").siblings('.label-material').addClass('active');
			}
		</script>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	</head >
	<body>
		<div class='container-fluid'>
			<nav class="side-navbar">
				<div class="sidenav-header d-flex align-items-center justify-content-center">
					<div class="sidenav-header-inner  text-center">
						<img class="img-fluid rounded-circle" src="<?php echo $url;?>/content/imagens/logo.png" title='CEP - Centro de Educação Profissional "Tancredo Neves"'>
						<h2>CEP - Admin</h2> <br />

					</div>
					<div style="margin-top: 15px;" class="sidenav-header-logo"><a href="#" class="brand-small text-center">
						<strong title='CEP - Centro de Educação Profissional "Tancredo Neves"'>CEP</strong></a>
					</div>
				</div>
				<div class="main-menu">
					<ul id="side-main-menu" class="side-menu list-unstyled">
					<?php
						for ($i = 0; $i < count($menu); $i++) {
							$status = "false";
							$classe = "collapse list-unstyled";
							if($menu_selectd == $menu[$i]['Menu_id'])
							{
								$status = "true";
								$classe = "collapse list-unstyled show";
							}
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

			<div class="modal fade" id="modal_aguardar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					
				  </div>
				  <div class="modal-body text-center" id='mensagem_aguardar'>
					
				  </div>
				  <div class="modal-footer">
					
				  </div>
				</div>
			  </div>
			</div>

			<div id="admin_confirm_modal" class="modal" tabindex="-1" role="dialog">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header text-center">
					<h5 class="modal-title">Atenção</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div id="menssagem_confirm" class="modal-body text-center">
					
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-primary" id="bt_delete">Sim</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
				  </div>
				</div>
			  </div>
			</div>


			<div id="modal_aviso" class="modal fade" tabindex="-1" role="dialog">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header text-center" style="background: rgb(241,193,0);">
					<h5 class="modal-title text-white"><span class="glyphicon glyphicon-warning-sign" style="color: white;"></span>&nbsp;&nbsp;Atenção</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div id="mensagem_aviso" class="modal-body text-center">
					
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" id="bt_close_modal_aviso" data-dismiss="modal">Fechar</button>
				  </div>
				</div>
			  </div>
			</div>

			<div class='page home-page'>
				<header class="header">
					<nav class="navbar">
						<div class="container-fluid">
							<div class="navbar-holder d-flex align-items-center justify-content-between">
								<div class="navbar-header"><a id="toggle-btn" href="#" class="menu-btn">
									<span class="glyphicon glyphicon-align-justify" style='line-height: 40px; transform: scale(2.5);'> </span></a>
								</div>
								<ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
									<li class="nav-item">
										<div class="text-white" style=" padding-right: 20px"><?php echo $usuario; ?></div>
									</li>
									<li class="nav-item">
										<?php
										echo "<button class='btn btn-outline-danger btn-block' onclick='Main.logout()'><span class='glyphicon glyphicon-log-out'></span> Sair</button>";
										  ?>
									</li>
								</ul>
							</div>
						</div>
					</nav>
				</header>
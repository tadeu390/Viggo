<html>
	<head> 
		
		<title><?php echo $title;?></title>
		<?php echo"<link rel='shortcut icon' href='".$url."content/imagens/favicon.ico'>"; ?>
		<?= link_tag('content/css/bootstrap.min.css') ?>
		<?= link_tag('content/css/normalize.css') ?>
		<?= link_tag('content/css/font-awesome.css') ?>
		<?= link_tag('content/css/glyphicons.css') ?>
		<?= link_tag('content/css/site.css') ?>
		<?= link_tag('content/css/default.css') ?>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<style type="text/css">
			
		</style>
	</head >
	<body id='c'>
		<div class="modal fade" id="admin_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					
				  </div>
				  <div class="modal-body text-center" id='mensagem'>
					
				  </div>
				  <div class="modal-footer">
					
				  </div>
				</div>
			  </div>
			</div>

			<div id="admin_warning_modal" class="modal fade" tabindex="-1" role="dialog">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header text-center" style="background: rgb(241,193,0);">
					<h5 class="modal-title text-white"><span class="glyphicon glyphicon-warning-sign" style="color: white;"></span>&nbsp;&nbsp;Atenção</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div id="mensagem_warning" class="modal-body text-center">
					
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
				  </div>
				</div>
			  </div>
			</div>

        

<html>
	<head> 
		
		<title><?php echo $title;?></title>
		<?= link_tag('content/css/bootstrap.min.css') ?>
		<?= link_tag('content/css/normalize.css') ?>
		<?= link_tag('content/css/font-awesome.css') ?>
		<?= link_tag('content/css/glyphicons.css') ?>
		<?= link_tag('content/css/site.css') ?>
		<?= link_tag('content/css/default.css') ?>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
		<div id="admin_warning_modal" class="modal" tabindex="-1" role="dialog">
		  	<div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header text-center">
					<h5 class="modal-title">Atenção</h5>
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

        

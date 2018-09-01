<html lang="pt-br">
	<head> 
		
		<title><?php echo $title;?></title>
		<meta charset="utf-8">
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
	</head>
	<body>
		<?php $this->load->view("templates/modal"); ?>
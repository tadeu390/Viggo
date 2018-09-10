<div id="modal_aviso" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header text-center" style="background: rgb(241,193,0);">
				<h5 class="modal-title text-white">
				<span class="glyphicon glyphicon-warning-sign" style="color: white;"></span>&nbsp;&nbsp;Atenção</h5>
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
<div class="modal fade" id="modal_aguardar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">

			</div>
			<div class="modal-body text-center" id='mensagem_aguardar'>
				Aguarde... validando seus dados.
			</div>
			<div class="modal-footer">

			</div>
		</div>
	</div>
</div>
<div id="modal_confirm" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header text-center" style="background: rgb(241,193,0);">
				<h5 class="modal-title text-white">
				<span class="glyphicon glyphicon-warning-sign" style="color: white;"></span>&nbsp;&nbsp;Atenção</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div id="mensagem_confirm" class="modal-body text-center">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" id="bt_delete">Sim</button>
				<button type="button" class="btn btn-secondary" id="bt_confirm_modal" data-dismiss="modal">Não</button>
			</div>
		</div>
	</div>
</div>

<div id="modal_large" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background: rgb(241,193,0); padding-bottom: 0px;">
				<h5 class="modal-title text-white w-100" id="header_large"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div id="mensagem_large" class="modal-body" style="overflow: auto; height: 100%;">
				Aguarde...
			</div>
		</div>
	</div>
</div>
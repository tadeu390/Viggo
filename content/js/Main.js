var Main = {
	base_url : "http://"+window.location.host+"/git/TCC/",
	load_mask : function(){
		$(document).ready(function(){
			$('[data-toggle="popover"]').popover(),
			$('#telefone').mask('(00) 0000-00009'),
			$('#cep').mask('00000-000'),
			$('#cpf').mask('000.000.000-00')
			$('#codigo_ativacao').mask('999999')
		});
	},
	login : function () {
		if(Main.login_isvalid() == true)
		{
			$('#modal_aguardar').modal({
				keyboard: false,
				backdrop : 'static'
			});
			$.ajax({
				url: Main.base_url+'account/validar',
				data: $("#form_login").serialize(),
				dataType:'json',
				cache: false,
				type: 'POST',
				success: function (msg) {
					if(msg.response == "primeiro_acesso")
						window.location.assign(Main.base_url+"account/primeiro_acesso");
					else if(msg.response == "valido")
						location.reload();
					else
					{
						setTimeout(function(){
							$('#modal_aguardar').modal('hide');
						},500);
						Main.limpa_login();
						$("#mensagem_aviso").html(msg.response);
						$('#modal_aviso').modal({
							keyboard: false,
							backdrop : 'static'
						})

						$('#modal_aviso').on('shown.bs.modal', function () {
						  $('#bt_close_modal_aviso').trigger('focus')
						})
					}
				}
			});
		}
	},
	troca_status: function(idd)
	{
		//settimeout para recuperar o efeito de transição do botão, somente por questões de estética
		setTimeout(function(){
			document.getElementById(idd).className = "checkbox checbox-switch switch-success";
		},500);	
		document.getElementById("flag"+idd).value = "success";
	},
	logout : function (){
		$("#mensagem").html("Aguarde... encerrando sessão");
		$('#modal_aguardar').modal({
			keyboard: false,
			backdrop : 'static'
		});
		$.ajax({
			type: "POST",
			dataType: "json",
			url: Main.base_url+"Account/logout",
			complete: function(data) {
				 location.reload();
			}
		});
	},
	login_isvalid : function (){
		if($("#email-login").val() == "")
			Main.show_error("email-login","Informe seu e-mail","");
		else if(Main.valida_email($("#email-login").val()) == false)
			Main.show_error("email-login","Formato de e-mail inválido","");
		else if($("#senha-login").val() == "")
			Main.show_error("senha-login","Insira sua senha","");
		else
			return true;
	},
	
	valida_email : function(email)
	{
		var nome = email.substring(0, email.indexOf("@"));
		var dominio = email.substring(email.indexOf("@")+ 1, email.length);

		if ((nome.length >= 1) &&
			(dominio.length >= 3) && 
			(nome.search("@")  == -1) && 
			(dominio.search("@") == -1) &&
			(nome.search(" ") == -1) && 
			(dominio.search(" ") == -1) &&
			(dominio.search(".") != -1) &&      
			(dominio.indexOf(".") >= 1)&& 
			(dominio.lastIndexOf(".") < dominio.length - 1)) 
			return true;
		else
			return false;
	},
	show_error : function(form, error, class_error)
	{
		if(class_error != "")
			document.getElementById(form).className = "input-material "+class_error;
		document.getElementById("error-"+form).innerHTML = error;
	},
	limpa_login : function ()
	{
		$("#senha-login").val("");
		$("#senha-login").focus();
	},
	create_edit : function (){
		$("#mensagem_aguardar").html("Aguarde... processando dados");
		$('#modal_aguardar').modal({
			keyboard: false,
			backdrop : 'static'
		})
		
		//QUANDO NÃO FOR DEFINIDO NENHUM MÉTODO NA VIEW, POR DEFAULT É CONSIDERADO O METÓDO STORE PARA RECEBER OS DADOS
		var method = "store";
		if(document.getElementById("method") != undefined)
			method = $("#method").val();

		$.ajax({
			url: Main.base_url+$("#controller").val()+'/'+method,
			data: $("#"+$("form[name=form_cadastro]").attr("id")).serialize(),
			dataType:'json',
			cache: false,
			type: 'POST',
			success: function (msg) {
				if(msg.response == "sucesso")
				{
					$("#mensagem_aguardar").html("Dados salvos com sucesso");
					window.location.assign(Main.base_url+$("#controller").val()+"/index");
				}
				else
				{
					setTimeout(function(){
						$("#modal_aguardar").modal('hide');
						
						$("#mensagem_aviso").html(msg.response);
						$('#modal_aviso').modal({
							keyboard: false,
							backdrop : 'static',
						});
						$('#modal_aviso').on('shown.bs.modal', function () {
						  $('#bt_close_modal_aviso').trigger('focus')
						})
					},500);
				}
			}
		});
	},
	settings_geral_validar : function(){
		Main.create_edit();
	},
	usuario_validar : function(){
		if($("#nome").val() == "")
			Main.show_error("nome", 'Informe o nome de usuário', 'is-invalid');
		else if($("#email").val() == "")
			Main.show_error("email", 'Informe o e-mail de usuário', 'is-invalid');
		else if(Main.valida_email($("#email").val()) == false)
			Main.show_error("email", 'Formato de e-mail inválido', 'is-invalid');
		else if($("#senha").val() == "")
			Main.show_error("senha", 'Informe a senha de usuário', 'is-invalid');
		else
		{
			var trava = 0;
			if($("#id").val() == "")//se estiver criando um usuário
			{
				if($("#confirmar_senha").val() == "")
				{
					trava = 1;
					Main.show_error("confirmar_senha", 'Repita a senha de usuário', 'is-invalid');
				}
				else if($("#senha").val() != $("#confirmar_senha").val())
				{
					trava = 1;
					Main.show_error("confirmar_senha", 'Senha especificada é diferente da anterior', 'is-invalid');
				}
			}
			if(trava == 0)
			{
				if($("#grupo_id").val() == "0")
					Main.show_error("grupo_id", 'Selecione um tipo de usuário', '');
				else if($("#nova_senha").val() != "")
				{
					if($("#confirmar_nova_senha").val() == "")
						Main.show_error("confirmar_nova_senha", 'Repita a nova senha', 'is-invalid');
					else if($("#nova_senha").val() != $("#confirmar_nova_senha").val())
						Main.show_error("confirmar_nova_senha", 'Senha especificada é diferente da anterior', 'is-invalid');
					else
						Main.create_edit();
				}
				else
					Main.create_edit();
			}
		}
	},
	menu_validar : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome", 'Informe o nome de menu', 'is-invalid');
		else if($("#ordem").val() == "")
			Main.show_error("ordem", 'Informe o número da ordem', 'is-invalid');
		else
			Main.create_edit();
	},
	modulo_validar : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome", 'Informe o nome de módulo', 'is-invalid');
		else if($("#descricao").val() == "")
			Main.show_error("descricao", 'Informe a descrição de módulo', 'is-invalid');
		else if($("#url_modulo").val() == "")
			Main.show_error("url_modulo", 'Informe a url de módulo', 'is-invalid');
		else if($("#ordem").val() == "")
			Main.show_error("ordem", 'Informe o número da ordem', 'is-invalid');
		else
			Main.create_edit();
	},
	grupo_validar : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome", 'Informe o nome de grupo', 'is-invalid');
		else
			Main.create_edit();
	},
	id_registro : "",
	confirm_delete : function(id){
		Main.id_registro = id;
		$("#menssagem_confirm").html("Deseja realmente excluir o registro selecionado?");
		$('#admin_confirm_modal').modal({
			keyboard: false,
			backdrop : 'static'
		});
	},
	delete_registro : function(){
		$.ajax({
			url: Main.base_url+$("#controller").val()+'/deletar/'+Main.id_registro,
			dataType:'json',
			cache: false,
			type: 'POST',
			complete: function (data) {
				location.reload();
			}
		});
	},
	settings : function(){
		$("#settings").modal('show');
	},
	validar_senha_primeiro_acesso : function() {
		var codigo_ativacao = $("#codigo_ativacao").val();
		var nova_senha = $("#nova_senha").val();
		var confirmar_nova_senha = $("#confirmar_nova_senha").val();

		if(codigo_ativacao.length == 0)
			Main.show_error("codigo_ativacao", 'Insira o código de ativação', 'is-invalid');
		else if(codigo_ativacao.length < 6)
			Main.show_error("codigo_ativacao", 'O código de ativação deve conter 6 caracteres numéricos', 'is-invalid');
		else if(nova_senha.length == 0)
			Main.show_error("nova_senha", 'Insira a nova senha', 'is-invalid');
		else if(nova_senha.length < 8)
			Main.show_error("nova_senha", 'A senha deve conter no mínimo 8 caracteres.', 'is-invalid');
		else if(confirmar_nova_senha == 0)
			Main.show_error("confirmar_nova_senha", 'Confirme a nova senha', 'is-invalid');
		else if(nova_senha != confirmar_nova_senha)
			Main.show_error("confirmar_nova_senha", 'As senhas não coincidem', 'is-invalid');
		else
			Main.create_edit();
	},
	validar_redefinir_senha : function()//validar na solicitação de uma nova senha
	{
		var email = $("#email").val();

		if(email == "")
			Main.show_error("email", 'Informe o e-mail de usuário', 'is-invalid');
		else if(Main.valida_email(email) == false)
			Main.show_error("email", 'Formato de e-mail inválido', 'is-invalid');
		else
			Main.create_edit();
	},
	validar_nova_senha : function()//validar a senha nova que o usuário está inserindo
	{
		var nova_senha = $("#nova_senha").val();
		var confirmar_nova_senha = $("#confirmar_nova_senha").val();

		if(nova_senha.length == 0)
			Main.show_error("nova_senha", 'Insira a nova senha', 'is-invalid');
		else if(nova_senha.length < 8)
			Main.show_error("nova_senha", 'A senha deve conter no mínimo 8 caracteres.', 'is-invalid');
		else if(confirmar_nova_senha == 0)
			Main.show_error("confirmar_nova_senha", 'Confirme a nova senha', 'is-invalid');
		else if(nova_senha != confirmar_nova_senha)
			Main.show_error("confirmar_nova_senha", 'As senhas não coincidem', 'is-invalid');
		else
			Main.create_edit();
	}
};
var Main = {
	base_url : ((window.location.href.split("/")[2] == "localhost") ? "http://"+window.location.host+"/git/TCC/" : "http://"+window.location.host+"/"),
	//base_url : "http://"+window.location.host+"/git/TCC/",//localhost (pra qualquer dispositivo na rede local)
	load_mask : function(){
		$(document).ready(function(){
			$('[data-toggle="popover"]').popover(),
			$('#telefone').mask('(00) 0000-00009'),
			$('#cep').mask('00000-000'),
			$('#cpf').mask('000.000.000-00'),
			$('#codigo_ativacao').mask('999999'),
			$('#itens_por_pagina').mask('000'),
			$('#porta').mask('0000'),
			$('#data_nascimento').mask('00/00/0000'),
			$('[data-toggle="tooltip"]').tooltip(),
			$('#data1 input').datepicker({
		    	language: "pt-BR",
		    	 clearBtn: true,
		    	todayHighlight: true//,
		    	//autoclose: true
			}),
			$('#clearDates').on('click', function(){
			     
			})   
		});
	},
	get_cookie : function(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
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
					{
						var url_redirect = $("#url_redirect").val();
						url_redirect = url_redirect.replace(/-x/g,"/");
						
						if($("#url_redirect").val() != "")
							window.location.assign(url_redirect);
						else
							location.reload();
					}
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
		$("#mensagem_aguardar").html("Aguarde... encerrando sessão");
		$('#modal_aguardar').modal({
			keyboard: false,
			backdrop : 'static'
		});
		$.ajax({
			type: "POST",
			dataType: "json",
			url: Main.base_url+"account/logout",
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
	method : '',
	form : '',
	create_edit : function (){
		$("#mensagem_aguardar").html("Aguarde... processando dados");
		$('#modal_aguardar').modal({
			keyboard: false,
			backdrop : 'static'
		})
		//QUANDO NÃO FOR DEFINIDO NENHUM MÉTODO NO 'init.js', POR DEFAULT É CONSIDERADO O METÓDO STORE PARA RECEBER OS DADOS
		
		if(Main.method == "" || Main.method == null)
			Main.method = "store";
		
		//QUANDO NÃO HÁ NECESSIDADE DE COLOCAR UM NOME ESPECÍFICO PRO FORMULÁRIO, USA O NOME PADRÃO ESPECIFICADO ABAIXO
		if(Main.form == "" || Main.form == null)
			Main.form = "form_cadastro";

		$.ajax({
			url: Main.base_url+$("#controller").val()+'/'+Main.method,
			data: $("#"+$("form[name="+Main.form+"]").attr("id")).serialize(),
			dataType:'json',
			cache: false,
			type: 'POST',
			success: function (msg) {
				if(msg.response == "sucesso")
				{
					$("#mensagem_aguardar").html("Dados salvos com sucesso");
					window.location.assign(Main.base_url+$("#controller").val()+"/index/"+Main.get_cookie("page"));
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
	usuario_validar : function(){
		if($("#grupo_id").val() == "0")
			Main.show_error("grupo_id", 'Selecione um tipo de usuário', '');
		else if($("#nome").val() == "")
			Main.show_error("nome", 'Informe o nome de usuário', 'is-invalid');
		else if($("#email").val() == "")
			Main.show_error("email", 'Informe o e-mail de usuário', 'is-invalid');
		else if($("#data_nascimento").val() == "")
			Main.show_error("data_nascimento", 'Informe a data de nascimento do usuário', 'is-invalid');

		else if($("#form_cadastro_"+$("#controller").val()).find("input[name='sexo']:checked").length == 0)
			Main.show_error("sexo","Selecione o sexo do usuário","");
		else if(Main.valida_email($("#email").val()) == false)
			Main.show_error("email", 'Formato de e-mail inválido', 'is-invalid');
		else if($("#senha").val() == "")
			Main.show_error("senha", 'Informe a senha de usuário', 'is-invalid');
		else if(document.getElementById("senha") != undefined && $("#senha").val().length < 8)
			Main.show_error("senha", 'A senha deve conter no mínimo 8 caracteres.', 'is-invalid');
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
				
				if($("#nova_senha").val() != "")
				{
					if(document.getElementById("nova_senha") != undefined && $("#nova_senha").val().length < 8)
						Main.show_error("nova_senha", 'A senha deve conter no mínimo 8 caracteres.', 'is-invalid');
					else if($("#confirmar_nova_senha").val() == "")
						Main.show_error("confirmar_nova_senha", 'Repita a nova senha', 'is-invalid');
					else if($("#nova_senha").val() != $("#confirmar_nova_senha").val())
						Main.show_error("confirmar_nova_senha", 'Senha especificada é diferente da anterior', 'is-invalid');
					else
						return true;
				}
				else
					return true;
			}
		}
	},
	aluno_validar : function()
	{
		if($("#matricula").val() == "")
			Main.show_error("matricula", 'Informe a matricula', 'is-invalid');	
		else
			return true;
	}
	,
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
					
		$("#mensagem_confirm").html('Deseja realmente excluir o registro selecionado?');
		$('#modal_confirm').modal({
			keyboard: false,
			backdrop : 'static',
		});
		$('#modal_confirm').on('shown.bs.modal', function () {
		  $('#bt_confirm_modal').trigger('focus')
		})

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
	validar_senha_primeiro_acesso : function() {
		
		Main.method = "altera_senha_primeiro_acesso";

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
		Main.method = "valida_redefinir_senha";

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
		Main.method = "alterar_senha";

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
	},
	settings_geral_validar : function()
	{
		Main.form = "form_cadastro_configuracoes_geral";

		if($("#itens_por_pagina").val() == "")
			Main.show_error("itens_por_pagina", 'Informe a quantidade de ítens por página', 'is-invalid');
		else if($("#itens_por_pagina").val() < 0)
			Main.show_error("itens_por_pagina", 'Informe um número positivo', 'is-invalid');
		else
			Main.create_edit();
	},
	validar_config_email : function()
	{
		Main.form = "form_cadastro_configuracoes_email";
		Main.method = "store_email";
		
		if($("#email").val() == "")
			Main.show_error("email", 'Informe um e-mail válido', 'is-invalid');
		else if(Main.valida_email($("#email").val()) == false)
			Main.show_error("email", 'Formato de e-mail inválido', 'is-invalid');
		else
			Main.create_edit();
	},
	validar_disciplina : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome", 'Informe um nome de disciplina', 'is-invalid');
		else
			Main.create_edit();
	},
	validar_curso : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome","Informe o nome do curso","is-invalid");
		else if($('input:checkbox[name^=disciplinas]:checked').length == 0)
			Main.show_error("discip","Selecione ao menos uma disciplina","");
		else
			Main.create_edit();
	},
	altera_tipo_cadastro_usuario : function(tipo,registro,method)
	{
		if(tipo != 0)
		{
			$("#mensagem_aguardar").html("Aguarde um momento");
			$('#modal_aguardar').modal({
					keyboard: false,
					backdrop : 'static'
				});

			if(tipo == 1 || tipo == 3 || tipo == 4)//admin||secretaria||professor
				window.location.assign(Main.base_url+"usuario/"+method+"/"+registro+"/"+tipo);
			else if(tipo == 2)//aluno
				window.location.assign(Main.base_url+"aluno/"+method+"/"+registro+"/"+tipo);
		}
	}
};
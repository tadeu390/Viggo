var Main = {
	base_url : ((window.location.href.split("/")[2] == "localhost") ? "http://"+window.location.host+"/git/TCC/" : "http://"+window.location.host+"/"),
	//base_url : "http://"+window.location.host+"/git/TCC/",//localhost (pra qualquer dispositivo na rede local)
	load_mask : function(){
		$(document).ready(function(){
			$('[data-toggle="popover"]').popover(),
			$(".chosen-select").chosen({no_results_text: "Não encontrado"}); 
			$('#telefone').mask('(00) 0000-00009'),
			$('#cep').mask('00000-000'),
			$('#cpf').mask('000.000.000-00'),
			$('#codigo_ativacao').mask('999999'),
			$('#itens_por_pagina').mask('000'),
			$('#porta').mask('0000'),
			$('#data_nascimento').mask('00/00/0000'),
			$('#periodo').mask('0000/0000'),
			$('#limite_falta').mask('000'),
			$('#dias_letivos').mask('000'),
			$('#media').mask('000'),
			$('#duracao_aula').mask('000'),
			$('#quantidade_aula').mask('00'),
			$('#reprovas').mask('00'),
			$('#valor').mask('00'),
			$('#qtd_minimo').mask('000'),
			$('#qtd_maxima').mask('000'),
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
	modal : function(tipo, mensagem)
	{
		$("#mensagem_"+tipo).html(mensagem);
		$('#modal_'+tipo).modal({
			keyboard: false,
			backdrop : 'static',
		});

		if(tipo == "aviso")
		{
			$('#modal_aviso').on('shown.bs.modal', function () {
			 	$('#bt_close_modal_aviso').trigger('focus')
			})
		}
		else if(tipo == "confirm")
		{
			$('#modal_confirm').on('shown.bs.modal', function () {
		  		$('#bt_confirm_modal').trigger('focus')
			})
		}
	},
	weekday : function(dia)
	{
		var arrayDia = new Array(8);
		arrayDia[1] = "Segunda";
		arrayDia[2] = "Terça";
		arrayDia[3] = "Quarta";
		arrayDia[4] = "Quinta";
		arrayDia[5] = "Sexta";
		arrayDia[6] = "Sábado";
		arrayDia[7] = "Domingo";

		return arrayDia[dia];
	},
	str_to_date : function(str)
	{
		return new Date(new Date(str.split('/')[2],str.split('/')[1],str.split('/')[0]));
	},
	convert_date : function(str,to_region)
	{
		if(to_region == "en")
		{
			return str.split('/')[2]+'-'+str.split('/')[1]+'-'+str.split('/')[0];
		}
		else if(to_region == "pt")
		{
			return str.split('-')[2]+'/'+str.split('-')[1]+'/'+str.split('-')[0];
		}
	},
	corta_string : function (string, tam)
	{
		var str = string.substr(0, tam);
		
		if(string.length > tam)
			str = str + "...";
		
		return str;
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
			Main.modal("aguardar","Aguarde... validando seus dados.");
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
						Main.modal("aviso", msg.response);
					}
				}
			});
		}
	},
	troca_status: function(idd)//checkbox de permissões
	{
		//settimeout para recuperar o efeito de transição do botão, somente por questões de estética
		setTimeout(function(){
			document.getElementById(idd).className = "checkbox checbox-switch switch-success";
		},500);	
		document.getElementById("flag"+idd).value = "success";
	},
	logout : function (){
		Main.modal("aguardar", "Aguarde... encerrando sessão");
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
	create_edit : function ()
	{
		Main.modal("aguardar", "Aguarde... processando dados.");
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
						Main.modal("aviso", msg.response);
					},500);
				}
			}
		}).fail(function(msg){
			    setTimeout(function(){
			    	$("#modal_aguardar").modal('hide');
				    Main.modal("aviso", "Houve um erro ao processar sua requisição. Verifique sua conexão com a internet.");
				},500);
			});
	},
	usuario_validar : function(){
		if($("#grupo_id").val() == "0")
			Main.show_error("grupo_id", 'Selecione um tipo de usuário', '');
		else if($("#nome").val() == "")
			Main.show_error("nome", 'Informe o nome de usuário', 'is-invalid');
		else if($("#nome").val().length > 100)
			Main.show_error("nome", 'Máximo 100 caracteres', 'is-invalid');
		else if($("#email").val() == "")
			Main.show_error("email", 'Informe o e-mail de usuário', 'is-invalid');
		else if($("#email").val().length > 100)
			Main.show_error("email", 'Máximo 100 caracteres', 'is-invalid');
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
		/*if($("#ra").val() == "")
			Main.show_error("ra", 'Informe o RA do aluno', 'is-invalid');	
		else*/
			return true;
	}
	,
	menu_validar : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome", 'Informe o nome de menu', 'is-invalid');
		else if($("#nome").val().length > 20)
			Main.show_error("nome", 'Máximo 20 caracteres', 'is-invalid');
		else if($("#ordem").val() == "")
			Main.show_error("ordem", 'Informe o número da ordem', 'is-invalid');
		else
			Main.create_edit();
	},
	modulo_validar : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome", 'Informe o nome de módulo', 'is-invalid');
		else if($("#nome").val().length > 20)
			Main.show_error("nome", 'Máximo 20 caracteres', 'is-invalid');
		else if($("#descricao").val() == "")
			Main.show_error("descricao", 'Informe a descrição de módulo', 'is-invalid');
		else if($("#descricao").val().length > 50)
			Main.show_error("descricao", 'Máximo 50 caracteres', 'is-invalid');
		else if($("#url_modulo").val() == "")
			Main.show_error("url_modulo", 'Informe a url do módulo', 'is-invalid');
		else if($("#url_modulo").val().length > 20)
			Main.show_error("url_modulo", 'Máximo 20 caracteres', 'is-invalid');
		else if($("#ordem").val() == "")
			Main.show_error("ordem", 'Informe o número da ordem', 'is-invalid');
		else if($("#icone").val() == "")
			Main.show_error("icone", 'Informe o ícone do módulo', 'is-invalid');
		else if($("#icone").val().length > 50)
			Main.show_error("icone", 'Máximo 50 caracteres', 'is-invalid');
		else
			Main.create_edit();
	},
	grupo_validar : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome", 'Informe o nome de grupo', 'is-invalid');
		else if($("#nome").val().length > 20)
			Main.show_error("nome", 'Máximo 20 caracteres', 'is-invalid');
		else
			Main.create_edit();
	},
	validar_turma : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome", 'Informe o nome da turma', 'is-invalid');
		else if($("#modalidade_id").val() == "0")
			Main.show_error("modalidade_id", 'Selecione uma modalidade para a turma.', '');
		else if($("#curso_id").val() == "0")
			Main.show_error("curso_id", 'Selecione um curso para a turma.', '');
		else if($("#grade_id").val() == "0")
			Main.show_error("grade_id", 'Selecione uma grade para a turma.', '');
		else if($("#periodo_grade_id").val() == "0")
			Main.show_error("periodo_grade_id", 'Selecione um período da grade para a turma.', '');
		else if(Main.valida_turma_disciplina(1) == false)
			Main.show_error("disciplinas", 'Selecione pelo menos uma disciplina para a turma.', '');
		else if(Main.valida_turma_disciplina(2) == false)
			Main.show_error("disciplinas", 'Há disciplinas marcadas que não foram preenchidas.', '');
		else if($("#quantidade_alunos_aux").val() == "0")
			Main.modal("aviso", "Selecione pelo menos "+(($("#quantidade_minima_aux").val() == '-') ? '1' : $("#quantidade_minima_aux").val())+" aluno(s) para a turma.");
		else if($("#quantidade_minima_aux").val() != "-" && 
			parseInt($("#quantidade_alunos_aux").val()) < parseInt($("#quantidade_minima_aux").val()))
			Main.modal("aviso", "A quantidade de alunos adicionados é inferior a quantidade mínima permitda.");
		else if($("#quantidade_maxima_aux").val() != "-" && 
			parseInt($("#quantidade_alunos_aux").val()) > parseInt($("#quantidade_maxima_aux").val()))
			Main.modal("aviso", "A quantidade de alunos adicionados é superior a quantidade máxima permitda.");
		else
			Main.create_edit();
	},
	valida_turma_aluno : function()
	{
		/*for (var i = 0; i < $("#limite_aluno_add").val(); i ++) 
		{
			if($('input:checkbox[name^=nome_aluno_add'+i+']:checked').length > 0)
		}*/
	},
	valida_turma_disciplina : function(type)//se for 1 valida se foi marcado pelo menos uma disciplina
	{										//se for 2 valida se todas as disciplinas marcadas foram preenchidas
		var flag_disciplina = 0;
		var flag_disciplina_preenchida = 1;
		for (var i = 0; i < $("#limite_disciplina").val(); i ++) 
		{
			if($('input:checkbox[name^=nome_disciplina'+i+']:checked').length > 0)
			{
				Main.show_error('disciplinas','','');
				if($("#categoria_id"+i).val() == "0")
				{
					Main.show_error('categoria_id'+i,'Não selecionado','');
					flag_disciplina_preenchida = 0;
				}
				else
					Main.show_error('categoria_id'+i,'','');
				if($("#professor_id"+i).val() == "0")
				{
					Main.show_error('professor_id'+i,'Não selecionado','');
					flag_disciplina_preenchida = 0;
				}
				else
					Main.show_error('professor_id'+i,'','');
				flag_disciplina = 1;
			}
		}
		if(type == 1)
		{
			if (flag_disciplina) return true;
			return false;
		}
		else
		{
			if (flag_disciplina_preenchida) return true;
			return false;
		}
	},
	id_registro : "",
	confirm_delete : function(id)
	{
		Main.id_registro = id;
					
		Main.modal("confirm", "Deseja realmente excluir o registro selecionado?");
	},
	delete_registro : function()
	{
		$.ajax({
			url: Main.base_url+$("#controller").val()+'/deletar/'+Main.id_registro,
			dataType:'json',
			cache: false,
			type: 'POST',
			success: function (data) {
				if(data.response == "sucesso")
					location.reload();
			}
		}).fail(function(msg){
			    setTimeout(function(){
			    	$("#modal_confirm").modal('hide');
			    	Main.modal("aviso", "Houve um erro ao processar sua requisição. Verifique sua conexão com a internet.");
				},500);
			});
	},
	senha_primeiro_acesso_validar : function() 
	{
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
	redefinir_senha_validar : function()//validar na solicitação de uma nova senha
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
	nova_senha_validar : function()//validar a senha nova que o usuário está inserindo
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
	config_email_validar : function()
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
	disciplina_validar : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome", 'Informe um nome de disciplina', 'is-invalid');
		else if($("#nome").val().length > 200)
			Main.show_error("nome", 'Máximo 200 caracteres', 'is-invalid');
		else if($("#apelido").val() == "")
			Main.show_error("apelido", 'Informe o apelido da disciplina', 'is-invalid');
		else if($("#apelido").val().length > 40)
			Main.show_error("apelido", 'Máximo 40 caracteres', 'is-invalid');
		else
			Main.create_edit();
	},
	inscricao_validar : function()
	{
		if($("#aluno_id").val() == "0")
			Main.show_error("aluno_id", 'Selecione um aluno.', '');
		else if($("#curso_id").val() == "0")
			Main.show_error("curso_id", 'Selecione um curso.', '');
		else if($("#modalidade_id").val() == "0")
			Main.show_error("modalidade_id", 'Selecione uma modalidade.', '');
		else
			Main.create_edit();
	},
	curso_validar : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome","Informe o nome do curso","is-invalid");
		else if($("#nome").val().length > 100)
			Main.show_error("nome", 'Máximo 100 caracteres', 'is-invalid');
		else
			Main.create_edit();
	},
	altera_tipo_cadastro_usuario : function(tipo,registro,method)
	{
		if(tipo != 0)
		{
			Main.modal("aguardar", "Aguarde um momento");

			if(tipo == 1 || tipo == 3 || tipo == 4)//admin||secretaria||professor
				window.location.assign(Main.base_url+"usuario/"+method+"/"+registro+"/"+tipo);
			else if(tipo == 2)//aluno
				window.location.assign(Main.base_url+"aluno/"+method+"/"+registro+"/"+tipo);
		}
	},
	oculta_limite_falta : function()
	{
		var a = false;
		a = document.getElementById("limite_falta").disabled;
		if($('input:checkbox[name^=avaliar_faltas]:checked').length == 1 && a  == false)
		{
			document.getElementById("limite_falta").disabled = true;
			document.getElementById("limite_falta").value = "";
		}
		else
			document.getElementById("limite_falta").disabled = false;
	},
	validar_regras : function()
	{
		if($("#modalidade_id").val() == "0")
			Main.show_error("modalidade_id", 'Selecione uma modalidade.', '');
		else if($("#periodo").val() == "")
			Main.show_error("periodo", 'Informe o período letivo.', 'is-invalid');
		else if($('input:checkbox[name^=avaliar_faltas]:checked').length == 0 && $("#limite_falta").val() == "")
			Main.show_error("limite_falta", 'Informe o limite de faltas ou marque a opção acima.', 'is-invalid');				
		else if($('input:checkbox[name^=avaliar_faltas]:checked').length == 0 && $("#limite_falta").val() > 100)
			Main.show_error("limite_falta", 'O limite de falta deve estar entre 0 e 100.', 'is-invalid');
		else if($("#dias_letivos").val() == "")
			Main.show_error("dias_letivos", 'Informe quantos dias letivos terá este período.', 'is-invalid');
		else if($("#media").val() == "")
			Main.show_error("media", 'Informe a média de aprovação.', 'is-invalid');
		else if($("#media").val() > 100)
			Main.show_error("media", 'A média de aprovação deve estar entre 0 e 100.');
		else if($("#duracao_aula").val() == "")
			Main.show_error("duracao_aula", 'Informe quanto tempo terá cada aula.', 'is-invalid');
		else if($("#hora_inicio_aula").val() == "")
			Main.show_error("hora_inicio_aula", 'Informe a hora de início da aula.', 'is-invalid');
		else if($("#quantidade_aula").val() == "")
			Main.show_error("quantidade_aula", 'Informe a quantidade de aulas por dia.', 'is-invalid');
		else if($("#reprovas").val() == "")
			Main.show_error("reprovas", 'Informe quantas disciplinas o aluno poderá carregar.', 'is-invalid');
		else if($("#qtd_minima").val() != "" && $("#qtd_maxima").val() != "" && parseInt($("#qtd_minima").val()) > parseInt($("#qtd_maxima").val()))
			Main.show_error("qtd_maxima", 'A quantidade máxima deve ser superior ou igual a quantidade mínima.', 'is-invalid');
		else 
			Main.create_edit();
	},
	add_intervalo : function()
	{
		if(Main.intervalo_validar() == true)
		{
			var max_value_intervalo  =  $("#max_value_intervalo").val();

			var a = new Array();
			a.push(Main.weekday($("#dia").val()));
			a.push($("#hora_inicio").val()+":00");
			a.push($("#hora_fim").val()+":00");
			a.push("");

			var aux = new Array();
			aux.push("dia");
			aux.push("hora_inicio");
			aux.push("hora_fim");
			aux.push("");

			var node_tr = document.createElement("TR");
			node_tr.setAttribute("id","intervalo"+max_value_intervalo);
			
			for(var i = 0; i < 4; i++)
			{
				var node_td = document.createElement("TD");
				//if(i < 3)
				//	node_td.className = "text-center";
				
				var input_text = document.createElement("INPUT");
				input_text.setAttribute("type", "hidden");
				if(i < 3)
					input_text.setAttribute("value", a[i]);
				input_text.setAttribute("id",aux[i]+max_value_intervalo);
				input_text.setAttribute("name",aux[i]+max_value_intervalo);
				
				var textnode = document.createTextNode(a[i]); 
				node_td.appendChild(input_text);
				node_td.appendChild(textnode);
				
				if(i == 3)
					node_td.innerHTML = "<span class='glyphicon glyphicon-remove pointer' title='Remover' onclick='Main.remove_elemento(\"intervalo"+max_value_intervalo+"\");'></span>";
				
				node_tr.appendChild(node_td);
				document.getElementById("intervalos").appendChild(node_tr);
			}
			$("#max_value_intervalo").val(parseInt(max_value_intervalo) + 1);
		}
	},
	intervalo_validar : function()
	{
		if($("#hora_inicio").val() == "")
			Main.show_error("hora_inicio", 'Informe a hora de início para o intervalo.', '');
		else if($("#hora_fim").val() == "")
			Main.show_error("hora_fim", 'Informe a hora de fim para o intervalo.', '');
		else if($("#hora_inicio").val() > $("#hora_fim").val())
			Main.show_error("hora_fim", 'O Horário de fim deve ser maior do que o de início.', '');
		else if($("#hora_inicio").val()+":00" < $("#hora_inicio_aula").val())
			Main.show_error("hora_inicio", 'O início do intervalo não pode ser inferior ao horário de início da aula', '');
		else if($("#dia").val() == "0")
			Main.show_error("dia", 'Informe o dia deste intervalo.', '');
		else
		{
			var max_value_intervalo  =  $("#max_value_intervalo").val();

			var a = new Array();
			a.push(Main.weekday($("#dia").val()));
			a.push($("#hora_inicio").val()+":00");
			a.push($("#hora_fim").val()+":00");
			a.push("");

			var flag = 0;
			for(var i = 0; i < max_value_intervalo; i++)
			{
				if($("#dia"+i).val() == a[0] && $("#hora_inicio"+i).val() == a[1] &&
					$("#hora_fim"+i).val() == a[2])
					flag = 1;
				console.log("form"+Main.weekday($("#dia"+i).val()) +" a0: "+a[0]);
			}

			var flag2 = 0;
			for(var i = 0; i < max_value_intervalo; i++)
			{
				if($("#dia"+i).val() == a[0] && (a[1] >= $("#hora_inicio"+i).val()  &&
					a[1] <= $("#hora_fim"+i).val() || a[2] >= $("#hora_inicio"+i).val()  &&
					a[2] <= $("#hora_fim"+i).val()))
					flag2 = 1;
			}

			if(flag == 1)
				Main.modal("aviso", "Este intervalo já existe na lista. Se deseja edita-lo, remova-o da lista e o adicione novamente.");
			else if (flag2 == 1)
				Main.modal("aviso", "Horário inválido.");
			else
				return true;
		}
	},
	remove_elemento : function (id)
	{
		var linha = document.getElementById(id);
		if(linha != undefined)
			linha.parentNode.removeChild(linha);
	},
	add_bimestre : function ()
	{
		if(Main.bimestre_validar() == true)
		{
			var max_value_bimestre  =  $("#max_value_bimestre").val();

			var a = new Array();
			a.push($("#nome_bimestre").val());
			a.push($("#valor").val());
			a.push($("#data_inicio").val());
			a.push($("#data_fim").val());
			a.push(($("#data_abertura").val() == '') ? '' : $("#data_abertura").val());
			a.push(($("#data_fechamento").val() == '') ? '' : $("#data_fechamento").val());
			a.push("");

			var aux = new Array();
			aux.push("nome_bimestre");
			aux.push("valor");
			aux.push("data_inicio");
			aux.push("data_fim");
			aux.push("data_abertura");
			aux.push("data_fechamento");
			aux.push("");

			var node_tr = document.createElement("TR");
			node_tr.setAttribute("id","bimestre"+max_value_bimestre);
			
			for(var i = 0; i < 7; i++)
			{
				var node_td = document.createElement("TD");
				
				var input_text = document.createElement("INPUT");
				input_text.setAttribute("type", "hidden");
				if(i < 6)
					input_text.setAttribute("value", a[i]);
				input_text.setAttribute("id",aux[i]+max_value_bimestre);
				input_text.setAttribute("name",aux[i]+max_value_bimestre);
				
				var textnode = document.createTextNode(a[i]); 
				node_td.appendChild(input_text);
				node_td.appendChild(textnode);
				
				if(i == 6)
					node_td.innerHTML = "<span class='glyphicon glyphicon-remove pointer' title='Remover' onclick='Main.remove_elemento(\"bimestre"+max_value_bimestre+"\");'></span>";
				
				node_tr.appendChild(node_td);
				document.getElementById("bimestres").appendChild(node_tr);
			}
			$("#max_value_bimestre").val(parseInt(max_value_bimestre) + 1);
		}
	},
	bimestre_validar : function()
	{
		if($("#nome_bimestre").val() == "")
			Main.show_error("nome_bimestre", 'Informe o nome do bimestre.', '');
		else if($("#valor").val() == "")
			Main.show_error("valor", 'Informe o valor do bimestre.', '');
		else if($("#data_inicio").val() == "")
			Main.show_error("data_inicio", 'Informe a data de início do bimestre.', '');
		else if($("#data_fim").val() == "")
			Main.show_error("data_fim", 'Informe a data de fim do bimestre.', '');
		else if(Main.str_to_date($("#data_fim").val()) <= Main.str_to_date($("#data_inicio").val()))
			Main.show_error("data_fim", 'A data de fim deve ser maior que a data de início.', '');
		else if($("#data_abertura").val() == "")
			Main.show_error("data_abertura", 'Informe a data de abertura', '');
		else if($("#data_fechamento").val() == "")
			Main.show_error("data_fechamento", 'Informe a data de fechamento', '');
		else if(Main.str_to_date($("#data_fechamento").val()) <= Main.str_to_date($("#data_abertura").val()))
			Main.show_error("data_fechamento", 'A data de fechamento deve ser maior que a data de abertura.', '');
		else if(Main.str_to_date($("#data_abertura").val()) < Main.str_to_date($("#data_inicio").val()) ||
				Main.str_to_date($("#data_fechamento").val()) > Main.str_to_date($("#data_fim").val()))
			Main.show_error("data_fechamento", 'Data de abertura / fechamento deve estar entre a data de início e fim.', '');
		else
		{
			var max_value_bimestre  =  $("#max_value_bimestre").val();

			var a = new Array();
			a.push($("#nome_bimestre").val());
			a.push($("#valor").val());
			a.push($("#data_inicio").val());
			a.push($("#data_fim").val());
			a.push($("#data_abertura").val());
			a.push($("#data_fechamento").val());
			a.push("");

			var flag = 0;
			for(var i = 0; i < max_value_bimestre; i++)
			{
				if($("#nome_bimestre"+i).val() == a[0] && $("#valor"+i).val() == a[1] &&
					$("#data_inicio"+i).val() == a[2] && $("#data_fim"+i).val() == a[3])
					flag = 1;
				else if($("#valor"+i).val() == a[1] &&
					$("#data_inicio"+i).val() == a[2] && $("#data_fim"+i).val() == a[3])
					flag = 2;
			}

			if(flag == 1)
				Main.modal("aviso", "Este bimestre já existe na lista. Se deseja edita-lo, remova-o da lista e o adicione novamente.");
			else if(flag == 2)
				Main.modal("aviso", "As datas informadas já estão em uso para um bimestre na lista.");
			else
				return true;
		}
	},
	modalidade_validar : function()
	{
		if($("#nome").val() == "")
			Main.show_error("nome", 'Informe o nome da modalidade', 'is-invalid');
		else if($("#nome").val().length > 100)
			Main.show_error("nome", 'Máximo 100 caracteres', 'is-invalid');
		else
			Main.create_edit();
	},
	load_data_periodo_letivo : function(modalidade_id)
	{
		if(modalidade_id != 0)
		{
			$.ajax({
				url: Main.base_url+$("#controller").val()+'/periodo_letivo/'+modalidade_id,
				dataType:'json',
				cache: false,
				type: 'POST',
				success: function (data) 
				{
					if(data.response != "0")
					{
						var minimo = '-';
						var maximo = '-';
						if(data.response.Qtd_minima_aluno != 0) minimo = data.response.Qtd_minima_aluno;
						if(data.response.Qtd_maxima_aluno != 0) maximo = data.response.Qtd_maxima_aluno;

						$("#quantidade_minima").html("Mínimo "+minimo);
						$("#quantidade_maxima").html("Máximo "+maximo);
						$("#quantidade_minima_aux").val(minimo);
						$("#quantidade_maxima_aux").val(maximo);
						$("#nome_periodo_letivo").val(data.response.Periodo);
					}
					else if(modalidade_id != 0)
					{
						Main.modal("aviso","Nenhum período letivo foi identificado para esta modalidade. Por favor, primeiro cadastre o período letivo.");
						$("#quantidade_minima").html("Mínimo -");
						$("#quantidade_maxima").html("Máximo -");
						$("#quantidade_minima_aux").val('-');
						$("#quantidade_maxima_aux").val('-');
						$("#nome_periodo_letivo").val("Não encontrado.");
					}
					else{
						$("#quantidade_minima").html("Mínimo -");
						$("#quantidade_maxima").html("Máximo -");
						$("#quantidade_minima_aux").val('-');
						$("#quantidade_maxima_aux").val('-');
						$("#nome_periodo_letivo").val("");
					}
				}
			}).fail(function(msg){
			    setTimeout(function(){
			    	$("#modal_confirm").modal('hide');
			    	Main.modal("aviso", "Houve um erro ao processar sua requisição. Verifique sua conexão com a internet.");
				},500);
			});
			Main.load_data_aluno("");
			Main.load_data_turma_filtro();
			Main.load_grade();
		}
	},
	habilita_curso : function(id)
	{
		if(id != 0)
			document.getElementById("curso_id").disabled = false;
		else
			document.getElementById("curso_id").disabled = true;
		Main.load_data_periodo_letivo(id);

	},
	load_grade : function ()
	{
		if($("#curso_id").val() != 0 && $("#modalidade_id").val() != 0)
		{
			Main.modal("aguardar", "Aguarde...");
			$.ajax({
				url: Main.base_url+$("#controller").val()+'/grade/'+$("#modalidade_id").val() + '/' + $("#curso_id").val(),
				dataType:'json',
				cache: false,
				type: 'POST',
				success: function (data) 
				{
					setTimeout(function(){
						$("#modal_aguardar").modal('hide');
					},500);
					document.getElementById("grade").innerHTML = data.response;
					if(data.aviso != "")
						Main.modal("aviso", data.aviso);
				}
			}).fail(function(msg){
			    setTimeout(function(){
			    	$("#modal_confirm").modal('hide');
			    	Main.modal("aviso", "Houve um erro ao processar sua requisição. Verifique sua conexão com a internet.");
				},500);
			});
		}
	},
	load_periodo_grade : function(grade_id) 
	{
		if($("#grade_id").val() != 0)
		{
			Main.modal("aguardar", "Aguarde...");
			$.ajax({
				url: Main.base_url+$("#controller").val()+'/periodo_grade/'+$("#grade_id").val(),
				dataType:'json',
				cache: false,
				type: 'POST',
				success: function (data) 
				{
					setTimeout(function(){
						$("#modal_aguardar").modal('hide');
					},500);
					document.getElementById("periodo_grade").innerHTML = data.response;
					document.getElementById("turma_id").innerHTML = "<option value='0' class='background_dark'>Turmas</option>";
					if(data.aviso != "")
						Main.modal("aviso", data.aviso);
				}
			}).fail(function(msg){
			    setTimeout(function(){
			    	$("#modal_confirm").modal('hide');
			    	Main.modal("aviso", "Houve um erro ao processar sua requisição. Verifique sua conexão com a internet.");
				},500);
			});
		}
	},
	load_grade_disciplina : function()//carrega a grade
	{
		if($("#periodo_grade_id").val() != 0)
		{
			Main.modal("aguardar", "Aguarde...");
			$.ajax({
				url: Main.base_url+$("#controller").val()+'/grade_disciplina/'+$("#grade_id").val()+'/'+$("#periodo_grade_id").val() + '/' + (($("#id").val() == "") ? 0 : $("#id").val()),
				dataType:'json',
				cache: false,
				type: 'POST',
				success: function (data) 
				{
					setTimeout(function(){
						$("#modal_aguardar").modal('hide');
					},500);
					document.getElementById("disciplinas").innerHTML = data.response;
				}
			}).fail(function(msg){
			    setTimeout(function(){
			    	$("#modal_confirm").modal('hide');
			    	Main.modal("aviso", "Houve um erro ao processar sua requisição. Verifique sua conexão com a internet.");
				},500);
			});
		}
		Main.load_data_aluno("");
		Main.load_data_turma_filtro();
	},
	load_filtro_turma_aluno : function()//monta o filtro
	{
		var nome = (($("#nome_aluno").val() == "") ? 0 : $("#nome_aluno").val());
		var data_renovacao_inicio = (($("#data_renovacao_inicio").val() == "") ? 0 : $("#data_renovacao_inicio").val());
		var data_renovacao_fim = (($("#data_renovacao_fim").val() == "") ? 0 : $("#data_renovacao_fim").val());
		
		if(data_renovacao_inicio != 0)
			data_renovacao_inicio = Main.convert_date(data_renovacao_inicio, "en");
		if(data_renovacao_fim != 0)
			data_renovacao_fim = Main.convert_date(data_renovacao_fim, "en");

		Main.load_data_aluno(nome + "/" + data_renovacao_inicio + "/" + data_renovacao_fim);
		document.getElementById("turma_id").value = 0;
	},
	load_data_aluno : function(filtro)//Carrega os alunos para a lista a esquerda quando editando e quando usando o filtro pressionando o botão Pesquisar
	{ 
		if($("#curso_id").val() != "0" && $("#modalidade_id").val() != "0")
		{
			var pesquisa = "get_alunos_inscritos";
			if($("#aluno_novo").is(":checked") == true)
				var pesquisa = "get_alunos_inscritos_novos";
			Main.modal("aguardar", "Aguarde...");

			$.ajax({
				url: Main.base_url + $("#controller").val() + '/' + pesquisa + '/' + $("#curso_id").val() + "/" + $("#modalidade_id").val() + '/' + (($("#id").val() == "") ? 0 : $("#id").val()) + '/' + $("#grade_id").val() + '/' + filtro,
				dataType:'json',
				cache: false,
				type: 'POST',
				success: function (data) 
				{
					setTimeout(function(){
						$("#modal_aguardar").modal('hide');
					},500);
					document.getElementById("alunos").innerHTML = data.response;;
				}
			}).fail(function(msg){
			    setTimeout(function(){
			    	$("#modal_confirm").modal('hide');
			    	Main.modal("aviso", "Houve um erro ao processar sua requisição. Verifique sua conexão com a internet.");
				},500);
			});
		}
	},
	load_data_aluno_turma_antiga : function(turma_id)//Carrega os alunos para a lista a esquerda quando selecionando alguma turma de histórico
	{
		if(turma_id != "0")
		{
			document.getElementById("nome_aluno").value = "";
			Main.modal("aguardar", "Aguarde...");

			$.ajax({
				url: Main.base_url + $("#controller").val() + '/get_alunos_inscritos_turma_antiga/' + turma_id,
				dataType:'json',
				cache: false,
				type: 'POST',
				success: function (data) 
				{
					setTimeout(function(){
						$("#modal_aguardar").modal('hide');
					},500);
					document.getElementById("alunos").innerHTML = data.response;
					
					if(data.aviso != "")
						Main.modal("aviso", data.aviso);
				}
			}).fail(function(msg){
			    setTimeout(function(){
			    	$("#modal_confirm").modal('hide');
			    	Main.modal("aviso", "Houve um erro ao processar sua requisição. Verifique sua conexão com a internet.");
				},500);
			});
		}
	},
	load_data_turma_filtro : function()//Carrega o filtro de turma
	{
		if($("#curso_id").val() != "0" && $("#modalidade_id").val() != "0")
		{
			Main.modal("aguardar", "Aguarde...");

			$.ajax({
				url: Main.base_url + $("#controller").val() + '/get_filtro_turma/' + '/' + $("#curso_id").val() + "/" + $("#modalidade_id").val() + '/' + $("#grade_id").val(),
				dataType:'json',
				cache: false,
				type: 'POST',
				success: function (data) 
				{
					setTimeout(function(){
						$("#modal_aguardar").modal('hide');
					},500);
					document.getElementById("turma_id").innerHTML = "<option value='0' class='background_dark'>Turmas</option>" + data.response;
				}
			}).fail(function(msg){
			    setTimeout(function(){
			    	$("#modal_confirm").modal('hide');
			    	Main.modal("aviso", "Houve um erro ao processar sua requisição. Verifique sua conexão com a internet.");
				},500);
			});
		}
	},
	remove_aluno : function ()//REMOVE OS ALUNOS ADICIONADOS E SELECIONADOS
	{
		for(var i = 0; i < $("#limite_aluno_add").val(); i++)
		{
			if($("#nome_aluno_add"+i).is(":checked") == true)
			{
				Main.remove_elemento("aluno_item_add"+i);
				$("#quantidade_alunos").html("Alunos na turma "+(parseInt($("#quantidade_alunos_aux").val()) - 1));
				$("#quantidade_alunos_aux").val(parseInt($("#quantidade_alunos_aux").val()) - 1);
			}
		}
	},
	add_aluno : function ()
	{
		if($("#modalidade_id").val() == "0")//DEPOIS DE TERMINAR A TELA TODA  TALVEZ ESSE IF NAO SEJA MAIS NECESSARIO, POIS A MODALIDADE TA ARRAMADA AO CURSO E O CURSO AOS ALUNOS QUE SERAO LISTADOS PRA SEREM COLOCADOS NA TURMA
			Main.modal("aviso", "Primeiro selecione uma modalidade.");
		else
		{
			var valido = 1;
			var limite_aluno_add = $("#limite_aluno_add").val();
			for(var i = 0; i < $("#limite_aluno").val(); i++)
			{
				if($("#nome_aluno"+i).is(":checked") == true)
				{
					if(Main.add_aluno_validar($("#aluno_id"+i).val()) == true)
					{
						var node_tr = document.createElement("TR");
						node_tr.setAttribute("id","aluno_item_add"+limite_aluno_add);
						
						var node_td = document.createElement("TD");
						var aluno_id = document.createElement("INPUT");
						aluno_id.setAttribute("type","hidden");
						aluno_id.setAttribute("value",$("#aluno_id"+i).val());
						aluno_id.setAttribute("id","aluno_id_add"+limite_aluno_add);
						aluno_id.setAttribute("name","aluno_id_add"+limite_aluno_add);
						node_td.innerHTML = "<div style='margin-top: 5px; height: 25px;' class='checkbox checbox-switch switch-success custom-controls-stacked'>"
							+"<label for='nome_aluno_add"+limite_aluno_add+"' style='display: block; height: 25px;'>"
								+"<input type='checkbox' id='nome_aluno_add"+limite_aluno_add+"' name='nome_aluno_add"+limite_aluno_add+"' value='1' /><span></span>"
								+Main.corta_string($("#nome_aluno_aux"+i).val(), 25)
							+"</label>"
						+"</div>";
						node_td.setAttribute("title",$("#nome_aluno_aux"+i).val());
						node_td.appendChild(aluno_id);
						node_tr.appendChild(node_td);

						node_td = document.createElement("TD");
						node_td.setAttribute("class","text-center");
						node_td.setAttribute("style","vertical-align: middle;");
						var inp_sub_turma = document.createElement("INPUT");
						inp_sub_turma.setAttribute("type","number");
						inp_sub_turma.setAttribute("class","text-center");
						inp_sub_turma.setAttribute("style","width: 60%;");
						inp_sub_turma.setAttribute("maxlength","1");
						inp_sub_turma.setAttribute("id","sub_turma_add"+limite_aluno_add);
						inp_sub_turma.setAttribute("name","sub_turma_add"+limite_aluno_add);
						inp_sub_turma.setAttribute("value","0");
						node_td.appendChild(inp_sub_turma);
						node_tr.appendChild(node_td);

						node_td = document.createElement("TD");
						node_td.setAttribute("class","text-center");
						node_td.setAttribute("style","vertical-align: middle;");
						node_td.innerHTML = "<span title='Detalhes' style='cursor: pointer;' class='glyphicon glyphicon-th text-danger'></span>";
						node_tr.appendChild(node_td);				

						document.getElementById("alunos_turma").appendChild(node_tr);

						limite_aluno_add = parseInt(limite_aluno_add) + 1;
						document.getElementById('nome_aluno'+i).checked = false;//LIMPA OS CHECK MARCADOS
						
						$("#quantidade_alunos").html("Alunos na turma "+(parseInt($("#quantidade_alunos_aux").val()) + 1));
						$("#quantidade_alunos_aux").val(parseInt($("#quantidade_alunos_aux").val()) + 1);
					}
					else 
						valido = 0;
				}
			}
			$("#limite_aluno_add").val(limite_aluno_add);
			if(valido == 0)
				Main.modal("aviso","Alguns alunos selecionados não foram adicionados, pois já se encontram na lista ou o limite de alunos foi atingido.");
		}
	},
	add_aluno_validar : function(aluno_id)
	{
		for(var i = 0; i < $("#limite_aluno_add").val(); i++)
			if(parseInt($("#aluno_id_add"+i).val()) == parseInt(aluno_id))
				return false;

		if($("#quantidade_maxima_aux").val() != "-" && 
			parseInt($("#quantidade_alunos_aux").val()) == parseInt($("#quantidade_maxima_aux").val()))
			return false;
		return true;
	},
	matricula : function(inscricao_id)
	{
		document.getElementById("bt"+inscricao_id).disabled = true;
		$.ajax({
			url: Main.base_url + $("#controller").val() + '/matricula/' + inscricao_id,
			dataType:'json',
			cache: false,
			type: 'POST',
			success: function (data) 
			{
				setTimeout(function(){
					$("#modal_aguardar").modal('hide');
				},500);
				document.getElementById("tdbt"+inscricao_id).innerHTML = "<span class='glyphicon glyphicon-ok'></span> Matriculado";
			}
		}).fail(function(msg){
		    setTimeout(function(){
		    	$("#modal_confirm").modal('hide');
		    	Main.modal("aviso", "Houve um erro ao processar sua requisição. Verifique sua conexão com a internet.");
			},500);
		});
	},
	add_disciplina : function()
	{
		var valido = 1;
		var limite_disciplina_add = $("#limite_disciplina_add").val();
		for(var i = 0; i < $("#limite_disciplina").val(); i++)
		{
			if($("#nome_disciplina"+i).is(":checked") == true)
			{
				if(Main.add_disciplina_validar($("#disciplina_id"+i).val()) == true)
				{
					var node_tr = document.createElement("TR");
					node_tr.setAttribute("id","disciplina_item_add"+limite_disciplina_add);
					
					var node_td = document.createElement("TD");
					var disciplina_id = document.createElement("INPUT");
					disciplina_id.setAttribute("type","hidden");
					disciplina_id.setAttribute("value",$("#disciplina_id"+i).val());
					disciplina_id.setAttribute("id","disciplina_id_add"+limite_disciplina_add);
					disciplina_id.setAttribute("name","disciplina_id_add"+limite_disciplina_add);
					node_td.innerHTML = "<div style='margin-top: 5px; height: 25px;' class='checkbox checbox-switch switch-success custom-controls-stacked'>"
						+"<label for='nome_disciplina_add"+limite_disciplina_add+"' style='display: block; height: 25px;'>"
							+"<input type='checkbox' id='nome_disciplina_add"+limite_disciplina_add+"' name='nome_disciplina_add"+limite_disciplina_add+"' value='1' /><span></span>"
							+Main.corta_string($("#nome_disciplina_aux"+i).val(), 25)
						+"</label>"
					+"</div>";
					node_td.setAttribute("title",$("#nome_disciplina_aux"+i).val());
					node_td.appendChild(disciplina_id);
					node_tr.appendChild(node_td);

					node_td = document.createElement("TD");
					node_td.setAttribute("class","text-center");
					node_td.setAttribute("style","vertical-align: middle;");
					var inp_periodo = document.createElement("INPUT");
					inp_periodo.setAttribute("type","number");
					inp_periodo.setAttribute("class","text-center");
					inp_periodo.setAttribute("style","width: 60%;");
					inp_periodo.setAttribute("maxlength","1");
					inp_periodo.setAttribute("id","periodo_add"+limite_disciplina_add);
					inp_periodo.setAttribute("name","periodo_add"+limite_disciplina_add);
					inp_periodo.setAttribute("value", $("#periodo_base").val());
					node_td.appendChild(inp_periodo);
					node_tr.appendChild(node_td);			

					document.getElementById("disciplinas_grade").appendChild(node_tr);

					limite_disciplina_add = parseInt(limite_disciplina_add) + 1;
					document.getElementById('nome_disciplina'+i).checked = false;//LIMPA OS CHECK MARCADOS
					
				}
				else 
					valido = 0;
			}
		}
		$("#limite_disciplina_add").val(limite_disciplina_add);
		if(valido == 0)
			Main.modal("aviso","Algumas disciplinas selecionadas não foram adicionados, pois já se encontram na lista.");
	},
	remove_disciplina : function ()//REMOVE AS DISCIPLINAS ADICIONADAS E SELECIONADAS
	{
		for(var i = 0; i < $("#limite_disciplina_add").val(); i++)
		{
			if($("#nome_disciplina_add"+i).is(":checked") == true)
				Main.remove_elemento("disciplina_item_add"+i);
		}
	},
	add_disciplina_validar : function(disciplina_id)
	{
		for(var i = 0; i < $("#limite_disciplina_add").val(); i++)
			if(parseInt($("#disciplina_id_add"+i).val()) == parseInt(disciplina_id) && parseInt($("#periodo_add"+i).val()) == parseInt($("#periodo_base").val()))
				return false;

		return true;
	}
};
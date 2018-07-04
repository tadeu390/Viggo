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
			$('#periodo').mask('0000/000'),
			$('#limite_falta').mask('000'),
			$('#dias_letivos').mask('000'),
			$('#media').mask('000'),
			$('#duracao_aula').mask('000'),
			$('#quantidade_aula').mask('00'),
			$('#reprovas').mask('00'),
			$('#valor').mask('00'),
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
		$("#mensagem_aguardar").html("Aguarde... processando dados.");
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
		else if($("#dias_letivos").val() == "")
			Main.show_error("dias_letivos", 'Informe quantos dias letivos terá este período.', 'is-invalid');
		else if($("#media").val() == "")
			Main.show_error("media", 'Informe a média de aprovação.', 'is-invalid');
		else if($("#duracao_aula").val() == "")
			Main.show_error("duracao_aula", 'Informe quanto tempo terá cada aula.', 'is-invalid');
		else if($("#hora_inicio_aula").val() == "")
			Main.show_error("hora_inicio_aula", 'Informe a hora de início da aula.', 'is-invalid');
		else if($("#quantidade_aula").val() == "")
			Main.show_error("quantidade_aula", 'Informe a quantidade de aulas por dia.', 'is-invalid');
		else if($("#reprovas").val() == "")
			Main.show_error("reprovas", 'Informe quantas disciplinas o aluno poderá carregar.', 'is-invalid');
		else 
			Main.create_edit();
	},
	add_intervalo : function()
	{
		if(Main.intervalo_validar() == true)
		{
			var max_value_intervalo  =  $("#max_value_intervalo").val();

			var a = new Array();
			a.push($("#dia").val());
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
				if(i < 3)
					node_td.className = "text-center";
				
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
			a.push($("#dia").val());
			a.push($("#hora_inicio").val()+":00");
			a.push($("#hora_fim").val()+":00");
			a.push("");

			var flag = 0;
			for(var i = 0; i < max_value_intervalo; i++)
			{
				if($("#dia"+i).val() == a[0] && $("#hora_inicio"+i).val() == a[1] &&
					$("#hora_fim"+i).val() == a[2])
					flag = 1;
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
			{
				$("#mensagem_aviso").html("Este intervalo já existe na lista. Se deseja edita-lo, remova-o da lista e o adicione novamente.");
				$('#modal_aviso').modal({
						keyboard: false,
						backdrop : 'static'
					});
			}
			else if (flag2 == 1)
			{
				$("#mensagem_aviso").html("Horário inválido.");
				$('#modal_aviso').modal({
						keyboard: false,
						backdrop : 'static'
					});
			}
			else
				return true;
		}
	},
	remove_elemento : function (id)
	{
		var linha = document.getElementById(id);
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
			a.push($("#data_abertura").val());
			a.push($("#data_fechamento").val());
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
		else if(new Date($("#data_fim").val()) <= new Date($("#data_inicio").val()))
			Main.show_error("data_fim", 'A data de início deve ser menor que a data de fim.', '');
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
			}

			if(flag == 1)
			{
				$("#mensagem_aviso").html("Este bimestre já existe na lista. Se deseja edita-lo, remova-o da lista e o adicione novamente.");
				$('#modal_aviso').modal({
						keyboard: false,
						backdrop : 'static'
					});
			}
			else
				return true;
		}
	}
};
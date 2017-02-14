var schoolObj = function(type){
	var that = {};
  
  that.typeAjax = type ? type : 'POST';
  
	//Remove a linha de uma tabela, o botão dentro da linha precisa ter o seguinte parametro onclick="funcao(this)"
	that.removeLinhaTabela = function(linha){
		(function($){
			var tr = $(linha).closest('tr');
			tr.fadeOut(400, function(){ 
				tr.remove(); 
			});
		})(jQuery);
	};
  
  /*Faz um ajax para determinada pagina, você precisa seguir os seguintes parametros para realiar o ajax:
    url:  é a url que você vai enviar o ajax, ou seja, para qual php você vai pedir para executar alguma coisa, deve conter o caminho do php
    dados: sao os dados que você vai enviar para o servidor, lembrando que dados sempre sera um objeto contendo pelo menos a posição tipo que indica o que você vai fazer no servidor
    callback: aqui fica a função anonima que você vai executar apos o servidor lhe devolver o resultado, sera executada como callback
    before: aqui você passar uma função anonima que sera executada antes de enviar os dados para o servidor, como por exemplo chamar uma tela de loading, lembrando que esta função é opcional e voce nao precisa necessariamente usa-la
  */
  that.ajax = function(url, dados, callback, before){
    if((!dados.tipo) || (!url)) {
      if(!dados.tipo)
        alert('Voce tentou invocar um ajax sem informar o tipo');
      if(!url)
        alert('Voce tentou invocar um ajax sem informas a url');
    }else{
			dados.ts = that.getTimestamp();
      dados = JSON.stringify(dados);
      $.ajax({
        type: that.typeAjax,
        url: url,
        data: {dados: dados},
        beforeSend: function(){
          if(before)
            before();
        },
        success: function(data){
					if(data){
						data = JSON.parse(data)
						if(callback)
							callback(data);
					}
        }
      });
    }
  };
	
	//inclui um arquivo JS na pagina, semelhante ao include_once do php
	that.include = function(arquivo, callback){
		if(arquivo){
			var sc = document.getElementsByTagName('script');
			for(var x in sc){
				if(sc[x].src != null){
					if(sc[x].src.indexOf(arquivo) != -1)
						return;
				}
			}
			var doc = document.createElement('script');	
			doc.type = 'text/javascript';
			doc.src = arquivo;
			document.body.appendChild(doc);
			if(callback)
				callback();
		}else
			alert('funcão include sem informar o arquivo a ser incluido');
	};

	//Funcao retorna o dia atual q ela foi chamada ja formatada
	that.getDiaAtual = function(){
		var now = new Date;
		var dia = now.getDate();
		var mes = parseInt(now.getMonth()) + 1;
		var ano = now.getFullYear();

		if(dia < 10)
			dia = '0' + dia
		if(mes < 0)	
			mes = '0' + mes;
		
		var data = dia+'/'+mes+'/'+ano;
		return data;
		}
	
	//Toda vez que é preciso colocar uma msg na tela eu informo primeiro a msg e o segundo parametro é o tamanho da fonte, foi adicionaado o parametro callback caso você precise esperar que o cara clique em ok para continuar o codigo e executar alguma outra função
	that.retornaMsg = function(msg,tamanho, callback){
		if(msg){
			tamanho = tamanho ? tamanho : 22;
			msg = '<p style="font-size:'+tamanho+'px">'+msg+'</p>'
			if(callback){
				bootbox.alert(msg, function(){
					if(callback)
						callback();
				});  
			}else{
				bootbox.alert(msg);
			}
		}else
			alert('Erro: retornarMsg sem msg informada');
	};
	
	that.retornaMsgConfirm = function(msg,tamanho, callback){
		if(msg){
			tamanho = tamanho ? tamanho : 22;
			msg = '<p style="font-size:'+tamanho+'px">'+msg+'</p>'
			bootbox.confirm({
				message: msg,
				buttons: {
						confirm: {
								label: 'Sim',
								className: 'btn-primary'
						},
						cancel: {
								label: 'Não',
								className: 'btn-danger'
						}
				},
				callback: function (result) {
					if(result){
						if(callback)
							callback();
					}
				}
			});
		}else
			alert('Erro: retornarMsgConfirm sem msg informada');
	}

	//Limpa um input e passo o foco para ele, o documento recebe o document.getElementeById('id') do input
	that.limparInput = function(id){
    if(!id)
      alert('limparInput sem id informado');
    else{
      var documento = document.getElementById(id);
      if(documento){
        documento.value = '';
        documento.focus();
      }else
        alert(id + 'nao encontrado');
    }
	};
	
  //Ele retorna o timestamp atual, você precisa invocar esta função com uma variavel recebendo ela pois o timestamp sera armazenada na mesma
	that.getTimestamp = function(){
		var timestamp = parseInt((new Date().getTime() /1000).toFixed(0));
		return timestamp;
	};
	
	//pegar o value de um SELECT selecionado, você precisa passar como parametro o id do documento que deseja pegar o value
	that.getSelectValue = function(id){
    if(!id)
      alert('getSelectValue sem id informado');
    else{
      var select = document.getElementById(id);
      if(select){
        return select.options[select.selectedIndex].value;
      }else
        alert(id + 'nao encontrdo');
    }
	};

	//Você passa uma class para o nomeClass que deseja adicionar em algo ou remover, no idDocumento vc informa o id do elemento que deseja adicionar ou remover a Class, na condicao você passa true ou false sendo true para adicionar e false para remover, e o inverso no eliminar, ambos os id podem ser unicos ou um array de id's.
	that.addRemoveClass = function(nomeClass,idDocumento,condicao) {
		if(nomeClass){
			if(idDocumento.constructor === Array){
				for(var i = idDocumento.length -1; i >= 0 ; i--){
					var documento = document.getElementById(idDocumento[i]) ? document.getElementById(idDocumento[i]) : '';
					if(documento){
						if(!condicao){
							if(documento.classList.contains(nomeClass));
								documento.classList.remove(nomeClass);
						}else{
							if(!documento.classList.contains(nomeClass));
								documento.classList.add(nomeClass);
						}
					}
				}		
			}else{
				var documento = document.getElementById(idDocumento) ? document.getElementById(idDocumento) : '';
				if(documento)
					if(!condicao){
						if(documento.classList.contains(nomeClass));
							documento.classList.remove(nomeClass);
					}else{
						if(!documento.classList.contains(nomeClass));
							documento.classList.add(nomeClass);	
					}	
			}
		}else
      alert('addRemoveClass sem nome da Class informado');
	};
	
	that.delay = function(callback,time){
		time = parseInt(time) ? parseInt(time) : 1000;
		if(typeof time === "number"){
			setTimeout(
			function(){
				callback()
			}, time);
		}	
	};
	
	//auxilia a validação de email 
that.auxConfimEmail = function(parametro,email) {
    var teste = email.match(parametro);
    if(teste === null){
      return 1;
    }else{
      return 0;
    }
  };
  
//valida se o email é verdadeiro
  that.confirmEmail = function(email) {
		email = email.trim();
		var erros = 0;

		erros += that.auxConfimEmail(/@/, email);
		erros += that.auxConfimEmail(/.com/, email);

		if(erros > 0){
			return false;
		}else{
			return email;
		}
  };
	
	// Valida se o navegador suporta gravacao de cache local
	that.suportaStorage = function(){
		if (typeof(Storage) !== "undefined") 
    	return true;
		else
			return false;
	};
	
	
	//grava alguma informacao no cache do navegador, o primeiro parametro e o nome do banco que ele vai criar e o segundo o valor que ira inserir
	that.setItem = function(banco,valor){
		var result = that.suportaStorage();
		if(result)
			localStorage.setItem(banco, valor);
	};
	
	// Remove um objeto do cache do navegador
	that.removeItem = function(banco){
		var result = that.suportaStorage();
		if(result)
			localStorage.removeItem(banco);	
	};
	
	//Pega o objeto item do cache do navegador
	that.getItem = function(banco){
		var result = that.suportaStorage();
		if(result)
			return localStorage.getItem(banco) ? localStorage.getItem(banco) : '';	
	};
	
  //pegar o valor do radio checado
  that.getRadioValor = function (name){
		if(name){
			var rads = document.getElementsByName(name);
			if(rads){
				for(var i = 0; i < rads.length; i++){
					if(rads[i].checked){
						return rads[i].value;
					}
				}
			}else
				alert(rads + 'nao encontrado');
    }else
			alert('getRadioValor sem name informado');
  };
	
	//Recebe 2 parametros, o primeiro é oque o sistema vai exibir, e o segundo oque ele vai esconder. Você pode informar tanto um array ou objeto de parametros, ou um unico parametro
	that.monoPage = function(param1,param2) {
		// Se o param2 exisitir, ele vai verificar se é um objeto ou array e percorrelo, caso contrario ira somente exibir o documento com o id informado
    if(param1 == 'object'){
      for(var i = param1.length -1; i >= 0 ; i--){
        if(document.getElementById(param1[i]))
          document.getElementById(param1[i]).style.display = 'block';
        else
          alert(param1 + ' nao encontrado');
      }
    }else if(param1.constructor === Array){
      for(var i = param1.length -1; i >= 0 ; i--){
        if(document.getElementById(param1[i]))
          document.getElementById(param1[i]).style.display = 'block';
        else
           alert(param1 + ' nao encontrado');
      }
		}else{
      if(document.getElementById(param1))
			  document.getElementById(param1).style.display = 'block';
      else
        alert(param1 + ' nao encontrado');
    }
    
    // Se o param2 exisitir, ele vai verificar se é um objeto ou array e percorrelo, caso contrario ira somente esconder o documento com o id informado
		if(param2){
			if(param2 == 'object'){
				for(var i = param2.length -1; i >= 0 ; i--){
          if(document.getElementById(param2[i]))
					  document.getElementById(param2[i]).style.display = 'none';
          else
            alert(param2 + ' nao encontrado');
        }
			}else if(param2.constructor === Array){
				for(var i = param2.length -1; i >= 0 ; i--){
          if(document.getElementById(param2[i]))
					  document.getElementById(param2[i]).style.display = 'none';
          else
             alert(param2 + ' nao encontrado');
        }
			}else{
        if(document.getElementById(param2))
				  document.getElementById(param2).style.display = 'none';
        else
          alert(param2 + ' nao encontrado');
      }  
		}
	};
	
  /*
  var divs = document.querySelectorAll('#paicasas > [id^=casa]');
    for (var i = 0; i < divs.length; i++) {
        divs[i].style.backgroundColor = "#FFF";
    }
  */
  
  
	//Preenche um select, passar um array com os dados a serem preenchidos e o id do documento select
	that.preencherSelect = function(dados,id){
		if((id) && (dados)){
			var select = document.getElementById(id);
				if(select){
					while ( select.childNodes.length >= 1 )
						select.removeChild(select.firstChild);     

					for(var i in dados){
						newOption = document.createElement('option');
						newOption.value=dados[i].codRaca;
						newOption.text=dados[i].nome;
						select.appendChild(newOption);
					}
				}else
					alert('prencherSelect não encontrou o documento');
		}else
			alert('preencherSelect sem os parametros necessarios para funcionar');
	};
	

  // funcao para jogar dados, passar como parametro a quantidade de lados do dado, caso queira trabalhar somente com numeros inteiros passar o segundo parametro como true
  that.jogarDados = function(ladoDado,float){
		ladoDado = ladoDado ? ladoDado : 1;
    var resultado = (Math.random() * ladoDado);
    if(!float){
      return  parseFloat(Math.floor(resultado) + 1);
    }else{
      return parseFloat(resultado.toFixed(2));
    }
  };
  
	//Valida se um input tem caracteres especiais ou espaços, so passar o value do input como parametro
	that.validarCaracter = function(campo) {
	 //se não desejar números é só remover da regex abaixo
	 var regex = '[^a-zA-Z]+';
	 if(campo.match(regex)) {
				return false;
		}else{
			if(!isNaN(campo))
				return false;
		 	else
				return true;			
		}
	}
	
	that.init = function(){
		return that;
	}
	
	return that.init();
};

window.addEventListener('load', function(){
	school = schoolObj('GET');
	
	z = function(id, event, action){
		if(document.getElementById(id)){
			if(event){
				if(action)
					return document.getElementById(id).addEventListener(action, event);
				else
					return document.getElementById(id).addEventListener('click', event);
			}else
				return document.getElementById(id);	
		}else
			alert('id ' +id + ' não encontrado!');
	}
	
	
});

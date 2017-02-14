<?php
	session_start();
	$sessao = $_SESSION['codUsuario'] ? $_SESSION['codUsuario'] : false;
	if(!$sessao)
		header('location: index.html');
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="GameSchool">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projetos Game School</title>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
		<script src="js/bootbox.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
  </head>
  
  <body class="body">
		
    <div class="container">
      <header>
        <div class="row"> 
          <a class="navbar-brand" href="#">Game School Project</a>
        </div>  
      </header>    
    
	     <div class="row">
        <div class="col-md-12">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#vw_novo" data-toggle="tab" id="btn_vwNovo">Novo Job</a> <!-- o id no href é para fazer a troca de abas -->
            </li>
            <li>
              <a href="#vw_pendente" data-toggle="tab" id="btn_vwPendente">Pendentes</a>
            </li>
						<li>
              <a href="#vw_aprovados" data-toggle="tab" id="btn_vwAprovados">Aprovados</a>
            </li>
						<li>
              <a href="#vw_andamento" data-toggle="tab" id="btn_vwAndamento">Em andamento</a>
            </li>
						<li>
              <a href="#vw_concluidos" data-toggle="tab" id="btn_vwConcluidos">Concluidos</a>
            </li>
						<li>
              <a href="#vw_cancelados" data-toggle="tab" id="btn_vwCancelados">Cancelados</a>
            </li>
						<li>
              <a href="#vw_logoff" data-toggle="tab" id="btn_vwLogoff">Logoff</a>
            </li>
          </ul>
        </div>
      </div>
			<select id="usuarios" class="form-control"></select>
        <div class="tab-content">
          <div class="tab-pane active" id="vw_novo">
            <div class="col-md-12">
              <h1><label for="nomeJob">Novo Job</label></h1>
              <form class="form">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Nome do novo Job" id="nomeJob">
                </div>
                <button type="button" class="btn btn-primary" id="criar">Criar</button>  
              </form>
              <span id="result"></span>
            </div>
          </div> <!-- Fim do Criar -->

          <div class="tab-pane" id="vw_pendente">
            <div class="row">
              <div class="col-md-12 col-xs-12">
                <table id="table" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Descricao</th>
                      <th>Data</th>
                      <th>Cancelar / Aprovar</th>
                    </tr>
                  </thead>
                  <tbody id="dadosPendente"></tbody>
                </table>
              </div>
            </div>
          </div> <!-- Fim do Pendente -->
					
					<div class="tab-pane" id="vw_aprovados">
            <div class="row">
              <div class="col-md-12 col-xs-12">
                <table id="table" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Descricao</th>
                      <th>Data</th>
                      <th>Usuario</th>
                      <th>Estornar / Avancar</th>
                    </tr>
                  </thead>
                  <tbody id="dadosAprovados"></tbody>
                </table>
              </div>
            </div>
          </div> <!-- Fim do Aprovados -->
					
					<div class="tab-pane" id="vw_andamento">
            <div class="row">
              <div class="col-md-12 col-xs-12">
                <table id="table" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Descricao</th>
                      <th>Data</th>
                      <th>Usuario</th>
                      <th>Estornar / Avancar</th>
                    </tr>
                  </thead>
                  <tbody id="dadosAndamento"></tbody>
                </table>
              </div>
            </div>
          </div> <!-- Fim do Andamento -->
					
					<div class="tab-pane" id="vw_concluidos">
            <div class="row">
              <div class="col-md-12 col-xs-12">
                <table id="table" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Descricao</th>
                      <th>Data</th>
                      <th>Usuario</th>
                      <th>Estornar / Avancar</th>
                    </tr>
                  </thead>
                  <tbody id="dadosConcluidos"></tbody>
                </table>
              </div>
            </div>
          </div> <!-- Fim do Concluidos -->
					
					<div class="tab-pane" id="vw_cancelados">
            <div class="row">
              <div class="col-md-12">
                <table id="table" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Descricao</th>
                      <th>Data</th>
                      <th>Estornar</th>
                    </tr>
                  </thead>
                  <tbody id="dadosCancelados"></tbody>
                </table>
              </div>
            </div>
          </div> <!-- Fim do Cancelados -->
					
					<div class="tab-pane" id="vw_logoff">
            <div class="row">
              <div class="col-md-12 col-xs-12">
                <button type="button" class="btn btn-warning" id="logoff">Logoff</button>
              </div>
            </div>
          </div> <!-- Fim do Logoff -->
        </div> 
      
    </div>
		
		<script src="js/school.js"></script>
    <script src="js/scrum.js"></script>
  </body>
</html>
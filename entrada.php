<?php
  session_start();
  require("config.php");
  // o usuário está logado?
  if(!isset($_SESSION["id_usuario_logado"])){
    header("Location: login.php");
    exit;
  }
  
  // id da entrada a ser visualizada
  $id_entrada = (int)(trim($_GET["entrada"]));
  $result = mysqli_query($conexao, "select id, num_nota, fornecedor, valor_nota, desconto, acrescimo, (valor_nota - desconto + acrescimo) as total_nota,
    quant_produtos, quant_itens, info, DATE_FORMAT(data_emissao, '%d/%m/%Y') as data_emissao,
	DATE_FORMAT(data_entrada, '%d/%m/%Y') as data_entrada,
    DATE_FORMAT(data_cadastro, '%d/%m/%Y - %H:%i') as data_cadastro, usuario_cadastro from entradas WHERE id = '$id_entrada'");
  if($result){
	while($detalhes = mysqli_fetch_array($result)){
	   $info = utf8_encode($detalhes["info"]);
	   $num_nota = utf8_encode($detalhes["num_nota"]);
	   $valor_nota = utf8_encode($detalhes["valor_nota"]);
	   $descontos = utf8_encode($detalhes["desconto"]);
	   $acrescimos = utf8_encode($detalhes["acrescimo"]);
	   $total_nota = utf8_encode($detalhes["total_nota"]);
	   $quant_produtos = utf8_encode($detalhes["quant_produtos"]);
	   $quant_itens = utf8_encode($detalhes["quant_itens"]);
	   $data_emissao = utf8_encode($detalhes["data_emissao"]);
	   $data_entrada = utf8_encode($detalhes["data_entrada"]);
	   $fornecedor = utf8_encode($detalhes["fornecedor"]);
	   $data_cadastro = $detalhes["data_cadastro"];
	   $usuario_cadastro = $detalhes["usuario_cadastro"];
	}
  }
  
  // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' acessou a entrada ' . sprintf("%05d", $id_entrada) . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title><?= $titulo_aplicacao ?> - Detalhes da Entrada <?= sprintf("%05d", $id_entrada) ?></title>
  <link rel="stylesheet" href="bootstrap-icons-1.5.0/font/bootstrap-icons.css" />
  <link href="css/styles.css?<?= date('Y-m-d_H:i:s'); ?>" rel="stylesheet" />
  <link rel="stylesheet" href="css/classic.css">
  <link rel="stylesheet" href="css/classic.date.css">
  <link rel="stylesheet" type="text/css" href="DataTables-1.10.25/css/jquery.dataTables.css"/>
  
  <link rel="stylesheet" href="Choises/styles/choices.min.css" />
  <script src="Choises/scripts/choices.min.js"></script>
  
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery.mask.min.js"></script>
  <script src="font-awesome/js/all.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="DataTables-1.10.25/js/jquery.dataTables.js?<?= date('Y-m-d_H:i:s'); ?>"></script>
  
  <style type="text/css">
    a{text-decoration:none} 
    .has-search .form-control {
    padding-left: 2.375rem;
    }

   .has-search .form-control-feedback {
    position: absolute;
    z-index: 2;
    display: block;
    width: 2.375rem;
    height: 2.375rem;
    line-height: 2.375rem;
    text-align: center;
    pointer-events: none;
    color: #aaa;
}

.blue-text {
    color: #00BCD4
}

.form-control-label {
    margin-bottom: 0
}

input,
textarea,
button {
    padding: 8px 15px;
    border-radius: 5px !important;
    margin: 5px 0px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    font-size: 18px !important;
    font-weight: 300
}

input:focus,
textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #00BCD4;
    outline-width: 0;
    font-weight: 400
}

.btn-block {
    text-transform: uppercase;
    font-size: 15px !important;
    font-weight: 400;
    height: 43px;
    cursor: pointer
}

.btn-block:hover {
    color: #fff !important
}

button:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    outline-width: 0
}

.image_area {
		  position: relative;
		}

		img {
		  	display: block;
		  	max-width: 100%;
		}

		.preview {
  			overflow: hidden;
  			width: 160px; 
  			height: 160px;
  			margin: 10px;
  			border: 1px solid red;
		}

		.modal-lg{
  			max-width: 1000px !important;
		}

		.overlay {
		  position: absolute;
		  bottom: 10px;
		  left: 0;
		  right: 0;
		  background-color: rgba(255, 255, 255, 0.5);
		  overflow: hidden;
		  height: 0;
		  transition: .5s ease;
		  width: 100%;
		}

		.image_area:hover .overlay {
		  height: 50%;
		  cursor: pointer;
		}

		.text {
		  color: #333;
		  font-size: 20px;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  -webkit-transform: translate(-50%, -50%);
		  -ms-transform: translate(-50%, -50%);
		  transform: translate(-50%, -50%);
		  text-align: center;
		}
  </style>
</head>

<body class="sb-nav-fixed">

<?php require("cima.php"); ?>

<div id="layoutSidenav">
            
            <?php require("menu_lado.php"); ?>
            
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4"><i class="bi-clipboard-plus"></i> Entrada <?= sprintf("%05d", $id_entrada) ?></h1>
                        
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">P&aacute;gina Inicial</a></li>
                            <li class="breadcrumb-item active">Detalhes da Entrada</li>
                        </ol>
                        
                        <nav>
                           <div class="nav nav-tabs" id="nav-tab" role="tablist">
                              <a class="nav-link <?= (!isset($_GET["acrescentar_itens"]) ? 'active' : '') ?>" id="nav-entrada-tab" data-bs-toggle="tab" href="#nav-entrada" 
                              role="tab" aria-controls="nav-entrada" aria-selected="true">Dados da Entrada</a>
                              <a class="nav-link <?= (isset($_GET["acrescentar_itens"]) ? 'active' : '') ?>" id="nav-produtos-tab" data-bs-toggle="tab" href="#nav-produtos" role="tab" 
                              aria-controls="nav-produtos" aria-selected="false">Produtos da Entrada</a>
                           </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                           <div class="tab-pane fade <?= (!isset($_GET["acrescentar_itens"]) ? 'active show' : '') ?>" id="nav-entrada" role="tabpanel" aria-labelledby="nav-entrada-tab">
    
                            <!-- TAB ENTRADAS //-->
                            <?php if(isset($_GET["sucesso_atualizar"])){ ?>
						
                        <div style="margin-top: 15px" class="alert alert-success" role="alert">
                          A entrada no estoque foi atualizada com sucesso.
                         </div>
                         
                        <?php } ?>
						
                        <div class="row" <?= (!isset($_GET["sucesso_atualizar"]) ? 'style="padding-top: 15px"' : '') ?>>
                            <div class="col-xl-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="bi-clipboard-data"></i>
                                        Dados da Entrada <?= sprintf("%05d", $id_entrada) ?>
                                    </div>
                                    <div class="card-body">
                                    
                                    <form id="cadastro_entrada" nome="cadastro_entrada" action="atualizar_entrada_action.php" method="post" class="form-card">
                                    
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                          <label style="padding-left: 5px" class="form-control-label">N&uacute;mero Nota<span class="text-danger"> *</span></label>
                                         <input required type="text" value="<?= $num_nota ?>" id="num_nota" name="num_nota" placeholder="">
                                       </div>
                                    
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                       <label style="padding-left: 5px" class="form-control-label">Fornecedor<span class="text-danger"> *</span></label>
                                          <select name="fornecedor" id="fornecedor" style="margin-bottom: 10px; margin-top: 10px" class="form-select">
                                             <?php
											   $result = mysqli_query($conexao, "select * from fornecedores order by fantasia");
                                               if($result){
	                                              while($detalhes = mysqli_fetch_array($result)){
	                                                 if($fornecedor == $detalhes["id"]){
													    echo '<option selected value="' . $detalhes["id"] . '">' . utf8_encode($detalhes["fantasia"]) . '</option>';
													 }
													 else{
														echo '<option value="' . $detalhes["id"] . '">' . utf8_encode($detalhes["fantasia"]) . '</option>'; 
													 }
	                                              }
                                               }
											 ?>
                                          </select>
                                       </div>
                                    </div>
                                       
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                         <label style="padding-left: 5px" class="form-control-label">Data de Emiss&atilde;o<span class="text-danger"> *</span></label>
                                         <input type="text" required class="form-control" value="<?= $data_emissao ?>" name="data_emissao" id="data_emissao" placeholder="Escolha a data">
                                       </div>
                                    
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                         <label style="padding-left: 5px" class="form-control-label">Data de Entrada<span class="text-danger"> *</span></label>
                                         <input type="text" required class="form-control" value="<?= $data_entrada ?>" name="data_entrada" id="data_entrada" placeholder="Escolha a data">
                                       </div>
                                    </div>   
                                       
                                   
                                       
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-12 flex-column d-flex">
                                          <label style="padding-left: 5px" class="form-control-label">Informa&ccedil;&otilde;es Adicionais<span class="text-danger"> *</span></label>
                                          <textarea required class="form-control" name="info" id="info" rows="4"><?= $info ?></textarea>
                                       </div>
                                    </div>
                                    
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                         <label style="padding-left: 5px" class="form-control-label">Data de Cadastro<span class="text-danger"> *</span></label>
                                         <input required type="text" disabled="disabled" id="data_cadastro" value="<?= $data_cadastro ?>" name="data_cadastro" placeholder="">
                                       </div>
                                       
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                           <label style="padding-left: 5px" class="form-control-label">Usu&aacute;rio Cadastro<span class="text-danger"> *</span></label>
                                         <input required type="text" disabled="disabled" id="usuario_cadastro" value="<?= obterNomeUsuarioId($usuario_cadastro) ?>" name="usuario_cadastro" placeholder="">
                                       </div>
                                    </div>
                                    
                                    <div class="row justify-content-end">
                                       <div class="form-group col-12 flex-column d-flex">
                                          <div class="btn-group" role="group">
                                            
                                            <?php if(Permissao::podeAlterar($_SESSION["nivel_acesso"])){ ?>
                                              <button type="submit" name="gravar" style="margin-right: 8px; width: 180px" 
                                              class="btn-secondary">Atualizar Entrada</button>
                                            <?php } ?>
                                            
                                            <?php if(Permissao::podeExcluir($_SESSION["nivel_acesso"])){ ?>
                                              <button type="button" onclick="excluir_registro('<?= $id_entrada ?>', '<?= sprintf("%05d", $id_entrada) ?>')" 
                                              name="excluir" style="width: 180px" class="btn-secondary">Excluir Entrada</button>
                                            <?php } ?>
                                          
                                          </div>
                                          <input name="id_entrada" type="hidden" id="id_entrada" value="<?= $id_entrada ?>">
                                          <input name="valor_nota_temp" type="hidden" id="valor_nota_temp" value="0,00">
                                       </div>
                                    </div>
                                    
                                    </form>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="bi-lightbulb"></i>
                                        Dica do Sistema
                                    </div>
                                    <div class="card-body">
                                    
                                    Preencha os dados da nota, salve e adicione produtos. O sistema preencher&aacute; automaticamente o restante das informa&ccedil;&otilde;es.
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                            <!-- FIM TAB ENTRADAS //-->   
                           
                           </div>
                           
                           <div class="tab-pane fade <?= (isset($_GET["acrescentar_itens"]) ? 'active show' : '') ?>" id="nav-produtos" role="tabpanel" aria-labelledby="nav-produtos-tab">
    
                              <div class="row" style="padding-top: 15px">
                                <div class="col-xl-12">
                                   <div class="card mb-8">
                                     <div class="card-header"><i class="bi-clipboard-data"></i> Produtos da Entrada <?= sprintf("%05d", $id_entrada) ?></div>
                                        <div class="card-body">
    
                            <table id="tabela_itens" class="display" style="width:100%">
                              <thead>
                                <tr>
                                  <th></th>
                                  <th>Ref</th>
                                  <th>Nome</th>
                                  <th>Un</th>
                                  <th>Unit&aacute;rio</th>
                                  <th>Quant</th>
                                  <th>Total</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
            
                              <?php
								$result = mysqli_query($conexao, "select e.id, e.id_produto, p.referencia, p.nome, p.unidade, p.foto,
								  e.valor_unitario, e.quantidade from produtos p, itens_entrada e WHERE e.id_entrada = '$id_entrada'
								  AND p.id = e.id_produto GROUP BY e.id_produto order by p.nome");
                                if($result){
	                               $contador = 0;
								   while($detalhes = mysqli_fetch_array($result)){
	                                  $foto_item_produto = utf8_encode($detalhes["foto"]);
									  if(file_exists($diretorio_fotos_produtos . '/' . $foto_item_produto . '.png')){
		                                  $foto_item_produto = $url_fotos_produtos . '/' . $foto_item_produto . '.png';
	                                  }
	                                  else{
		                                  $foto_item_produto = $url_base_aplicacao . '/imagens/produto_sem_foto.jpg'; 
	                                  }              
										
									  $valor_item_unitario = utf8_encode($detalhes["valor_unitario"]);
									  $quant_item_unitario = utf8_encode($detalhes["quantidade"]);	
															 
									  echo '<tr>';
									  echo '<td></td>';
									  echo '<td>' . utf8_encode($detalhes["referencia"]) . '</td>';
									  echo '<td>' . utf8_encode($detalhes["nome"]) . '</td>';
									  echo '<td>' . utf8_encode($detalhes["unidade"]) . '</td>';
									  echo '<td>' . number_format($valor_item_unitario, 2, ',', '.') . '</td>';
									  echo '<td>' . $quant_item_unitario . '</td>';
									  echo '<td>' . number_format($valor_item_unitario * $quant_item_unitario, 2, ',', '.') . '</td>';
									  echo '<td><a class="btn btn-secondary" role="button" title="Clique para excluir" 
									     href="javascript:excluir_item_entrada(\'' . utf8_encode($detalhes["referencia"]) . '\', \'' 
										 . $detalhes["id"] . '\', \'' 
										 . $detalhes["id_produto"] . '\', \'' . $id_entrada . '\', \'' . $quant_item_unitario . '\', \'' . 
										 $valor_item_unitario . '\', \'' . ($valor_item_unitario * $quant_item_unitario) . '\')">Excluir</a></td>';
									  echo '</tr>';  
	                              }
                                }
							  ?> 
            
                              </tbody>
                            </table>  
    
                          </div>
                          </div>
                          </div>
                           </div>
                           
                           <?php if(Permissao::podeCadastrar($_SESSION["nivel_acesso"])){ ?>
                           <div class="row">
                                <div class="col-xl-12">
                                   <div class="card mb-8">
                                     <div class="card-header"><i class="bi-bag-plus"></i> Adicionar Novo Produto</div>
                                        <div class="card-body">
                                        
                                           <form id="form_item" nome="form_item"> 
                                             
                                             <div class="row justify-content-between text-left">
                                                <div class="form-group col-sm-6 flex-column d-flex">
                                                   <label style="padding-left: 5px" class="form-control-label">
                                                     Nome Produto ou Refer&ecirc;ncia<span class="text-danger"> *</span></label>
                                                   
                                                   <select id="produto" name="produto" class="selectpicker form-control border-1">
                                                      <?php
													    $result = mysqli_query($conexao, "select id, referencia, nome, unidade, foto from produtos
														  order by nome");
                                                        if($result){
	                                                       while($detalhes = mysqli_fetch_array($result)){
	                                                         $foto_item_produto = utf8_encode($detalhes["foto"]);
															 if(file_exists($diretorio_fotos_produtos . '/' . $foto_item_produto . '.png')){
		                                                        $foto_item_produto = $url_fotos_produtos . '/' . $foto_item_produto . '.png';
	                                                         }
	                                                         else{
		                                                        $foto_item_produto = $url_base_aplicacao . '/imagens/produto_sem_foto.jpg'; 
	                                                         }              
															 
															 echo '<option value="' . $detalhes["id"] . '##%%##' . utf8_encode($detalhes["referencia"]) .
															   '##%%##' . utf8_encode($detalhes["nome"]) . '##%%##' . 
															   utf8_encode($detalhes["unidade"]) . '##%%##' . 
															   $foto_item_produto . '">' . utf8_encode($detalhes["nome"]) . ' - Ref: ' . 
															   utf8_encode($detalhes["referencia"]) . '</option>';
	                                                       }
                                                        }
											          ?> 
                                                   </select>
                                                   
                                                </div>
                                                <div class="form-group col-sm-2 flex-column d-flex">
                                                   <label style="padding-left: 5px" class="form-control-label">
                                                     Pre&ccedil;o Unit&aacute;rio<span class="text-danger"> *</span></label>
                                                   <input type="text" required class="form-control" style="text-align: 
                                                     right;" onKeyUp="atualizar_preco_total()" onBlur="formatarCampo(this)" name="preco_unitario" id="preco_unitario">
                                                </div>
                                                <div class="form-group col-sm-2 flex-column d-flex">
                                                   <label style="padding-left: 5px" class="form-control-label">
                                                     Quantidade<span class="text-danger"> *</span></label>
                                                   <input required type="number" min="1" class="form-control" style="text-align: 
                                                     right;" onKeyUp="atualizar_preco_total()" name="quantidade" value="1" id="quantidade">
                                                </div>
                                                <div class="form-group col-sm-2 flex-column d-flex">
                                                   <label style="padding-left: 5px" class="form-control-label">
                                                     Pre&ccedil;o Total<span class="text-danger"> *</span></label>
                                                   <input type="text" required disabled value="0,00" class="form-control" style="text-align: 
                                                     right;" name="preco_total" id="preco_total">
                                                </div>
                                                
                                             </div>
                                             
                                             <div class="row justify-content-between text-left">
                                                <div class="form-group col-sm-6 flex-column d-flex">
                                                   <div class="form-group col-sm-2 flex-column d-flex">
                                                   <button type="button" onclick="adicionarProduto()" 
                                                      name="btn_adicionar" style="width: 180px" class="btn-secondary">Adicionar Produto</button>
                                                   </div>
                                                </div>
                                             </div>
                                             
                                           </form>
                                        
                                        </div>
                                   </div>
                                 </div> 
                           </div>     
                           <?php } ?>
                           
                           
                           </div>
                        </div>
                        
                        
                    </div>
                </main>
                
                <?php require("rodape.php"); ?>
                
            </div>
        
        
        <div id="janela_excluir_entrada" class="modal fade" role="dialog" style="width:100%">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content" >
            <div class="modal-header">Excluir</div>
            <div class="modal-body"><span id="msg_excluir"></span></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="confirmar_exclusao()">Confirmar</button>
                <button type="button" class="btn btn-secondary" onclick="fechar_exclusao()" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
    </div>
    
    <div id="janela_aviso_duplicado" class="modal fade" role="dialog" style="width:100%">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content" >
            <div class="modal-header">Erro!</div>
            <div class="modal-body">Este item ja existe para esta entrada no estoque</span></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="fechar_item_duplicado()" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
    </div>
    
        
        <div id="janela_excluir_item_entrada" class="modal fade" role="dialog" style="width:100%">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content" >
            <div class="modal-header">Excluir</div>
            <div class="modal-body"><span id="msg_excluir_item_entrada"></span></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="confirmar_exclusao_item_entrada()">Confirmar</button>
                <button type="button" class="btn btn-secondary" onclick="fechar_exclusao_item_entrada()" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
    </div>
        
        <script src="bootstrap-5.0.2/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/picker.js"></script>
        <script src="js/picker.date.js"></script>
        
        <script type="text/javascript">
		  var valor_nota = <?= $valor_nota ?>;
		  var total_nota = <?= $total_nota ?>;
		  var total_produtos = <?= $quant_produtos ?>;
		  var total_itens = <?= $quant_itens ?>;
		  
		  $(function() {
             $('#data_emissao').pickadate({
			    today: 'Hoje',
                clear: 'Limpar',
                close: 'Cancelar',
				weekdaysShort: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
                showMonthsShort: true,
				format: 'dd/mm/yyyy',
				formatSubmit: 'yyyy/mm/dd',
                hiddenName: true,
				labelMonthNext: 'Pr&oacute;ximo M&ecirc;s',
                labelMonthPrev: 'M&ecirc;s Anterior',
                labelMonthSelect: 'Selecione o M&ecirc;s',
                labelYearSelect: 'Selecione um Ano',
				monthsFull: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                weekdaysFull: ['Domingo', 'Segunda', 'Ter&ccedil;a', 'Quarta', 'Quinta', 'Sexta', 'S&aacute;bado'] 
			 });
			 
			 $('#data_entrada').pickadate({
			    today: 'Hoje',
                clear: 'Limpar',
                close: 'Cancelar',
				weekdaysShort: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
                showMonthsShort: true,
				format: 'dd/mm/yyyy',
				formatSubmit: 'yyyy/mm/dd',
                hiddenName: true,
				labelMonthNext: 'Pr&oacute;ximo M&ecirc;s',
                labelMonthPrev: 'M&ecirc;s Anterior',
                labelMonthSelect: 'Selecione o M&ecirc;s',
                labelYearSelect: 'Selecione um Ano',
				monthsFull: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                weekdaysFull: ['Domingo', 'Segunda', 'Ter&ccedil;a', 'Quarta', 'Quinta', 'Sexta', 'S&aacute;bado'] 
			 });
          });
		  
		  // trata a formata�ao dos campos
	      $('#acrescimos').mask('000.000.000.000.000,00', {reverse: true});
		  $('#descontos').mask('000.000.000.000.000,00', {reverse: true});
		  $('#preco_unitario').mask('000.000.000.000.000,00', {reverse: true});
	      // fim formata�ao dos campos
		  
		  
		  
		  $(document).ready(function() {
            var t = $('#tabela_itens').DataTable( {
              "columnDefs": [ {
                 "searchable": false,
                 "orderable": false,
                 "targets": 0
              },
			  
			  {"targets": 5,
                "className": 'dt-body-right'},
			  {"targets": 6,
                "className": 'dt-body-right'},
			  {"targets": 7,
                "className": 'dt-body-right'},
			  { "width": "60%", "targets": 3}
			  
			   ],
                 "order": [[ 1, 'asc' ]]
              } );
 
            t.on('fnCreatedRow', function( nRow, aData, iDataIndex ) {
                 $(nRow).attr('id', aData[2]);
            });
 
            t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i+1;
            } );
         } ).draw();
         } );
		
        /* ==============================================
        CUSTOM SELECT
        ============================================== */
        const sorting = document.querySelector('.selectpicker');
        const commentSorting = document.querySelector('.selectpicker');
        const sortingchoices = new Choices(sorting, {
          placeholder: false,
          itemSelectText: ''
        });   


        // Trick to apply your custom classes to generated dropdown menu
        let sortingClass = sorting.getAttribute('class');
        window.onload= function () {
           sorting.parentElement.setAttribute('class', sortingClass); 
        }
        
		// fun�ao que atualiza o pre�o total do item sendo inserido
		function atualizar_preco_total(){
		  var preco_unitario = $('#preco_unitario').val();
		  preco_unitario = preco_unitario.replace(".", "");
		  preco_unitario = preco_unitario.replace(",", ".");
		  var quantidade = $('#quantidade').val();
		  var preco_total = preco_unitario * quantidade;
		  if(preco_unitario.length > 0){
			$('#preco_total').val(preco_total.toLocaleString('pt-br', {minimumFractionDigits: 2}));  
		  }	
		}
		// fim atulaiza o pre�o total do item sendo inserido
		
		// fun�ao que atualiza os dados da nota
		function atualizar_dados_nota(){
		  var acrescimos = $('#acrescimos').val();
		  var descontos = $('#descontos').val();
		  
		  if(acrescimos.trim() == ""){
			return;  
		  }
		  if(descontos.trim() == ""){
			return;  
		  }
		  
		  acrescimos = acrescimos.replace(".", "");
		  acrescimos = acrescimos.replace(",", ".");
		  descontos = descontos.replace(".", "");
		  descontos = descontos.replace(",", ".");
		  total_nota = parseFloat(valor_nota) + parseFloat(acrescimos) - parseFloat(descontos);
		  $('#total_nota').val(total_nota.toLocaleString('pt-br', {minimumFractionDigits: 2}));	
		}
		// fim atualizar dados da nota
		
		// fun�ao que permite adicionar um novo produto a esta entrada
		function adicionarProduto(){
		  if($('#preco_unitario').val().trim() == ""){
			 $('#preco_unitario').focus();
			 return;  
		  }
		  if($('#quantidade').val().trim() == ""){
			 $('#quantidade').focus();
			 return;  
		  }
			
		  var info_produto = $('#produto').val();
		  var dados_produto = info_produto.split('##%%##');
		  var id_produto = dados_produto[0];
		  var referencia_produto = dados_produto[1];
		  var nome_produto = dados_produto[2];
		  var unidade_produto = dados_produto[3];
		  var foto_produto = dados_produto[4];
		  var preco_unitario = $('#preco_unitario').val();
		  preco_unitario = preco_unitario.replace(".", "");
		  preco_unitario = preco_unitario.replace(",", ".");
		  var quantidade = $('#quantidade').val();
		  var preco_total = quantidade * preco_unitario;
		  var id_ultimo_item_entrada = 0;
		  
		  // vamos chamar um c�digo Ajax para inserir este item na tabela itens_entrada
		  var dados = {'id_entrada': "<?= $id_entrada ?>", 'id_produto': id_produto,
		    'preco_unitario': preco_unitario, 'quantidade': quantidade}

          $.ajax({
            url: 'inserir_produto_entrada_ajax.php',
            type: 'post',
            dataType: 'xml',
            data: dados,
            success: function(data){
			  $(data).find('mensagem').each(function(){
                id_ultimo_item_entrada = $(this).find('texto').text();
				
				if(id_ultimo_item_entrada == "erro"){
				   item_duplicado();	
				   return;	
				}
				
				var t = $('#tabela_itens').DataTable();
                var proximo_index = t.rows().count();
		        t.row.add(['', '<img width="30px" src="' + foto_produto + '"/>', referencia_produto, 
		          nome_produto, unidade_produto, 
		          preco_unitario.toLocaleString('pt-br', {minimumFractionDigits: 2}), quantidade,
			      preco_total.toLocaleString('pt-br', {minimumFractionDigits: 2}), '<a class="btn btn-secondary" role="button" title="Clique para excluir" href="javascript:excluir_item_entrada(\'' + referencia_produto + '\', ' + id_ultimo_item_entrada + ', ' + id_produto + ', <?= $id_entrada ?>, ' + quantidade + ', ' + preco_unitario + ', ' + preco_total + ')">Excluir</a>']).draw(false);
				  
				// atualiza as informa�oes da entrada
				valor_nota = parseFloat(valor_nota) + parseFloat(preco_total);
				$('#valor_nota').val(valor_nota.toLocaleString('pt-br', {minimumFractionDigits: 2}));
				
				var quant_produtos = $('#quant_produtos').val();
		        var quant_itens = $('#quant_itens').val();
				quant_produtos = (parseInt(quant_produtos) + 1);
				$('#quant_produtos').val(quant_produtos);
				quant_itens = parseInt(quant_itens) + parseInt(quantidade);
				$('#quant_itens').val(quant_itens);
				
				atualizar_dados_nota();
				// fim atualizar as informa�oes da entrada  
				  
			  });	
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
              window.alert('Houve um erro! Erro: ' + errorThrown.toString());
            }
          });
		  // fim chamar um c�digo Ajax para inserir este item na tabela itens_entrada	
	    }
		
		var $modal_excluir_item_entrada = $('#janela_excluir_item_entrada');
        // dados tempor�rios para exclusao do item da entrada
		var referencia_temp;
		var id_item_entrada_temp;
		var id_entrada_temp;
		var id_produto_temp;
		var quantidade_temp;
		var preco_unitario_temp;
		var preco_total_temp;
	   
	    function excluir_item_entrada(referencia, id_item_entrada, id_produto, id_entrada, quantidade, preco_unitario, preco_total){
		  referencia_temp = referencia;
		  id_item_entrada_temp = id_item_entrada;
		  id_entrada_temp = id_entrada;
		  id_produto_temp = id_produto;
		  quantidade_temp = quantidade;
		  preco_unitario_temp = preco_unitario;
		  preco_total_temp = preco_total;
	      $('#msg_excluir_item_entrada').html("Voce deseja mesmo excluir este item?"); 
	      $modal_excluir_item_entrada.modal('show');	
		}
	   
		// fun�ao que permite remover um produto desta entrada
		function confirmar_exclusao_item_entrada(){
		  // vamos chamar um c�digo Ajax para inserir este item na tabela itens_entrada
		  var dados = {'id_item_entrada': id_item_entrada_temp, 'id_produto': id_produto_temp, 'id_entrada': id_entrada_temp, 'quantidade': quantidade_temp, 'preco_unitario': preco_unitario_temp, 'preco_total': preco_total_temp}

          $.ajax({
            url: 'excluir_produto_entrada_ajax.php',
            type: 'post',
            dataType: 'xml',
            data: dados,
            success: function(data){
			  $(data).find('mensagem').each(function(){
                // precisamos remover a linha da DataTable
				var t = $('#tabela_itens').DataTable();
                for(var i = 0; i < t.rows().count(); i++){
				  //window.alert(t.cell(i, 2).data());
				  if(t.cell(i, 2).data() == referencia_temp){
					t.row(i).remove().draw();
					break;  
				  }
				}
				
				valor_nota = parseFloat(valor_nota) - parseFloat(preco_total_temp);
				$('#valor_nota').val(valor_nota.toLocaleString('pt-br', {minimumFractionDigits: 2}));
				
				var quant_produtos = $('#quant_produtos').val();
		        var quant_itens = $('#quant_itens').val();
				quant_produtos = (parseInt(quant_produtos) - 1);
				$('#quant_produtos').val(quant_produtos);
				quant_itens = parseInt(quant_itens) - parseInt(quantidade_temp);
				$('#quant_itens').val(quant_itens);
				
				atualizar_dados_nota();
				$modal_excluir_item_entrada.modal('hide');
				// fim remover linha da DataTable
			  });	
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
              window.alert('Houve um erro! Erro: ' + errorThrown.toString());
            }
          });
		  // fim chamar um c�digo Ajax para remover este item da tabela itens_entrada	
	    }
		
		
		var $modal_excluir = $('#janela_excluir_entrada');
		var id_exlusao = 0;
	   
        function excluir_registro(id, entrada){
	      id_exclusao = id;
	      $('#msg_excluir').html("Voce deseja mesmo excluir a entrada <b>" + entrada.toString().padStart(5, 0) + "</b>?<br><br>Se houver produtos cadastrados nesta entrada, a exclusao sera abortada"); 
	      $modal_excluir.modal('show');    
       }
	   
       function confirmar_exclusao(){
	     var url_volta = 'entradas.php';
         window.location = "excluir_entrada.php?entrada=" + id_exclusao + "&volta=" + url_volta;    
       }
	   
       function fechar_exclusao(){
	      $modal_excluir.modal('hide');
       }
	
	   function fechar_exclusao_item_entrada(){
	      $modal_excluir_item_entrada.modal('hide');
       }
	   	
	   var $modal_duplicado = $('#janela_aviso_duplicado');	
	   function item_duplicado(){
	      $modal_duplicado.modal('show');    
       } 	
	
	   function fechar_item_duplicado(){
	      $modal_duplicado.modal('hide');
       }
	   
	   function formatarCampo(campo){
		   var valor = campo.value;
		   
		   if(!valor.includes(",")){
			  valor = valor + ",00";  
		   }
		   
		   campo.value = valor.toLocaleString('pt-br', {minimumFractionDigits: 2});
	   } 	
		
        </script>
        
    </body>
</html>


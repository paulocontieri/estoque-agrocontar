<?php
  session_start();
  require("config.php");
  // o usuário está logado?
  if(!isset($_SESSION["id_usuario_logado"])){
    header("Location: login.php");
    exit;
  }
  
  // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' acessou a relacao de entradas no estoque.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log
  
  $data_inicial = date('Y-m-d');
  $data_final = date('Y-m-d');
  $filtro_data = "";
  
  // opções de filtro e ordenação
  $ordenacao = 'e.id';
  if(isset($_GET["ordem_por"])){
	$ordenacao = tratar_entrada($_GET["ordem_por"]);
	
	$data_inicial = tratar_entrada($_GET["data_inicial"]);
	$data_final = tratar_entrada($_GET["data_final"]);
	$filtro_data = " DATE(e.data_cadastro) between '" . $data_inicial . "' AND '" . $data_final . "' AND "; 	  
  }
  
  $per_page = 20;
  if(isset($_GET["quant_paginas"])){
	$per_page = tratar_entrada($_GET["quant_paginas"]);  
  }
  
  $ordem_class = 'desc';
  if(isset($_GET["ordem_class"])){
	$ordem_class = tratar_entrada($_GET["ordem_class"]);  
  }
  // fim opções de filhtro e ordenação
  
  if(isset($_GET["pesquisa"])){
	 $pesquisa_entrada = tratar_entrada(utf8_decode(trim($_GET["pesquisa"])));
     $sql_text = ("select e.id, e.num_nota, f.fantasia, e.valor_nota, e.desconto, e.acrescimo, (e.valor_nota - e.desconto + e.acrescimo) as total_nota,
       e.quant_produtos, e.quant_itens, DATE_FORMAT(e.data_emissao, '%d/%m/%Y') as data_emissao, DATE_FORMAT(e.data_entrada, '%d/%m/%Y') as data_entrada,
       DATE_FORMAT(e.data_cadastro, '%d/%m/%Y - %H:%i') as data_cadastro, e.usuario_cadastro from entradas e, fornecedores f WHERE " . $filtro_data . " e.fornecedor = f.id
	   AND (e.num_nota LIKE '%$pesquisa_entrada%' OR f.fantasia LIKE '%$pesquisa_entrada%' OR f.cpf_cnpj LIKE '%$pesquisa_entrada%' OR f.razao_social LIKE '%$pesquisa_entrada%') GROUP BY e.id order by " . $ordenacao . " " . $ordem_class);
	   
     $sql_text2 = ("select e.id from entradas e, fornecedores f WHERE " . $filtro_data . " e.fornecedor = f.id
	   AND (e.num_nota LIKE '%$pesquisa_entrada%' OR f.fantasia LIKE '%$pesquisa_entrada%' OR f.cpf_cnpj LIKE '%$pesquisa_entrada%' OR f.razao_social LIKE '%$pesquisa_entrada%') GROUP BY e.id order by " . $ordenacao . " " . $ordem_class);
  }
  else{
	 $sql_text = ("select e.id, e.num_nota, f.fantasia, e.valor_nota, e.desconto, e.acrescimo, (e.valor_nota - e.desconto + e.acrescimo) as total_nota,
       e.quant_produtos, e.quant_itens, DATE_FORMAT(e.data_emissao, '%d/%m/%Y') as data_emissao, DATE_FORMAT(e.data_entrada, '%d/%m/%Y') as data_entrada,
       DATE_FORMAT(e.data_cadastro, '%d/%m/%Y - %H:%i') as data_cadastro, e.usuario_cadastro from entradas e, fornecedores f  WHERE
	   " . $filtro_data . " e.fornecedor = f.id GROUP BY e.id order by " . $ordenacao . " " . $ordem_class);
     $sql_text2 = ("select e.id from entradas e, fornecedores f WHERE " . $filtro_data . " e.fornecedor = f.id");
	 
	 //echo '<h1>' . $sql_text . '</h1>';  
  }	
	
  $sql_text2 = $sql_text;
  $query2 = mysqli_query($conexao, $sql_text2);
  $num_rows2 = mysqli_num_rows($query2);

  if(isset($_GET["page"])){ 
    $page = (int)$_GET["page"];
  }
  else{
	$page = 1;   
  }
  
  $per_page = 20;
  $pagesize = $per_page;
  
  $query = mysqli_query($conexao, $sql_text); 
  if(!isset($_GET["page"])) $page = 1;
  $prev_page = $page - 1;
  $next_page = $page + 1;
  $query = mysqli_query($conexao, $sql_text);
  $page_start = ($per_page * $page) - $per_page;
  $num_rows = mysqli_num_rows($query);
  if ($num_rows <= $per_page) $num_pages = 1;
  else if (($num_rows % $per_page) == 0) $num_pages = ($num_rows / $per_page);
  else $num_pages = ($num_rows / $per_page) + 1;
  $num_pages = (int) $num_pages; 
  $totoalPages = $num_pages;
  $sql_text = $sql_text . " LIMIT $page_start, $per_page";
  $resultado_paginacao = mysqli_query($conexao, $sql_text);
?>
<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title><?= $titulo_aplicacao ?> - Entradas no Estoque</title>
  <link rel="stylesheet" href="bootstrap-icons-1.5.0/font/bootstrap-icons.css" />
  <link href="css/styles.css?<?= date('Y-m-d_H:i:s'); ?>" rel="stylesheet" />
  <script src="font-awesome/js/all.min.js" crossorigin="anonymous"></script>
  <script src="js/jquery.min.js"></script>
  
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
  </style>
</head>

<body class="sb-nav-fixed">

<?php require("cima.php"); ?>

<div id="layoutSidenav">
            
            <?php require("menu_lado.php"); ?>
            
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        
                        <h1 class="mt-4"><i class="bi-clipboard-plus"></i> Entradas no Estoque</h1>
                        
                        <table width="100%">
                        <tr>
                        <td>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">P&aacute;gina Inicial</a></li>
                            <li class="breadcrumb-item active">Entradas no Estoque</li>
                        </ol>
                        </td>
                        <td align="right"><?= (Permissao::podeCadastrar($_SESSION["nivel_acesso"]) ? '<a href="nova_entrada.php"><i class="bi-clipboard-plus"></i> Nova Entrada</a>' : '') ?></td>
                        </tr>
                        </table>                     
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <table width="100%">
                                <form id="pesquisa_entrada" nome="pesquisa_entrada" action="entradas.php" method="get">
                                <tr>
                                <td nowrap><i class="bi-clipboard-plus"></i> Entradas Cadastradas</td>
                                
                                <td nowrap align="right">
                                
                                <div style="width:230px" class="input-group">
    
    <a title="Opções" class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-markdown"></i>
  </a>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <li><a class="dropdown-item" href="javascript:definir_paginacao()">Pagina&ccedil;&atilde;o e Ordena&ccedil;&atilde;o</a></li>
    <li><a class="dropdown-item" href="entradas.php">Limpar Pesquisa e Filtro</a></li>
  </ul>

                                
    <input type="text" name="pesquisa" id="pesquisa" <?= (isset($_GET["pesquisa"]) ? ' value="' . $pesquisa_entrada . '"' : '') ?> required class="form-control" placeholder="Pesquisar">
    <div class="input-group-append">
      <button class="btn btn-secondary" type="button">
        <i class="fa fa-search"></i>
      </button>
    </div>
  </div>
                                
                                </td>
                                </tr>
                                </form>
                                </table>
                                
                            </div>
                            <div class="card-body">
                            
							<?php
					   if($num_rows2 == 0){
						   echo '<b>Nenhum registro encontrado nesta pesquisa.</b>';
					   }
					   else{
						   echo '<b>' . $num_rows2 . '</b> registros encontrados. Página <b>' . $page . '</b> de 
						      <b>' . ceil($num_rows2 / $pagesize) . '</b>';
					   }
					   
					?>
                    
                    
                    <div style="padding-top: 15px" class="table-responsive text-nowrap">
                    <table class="table">
                       <thead>
                         <tr style="color: #455a64; background-color: #f5f5f5">
                            <th>Ord</th> 
                            <th>Código</th>
                            <th>Fornecedor</th>
                            <th>Quantidade</th>
                            <th>Preço</th>
                            <th>Entrada</th>   
                         </tr>
                       </thead>
                       <tbody>
					   
					   <?php
                         $ordem = 0;
                         if($resultado_paginacao){
                            while($detalhes = mysqli_fetch_array($resultado_paginacao)){
                               $ordem++;
					           $id = $detalhes["id"];
							   $num_nota = utf8_encode($detalhes["num_nota"]);
							   $fornecedor = utf8_encode($detalhes["fantasia"]);
							   $valor_nota = $detalhes["valor_nota"];
							   $total_nota = $detalhes["total_nota"];
							   $desconto = $detalhes["desconto"];
							   $acrescimo = $detalhes["acrescimo"];
							   $data_emissao = $detalhes["data_emissao"]; 
							   $data_entrada = $detalhes["data_entrada"]; 
							   $data_cadastro = $detalhes["data_cadastro"]; 
							
							   if($ordem % 2 == 0){
							      echo '<tr style="background-color: ghostwhite">';
							   }
							   else{
							      echo '<tr style="background-color: white">';	
							   }
							   
							   echo '<td style="vertical-align: middle" nowrap="nowrap" align="left">' . $ordem . '</td>';
							   echo '<td style="vertical-align: middle" nowrap="nowrap" align="left">' . sprintf("%05d", $id) . '</td>';
							   echo '<td style="vertical-align: middle" nowrap="nowrap" align="left">
							      <a title="Clique para mais detalhes" href="entrada.php?entrada=' . $id . '"><img border="0" 
							      width="30px" src="imagens/detalhes.png"/></a> ';
									 
							   if(Permissao::podeExcluir($_SESSION["nivel_acesso"])){
								   echo '<a title="Clique para excluir" href="javascript:excluir_registro(' . $id . ', \'' . 
									 $num_nota . '\')"><img border="0" width="30px" src="imagens/excluir.gif"/></a> ';
							   }
							   echo $fornecedor . '</td>';
							   echo '<td style="vertical-align: middle" nowrap="nowrap" align="left">' . number_format($total_nota, 2, ',', '.') . '</td>';
							   echo '<td style="vertical-align: middle" nowrap="nowrap" align="left">' . number_format($valor_nota, 2, ',', '.') . '</td>';							   
							   echo '<td style="vertical-align: middle" nowrap="nowrap" align="left">' . $data_entrada . '</td>';
							   
							
							echo '</tr>';
							}
			              }
						  
					   ?>
                       
                       </tbody>   
                    </table>
                  </div>
                  
                    
                    
                    <!-- Pagination -->
            <div style="margin-top: 10px">
            <ul class="pagination justify-content-center">
                <?php
				  // opções de filtro e ordenação
				    $pagina_atual = 'entradas.php';
                    if((isset($_GET["ordem_por"])) && (isset($_GET["pesquisa"]))){
	                   $link_paginacao = $pagina_atual . '?pesquisa=' . $_GET["pesquisa"] . '&ordem_class=' . $_GET["ordem_class"] 
					     . '&quant_paginas=' . $_GET["quant_paginas"] . '&ordem_por=' . $_GET["ordem_por"] . '&';  
                    }
                    else if((isset($_GET["ordem_por"])) && (!isset($_GET["pesquisa"]))){
	                   $link_paginacao = $pagina_atual . '?ordem_class=' . $_GET["ordem_class"] . '&quant_paginas=' 
					     . $_GET["quant_paginas"] . '&ordem_por=' . $_GET["ordem_por"] . '&';  
                    }
                    else if((!isset($_GET["ordem_por"])) && (isset($_GET["pesquisa"]))){
	                   $link_paginacao = $pagina_atual . '?pesquisa=' . $_GET["pesquisa"] . '&';  
                    }
                    else{
					   $link_paginacao = $pagina_atual . '?';
					}
				?>
                <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link"
                        href="<?php if($page <= 1){ echo $link_paginacao . 'page=1'; } else { echo $link_paginacao . "page=" . $prev_page; } ?>"><<</a>
                </li>

                <?php for($i = 1; $i <= $totoalPages; $i++ ): ?>
                <li class="page-item <?php if($page == $i) {echo 'active'; } ?>">
                
                    <?php
                      echo '<a class="page-link" href="' . $link_paginacao . 'page=' . $i . '"> ' . $i . ' </a>';
                    ?> 
                </li>
                <?php endfor; ?>

                <li class="page-item <?php if($page >= $totoalPages) { echo 'disabled'; } ?>">
                    <a class="page-link"
                        href="<?php if($page >= $totoalPages){ echo $link_paginacao . 'page=1'; } else {echo $link_paginacao . "page=". $next_page; } ?>">>></a>
                </li>
            </ul> 
            
                            
                            </div>
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
                    
                    <div id="janela_paginacao" class="modal fade" role="dialog" style="width:100%">
            <form name="form_paginacao" action="entradas.php" method="get" id="form_paginacao">
            <div class="modal-dialog" style="width: 60%;">
               <div class="modal-content" >
                 <div class="modal-header">Pagina&ccedil;&atilde;o e Ordena&ccedil;&atilde;o</div>
                 <div class="modal-body">
                 
                 <div class="mb-3">
                 <label style="padding-left: 5px" class="form-control-label">Data Inicial<span class="text-danger"> *</span></label>
                 <input type="date" required class="form-control" value="<?= $data_inicial ?>" name="data_inicial" id="data_inicial" placeholder="Escolha a data">
                 </div>
                 
                 <div class="mb-3">
                 <label style="padding-left: 5px" class="form-control-label">Data Final<span class="text-danger"> *</span></label>
                 <input type="date" required class="form-control" value="<?= $data_final ?>" name="data_final" id="data_final" placeholder="Escolha a data">
                 </div>
                 
                 <div class="mb-3">
                   <label for="quant_paginas" class="form-label">Quantidade por P&aacute;gina</label>
                   <select class="form-select" id="quant_paginas" name="quant_paginas">
                      
                      <?php
					    for($i = 0; $i < count($opcoes_paginacao); $i++){
						   echo '<option value="' . $opcoes_paginacao[$i] . '">' . $opcoes_paginacao[$i] . ' itens por p&aacute;gina</option>';	
						}
					  ?>
                      
                   </select>
                 </div>
                 
                 <div class="mb-3">
                   <label for="ordem_por" class="form-label">Ordenar por</label>
                   <select class="form-select" id="ordem_por" name="ordem_por">
                      
                      <?php
					  
					  // select e.id, e.num_nota, f.fantasia, e.valor_nota, e.desconto, e.acrescimo, (e.valor_nota - e.desconto + e.acrescimo) as total_nota,
                      // e.quant_produtos, e.quant_itens, DATE_FORMAT(e.data_emissao, '%d/%m/%Y') as data_emissao, DATE_FORMAT(e.data_entrada
					  
					    $nomes_campos = array('e.num_nota', 'f.fantasia', 'f.cpf_cnpj', 'f.razao_social', 'e.valor_nota', 'e.data_cadastro');
						$titulos_campos = array('N&uacute;mero da Nota', 'Nome Fantasia', 'CPF/CNPJ', 'Raz&atilde;o Social', 'Valor da Nota', 'Data de Cadastro');
						
						for($i = 0; $i < count($nomes_campos); $i++){
						   echo '<option value="' . $nomes_campos[$i] . '">' . $titulos_campos[$i] . '</option>';	
						}	
					  ?>
                      
                   </select>
                 </div>
                 
                 <div class="mb-3">
                   <label for="ordem_class" class="form-label">Ordem de Classifica&ccedil;&atilde;o</label>
                   <select class="form-select" id="ordem_class" name="ordem_class">
                      
                      <?php
					    echo '<option value="asc">Ascendente</option>';
						echo '<option value="desc">Descendente</option>';	
					  ?>
                      
                   </select>
                 </div>
                 
                 </div>
                 <div class="modal-footer">
                    <button type="button" onClick="document.form_paginacao.submit()" class="btn btn-secondary">Confirmar</button>
                    <button type="button" class="btn btn-secondary" onclick="fechar_paginacao()" data-dismiss="modal">Cancelar</button>
                    
                    <?php
					  if(isset($_GET["pesquisa"])){
						echo '<input type="hidden" value="' . $_GET["pesquisa"] . '" id="pesquisa" name="pesquisa">';  
					  }
					?>
                 </div>
               </div>
            </div>
            </form>
          </div>
                        
                        
                    </div>
                </main>
                
                <?php require("rodape.php"); ?>
                
            </div>
        </div>
        <script src="bootstrap-5.0.2/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    
    <script type="text/javascript">
	   var $modal = $('#janela_excluir_entrada');
	   var $modalPaginacao = $('#janela_paginacao');
	   var id_exlusao = 0;
	   
	   function excluir_registro(id, nota){
		 id_exclusao = id;
		 $('#msg_excluir').html("Você deseja mesmo excluir a entrada com a nota <b>" + nota + "</b>?<br><br>Se houver produtos cadastrados nessa entrada a exclusão será abortada.");
		 $modal.modal('show');    
	   }
	   
	   function confirmar_exclusao(){
		 var url_volta = window.location;
		 window.location = "excluir_entrada.php?entrada=" + id_exclusao + "&volta=" + url_volta;    
	   }
	   
	   function fechar_exclusao(){
	     $modal.modal('hide');
       }
	   
	   function definir_paginacao(){
		 $modalPaginacao.modal('show');    
	   }
	   
	   function fechar_paginacao(){
	     $modalPaginacao.modal('hide');
       }
	</script>
    
    
    </body>
</html>


<?php
  session_start();
  require("config.php");
  // o usuário está logado?
  if(!isset($_SESSION["id_usuario_logado"])){
    header("Location: login.php");
    exit;
  }
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title><?= $titulo_aplicacao ?> - Nova Entrada no Estoque</title>
  <link rel="stylesheet" href="bootstrap-icons-1.5.0/font/bootstrap-icons.css" />
  <link href="css/styles.css?<?= date('Y-m-d_H:i:s'); ?>" rel="stylesheet" />
  <link rel="stylesheet" href="css/classic.css">
  <link rel="stylesheet" href="css/classic.date.css">
  
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery.mask.min.js"></script>
  <script src="font-awesome/js/all.min.js" crossorigin="anonymous"></script>
  
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
                        
                        <h1 class="mt-4"><i class="bi-clipboard-plus"></i> Nova Entrada Estoque</h1>
                        
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">P&aacute;gina Inicial</a></li>
                            <li class="breadcrumb-item active">Nova Entrada no Estoque</li>
                        </ol>
                        
                        <?php if(isset($_GET["sucesso"])){ ?>
						
                        <div class="alert alert-success" role="alert">
                          A entrada no estoque foi cadastrada com sucesso. Voce pode agora verificar as <a href="entradas.php" class="alert-link">
                           Entradas Cadastradas</a> ou continuar cadastrando mais entradas.
                         </div>
                         
                        <?php } ?>
						
                        <div class="row">
                            <div class="col-xl-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="bi-clipboard-data"></i>
                                        Dados da Nova Entrada
                                    </div>
                                    <div class="card-body">
                                    
                                    <form id="cadastro_entrada" nome="cadastro_entrada" action="cadastrar_entrada_action.php" method="post" class="form-card">
                                    
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                          <label style="padding-left: 5px" class="form-control-label">N&uacute;mero Nota<span class="text-danger"> *</span></label>
                                         <input required type="text" id="num_nota" name="num_nota" placeholder="">
                                       </div>
                                    
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                       <label style="padding-left: 5px" class="form-control-label">Fornecedor<span class="text-danger"> *</span></label>
                                          <select name="fornecedor" id="fornecedor" style="margin-bottom: 10px; margin-top: 10px" class="form-select">
                                             <?php
											   $result = mysqli_query($conexao, "select * from fornecedores order by fantasia");
                                               if($result){
	                                              while($detalhes = mysqli_fetch_array($result)){
	                                                 echo '<option value="' . $detalhes["id"] . '">' . utf8_encode($detalhes["fantasia"]) . '</option>';
	                                              }
                                               }
											 ?>
                                          </select>
                                       </div>
                                    </div>
                                       
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                         <label style="padding-left: 5px" class="form-control-label">Data de Emiss&atilde;o<span class="text-danger"> *</span></label>
                                         <input type="text" required class="form-control" value="<?= date('d/m/Y') ?>" name="data_emissao" id="data_emissao" placeholder="Escolha a data">
                                       </div>
                                    
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                         <label style="padding-left: 5px" class="form-control-label">Data de Entrada<span class="text-danger"> *</span></label>
                                         <input type="text" required class="form-control" value="<?= date('d/m/Y') ?>" name="data_entrada" id="data_entrada" placeholder="Escolha a data">
                                       </div>
                                    </div>   
                                    
                                       
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-12 flex-column d-flex">
                                          <label style="padding-left: 5px" class="form-control-label">Informa&ccedil;&otilde;es Adicionais<span class="text-danger"> </span></label>
                                          <textarea class="form-control" name="info" id="info" rows="4"></textarea>
                                       </div>
                                    </div>
                                    
                                    <div class="row justify-content-end">
                                       <div class="form-group col-12 flex-column d-flex">
                                          <button type="submit" name="gravar" style="width: 300px" class="btn-block btn-secondary">Gravar e Adicionar Produtos</button>
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
                        
                        
                    </div>
                </main>
                
                <?php require("rodape.php"); ?>
                
            </div>
        
        <script src="bootstrap-5.0.2/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/picker.js"></script>
        <script src="js/picker.date.js"></script>
        
        <script type="text/javascript">
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
	      // fim formata�ao dos campos
		</script>
        
    </body>
</html>


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
  <title><?= $titulo_aplicacao ?> - Nova Categoria</title>
  <link rel="stylesheet" href="bootstrap-icons-1.5.0/font/bootstrap-icons.css" />
  <link href="css/styles.css?<?= date('Y-m-d_H:i:s'); ?>" rel="stylesheet" />
  
  <script src="js/jquery.min.js"></script>
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
                        
                        <h1 class="mt-4"><i class="bi-clipboard-plus"></i> Nova Categoria</h1>
                        
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">P&aacute;gina Inicial</a></li>
                            <li class="breadcrumb-item active">Nova Categoria</li>
                        </ol>
                        
                        <?php if(isset($_GET["sucesso"])){ ?>
						
                        <div class="alert alert-success" role="alert">
                          A categoria foi cadastrada com sucesso. Voce pode agora verificar as <a href="categorias.php" class="alert-link">
                           Categorias Cadastradas</a> ou continuar cadastrando mais categorias.
                         </div>
                         
                        <?php } ?>
						
                        <div class="row">
                            <div class="col-xl-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="bi-clipboard-data"></i>
                                        Dados da Nova Categoria
                                    </div>
                                    <div class="card-body">
                                    
                                    <form id="cadastro_categoria" nome="cadastro_categoria" action="cadastrar_categoria_action.php" method="post" class="form-card">
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-12 flex-column d-flex">
                                          <label style="padding-left: 5px" class="form-control-label">Nome<span class="text-danger"> *</span></label>
                                         <input required type="text" id="nome" name="nome" placeholder="">
                                       </div>
                                    </div>
                                       
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-12 flex-column d-flex">
                                          <label style="padding-left: 5px" class="form-control-label">Descriçao<span class="text-danger"> *</span></label>
                                          <textarea required class="form-control" name="descricao" id="descricao" rows="3"></textarea>
                                       </div>
                                    </div>
                                    
                                    <div class="row justify-content-end">
                                       <div class="form-group col-12 flex-column d-flex">
                                          <button type="submit" name="gravar" style="width: 200px" class="btn-block btn-secondary">Gravar Dados</button>
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
                                    
                                    Uma descriçao mais detalhada da categoria dos produtos, além de fornecer informaçoes adicionais, agiliza a busca, pois o sistema permite buscar categorias de acordo com a descriçao também.
                                    
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
    
    
    </body>
</html>


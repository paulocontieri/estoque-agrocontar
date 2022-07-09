<?php
  require("config.php");
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title><?= $titulo_aplicacao ?> - Recuperar Senha</title>
  <link rel="stylesheet" href="bootstrap-icons-1.5.0/font/bootstrap-icons.css" />
  <link href="css/styles.css?<?= date('Y-m-d_H:i:s'); ?>" rel="stylesheet" />
  
  <script src="js/jquery.min.js"></script>
  <script src="font-awesome/js/all.min.js" crossorigin="anonymous"></script>
</head>
    <body style="background-color: #eeeeee">
        
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="index.php"><img width="200px" src="imagens/logo_cima.jpg"/></a>
        </nav>
        
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-3"><i class="bi-key"></i> Recuperar Senha</h3>
                                    
                                    <?php  if(isset($_GET["erro"])){ ?>
                                      <h5 class="text-center font-weight-light my-3"><span style="color: #ff5722">O e-mail informado n&atilde;o foi encontrado. Por favor, tente novamente.</span></h5>
                                    <?php } ?>
                                    
                                    <?php  if(isset($_GET["sucesso"])){ ?>
                                      <h5 class="text-center font-weight-light my-3"><span style="color:#699">Seus dados de acesso foram enviados para o e-mail: <?= tratar_entrada($_GET["email"]) ?></span></h5>
                                    <?php } ?>
                                    
                                    </div>
                                    
                                    <div class="card-body">
                                        <form method="post" action="recuperar_senha_action.php">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="email" name="email" required type="text" placeholder="" />
                                                <label for="email">Informe seu e-mail</label>
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button type="submit" name="login" style="width: 150px" class="form-control btn-secondary">Recuperar Senha</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                
                <?php require("rodape.php"); ?>
                
            </div>
        </div>
        <script src="bootstrap-5.0.2/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    
    <script type="text/javascript">
    function mostrar_senha(){
      var x = document.getElementById("senha");
      if (x.type === "password") {
         x.type = "text";
      } 
	  else {
         x.type = "password";
     }
   }
   </script>
    
    </body>
</html>



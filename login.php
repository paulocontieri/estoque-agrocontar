<?php
  require("config.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title><?= $titulo_aplicacao ?> - Login do Usuário</title>
  <link rel="stylesheet" href="bootstrap-icons-1.5.0/font/bootstrap-icons.css" />
  <link href="css/styles.css?<?= date('Y-m-d_H:i:s'); ?>" rel="stylesheet" />
  
  <script src="js/jquery.min.js"></script>
  <script src="font-awesome/js/all.min.js" crossorigin="anonymous"></script>
</head>
    <body class="background" style="background-image: url('imagens/background.jpg')">
        
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-light">
            <a class="navbar-brand ps-3" href="index.php"><img width="450px" src="imagens/logo.png"/></a>
        </nav>
        
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-3"><i class="bi-person"></i> Faça o login</h3>
                                    
                                    <?php  if(isset($_GET["erro"])){ ?>
                                      <h5 class="text-center font-weight-light my-3"><span style="color: #ff5722">O nome de usuário ou senha nao confere. Por favor, tente novamente.</span></h5>
                                    <?php } ?>
                                    
                                    </div>
                                    
                                    <div class="card-body">
                                        <form method="post" action="login_action.php">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="usuario" name="usuario" required type="text" placeholder="" />
                                                <label for="usuario">Nome de Usuário</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="senha" name="senha" required type="password" placeholder="" />
                                                <label for="senha">Senha</label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" onclick="mostrar_senha()" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Mostrar Senha</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="recuperar_senha.php">Esqueceu a senha?</a>
                                                <button type="submit" name="login" style="width: 150px" class="form-control btn-secondary">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small">Qualquer dúvida, contate o <strong>administrador</strong> do sistema.</div> 
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



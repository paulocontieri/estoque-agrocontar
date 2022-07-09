<?php
  session_start();
  require("config.php");
  // o usuĂĄrio estĂĄ logado?
  if(!isset($_SESSION["id_usuario_logado"])){
    header("Location: login.php");
    exit;
  }

  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $senha_usuario_logado = $_SESSION["senha_usuario_logado"];
  $senha_anterior = tratar_entrada(utf8_decode(trim($_POST["senha_anterior"])));
  $nova_senha = tratar_entrada(utf8_decode(trim($_POST["nova_senha"])));
   
  // a senha anterior confere com a senha gravada na sessão?
  if($senha_anterior != $senha_usuario_logado){
	header("Location: alterar_senha.php?erro=1");
    exit;  
  }
  
  // versão de demonstração. Nâo deixar alterar a senha do admin
  if($_SESSION["usuario_logado"] == "admin"){
	$_SESSION["erro_banco_dados"] = "A demonstra&ccedil;&atilde;o do sistema n&atilde;o permite a altera&ccedil;&atilde;o da senha do admin";
    header("Location: erro_banco_dados.php");
    exit;   
  }
  // fim não deixar alterar a senha do admin
  
  // vamos atualizar a senha 
  $result = mysqli_query($conexao, "update usuarios set senha = '$nova_senha' where id = '$id_usuario_logado'");
  if($result){
	
	// vamos registrar esse log
	$ip = getUserIpAddr();
	$usuario_logado = $_SESSION["usuario_logado"];
	$texto_log = 'O usuario ' . $usuario_logado . ' alterou sua senha de acesso ao sistema.';
	$result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
      NOW(), '$usuario_logado', '$ip')");
	// fim registrar o log
    
	$_SESSION["senha_usuario_logado"] = $nova_senha;
	header("Location: alterar_senha.php?sucesso=ok");
    exit;
  }
  else{
	$_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de usuarios. Nao foi possivel alterar a senha.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit;   
  }
?>
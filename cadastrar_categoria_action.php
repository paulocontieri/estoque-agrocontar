<?php
session_start();
  require("config.php");
  // o usuário está logado?
  if(!isset($_SESSION["id_usuario_logado"])){
    header("Location: login.php");
    exit;
  }

 // tem permissão para cadastrar?
  if(!Permissao::podeCadastrar($_SESSION["nivel_acesso"])){
	header("Location: erro_permissao.php");
    exit;  
  }
  // fim permissão para cadastrar

$id_usuario_logado = $_SESSION["id_usuario_logado"];
$nome = tratar_entrada(utf8_decode(trim($_POST["nome"])));
$descricao = tratar_entrada(utf8_decode(trim($_POST["descricao"])));  
  
  $result = mysqli_query($conexao, "INSERT into categorias values (null, '$nome', '$descricao', NOW(), '$id_usuario_logado')");
	 
  if($result){
	  
	   // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' cadastrou a categoria ' . $nome . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log 
	  
	 header("Location: nova_categoria.php?sucesso=ok");
     exit; 
  }
  else{
	$_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de categorias. Verifique se ja existe uma categoria com este nome.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit;  
  }
?>

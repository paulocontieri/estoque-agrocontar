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
$id_saida = tratar_entrada(utf8_decode(trim($_POST["id_saida"])));
$num_doc = tratar_entrada(utf8_decode(trim($_POST["num_doc"])));
$responsavel = tratar_entrada(utf8_decode(trim($_POST["responsavel"])));
$data_emissao = tratar_entrada(utf8_decode(trim($_POST["data_emissao"])));
$data_saida = tratar_entrada(utf8_decode(trim($_POST["data_saida"])));
$acrescimos = tratar_entrada(utf8_decode(trim($_POST["acrescimos"])));
$descontos = tratar_entrada(utf8_decode(trim($_POST["descontos"])));
$info = tratar_entrada(utf8_decode(trim($_POST["info"])));
 
  $result = mysqli_query($conexao, "UPDATE saidas SET num_doc = '$num_doc', responsavel = '$responsavel',
     desconto = '$descontos', acrescimo = '$acrescimos',
	 data_emissao = '$data_emissao', data_saida = '$data_saida', info = '$info' 
	 WHERE id = '$id_saida'");
	 
  if($result){
	 
	  	    // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' atualizou a retirada ' . sprintf("%05d", $id_saida) . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log
	 
	 header("Location: saida.php?saida=$id_saida&sucesso_atualizar=ok");
     exit; 
  }
  else{
	$_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de retiradas. Verifique se ja existe um lancamento com este numero de documento.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit;  
  }
?>

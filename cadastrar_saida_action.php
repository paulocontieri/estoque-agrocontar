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
$num_doc = tratar_entrada(utf8_decode(trim($_POST["num_doc"])));
$responsavel = tratar_entrada(utf8_decode(trim($_POST["responsavel"])));
$data_emissao = tratar_entrada(utf8_decode(trim($_POST["data_emissao"])));
$data_saida = tratar_entrada(utf8_decode(trim($_POST["data_saida"])));
$valor_saida = 0;
$acrescimos = tratar_entrada(utf8_decode(trim($_POST["acrescimos"])));
$descontos = tratar_entrada(utf8_decode(trim($_POST["descontos"])));
$info = tratar_entrada(utf8_decode(trim($_POST["info"])));
  
  $result = mysqli_query($conexao, "INSERT into saidas values (null, '$num_doc', '$responsavel', '$valor_saida',
     '$descontos', '$acrescimos', '0', '0', '$data_emissao', '$data_saida', '$info', NOW(), '$id_usuario_logado')");
	 
  if($result){
	 // direciona para inserir os itens dessa saída
	 $ultimo_id = mysqli_insert_id($conexao); 
	 header("Location: saida.php?saida=$ultimo_id&acrescentar_itens=true");
     exit; 
  }
  else{
	$_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de retiradas. Verifique se ja existe um lancamento com este numero de documento.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit;  
  }
?>

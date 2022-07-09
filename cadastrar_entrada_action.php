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
$num_nota = tratar_entrada(utf8_decode(trim($_POST["num_nota"])));
$fornecedor = tratar_entrada(utf8_decode(trim($_POST["fornecedor"])));
$data_emissao = tratar_entrada(utf8_decode(trim($_POST["data_emissao"])));
$data_entrada = tratar_entrada(utf8_decode(trim($_POST["data_entrada"])));
$valor_nota = 0;
$acrescimos = tratar_entrada(utf8_decode(trim($_POST["acrescimos"])));
$descontos = tratar_entrada(utf8_decode(trim($_POST["descontos"])));
$info = tratar_entrada(utf8_decode(trim($_POST["info"])));
  
  $result = mysqli_query($conexao, "INSERT into entradas values (null, '$num_nota', '$fornecedor', '$valor_nota',
     '$descontos', '$acrescimos', '0', '0', '$data_emissao', '$data_entrada', '$info', NOW(), '$id_usuario_logado')");
	 
  if($result){
	 // direciona para inserir os itens dessa entrada
	 $ultimo_id = mysqli_insert_id($conexao); 
	 header("Location: entrada.php?entrada=$ultimo_id&acrescentar_itens=true");
     exit; 
  }
  else{
	$_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de entradas. Verifique se ja existe esta nota cadastrada para este fornecedor.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit;  
  }
?>

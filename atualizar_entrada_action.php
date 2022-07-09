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
$id_entrada = tratar_entrada(utf8_decode(trim($_POST["id_entrada"])));
$num_nota = tratar_entrada(utf8_decode(trim($_POST["num_nota"])));
$fornecedor = tratar_entrada(utf8_decode(trim($_POST["fornecedor"])));
$data_emissao = tratar_entrada(utf8_decode(trim($_POST["data_emissao"])));
$data_entrada = tratar_entrada(utf8_decode(trim($_POST["data_entrada"])));
$acrescimos = tratar_entrada(utf8_decode(trim($_POST["acrescimos"])));
$valor_nota = tratar_entrada(utf8_decode(trim($_POST["valor_nota_temp"])));
$descontos = tratar_entrada(utf8_decode(trim($_POST["descontos"])));
$info = tratar_entrada(utf8_decode(trim($_POST["info"])));
 
  $result = mysqli_query($conexao, "UPDATE entradas SET num_nota = '$num_nota', fornecedor = '$fornecedor',
     desconto = '$descontos', acrescimo = '$acrescimos',
	 data_emissao = '$data_emissao', data_entrada = '$data_entrada', info = '$info' 
	 WHERE id = '$id_entrada'");
	 
  if($result){
	 
	 	    // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' atualizou a entrada ' . sprintf("%05d", $id_entrada) . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log
	 
	 header("Location: entrada.php?entrada=$id_entrada&sucesso_atualizar=ok");
     exit; 
  }
  else{
	$_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de entradas. Verifique se ja existe esta nota cadastrada para este fornecedor.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit; 
  }
?>

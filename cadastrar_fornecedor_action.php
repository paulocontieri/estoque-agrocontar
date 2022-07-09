<?php
session_start();
  require("config.php");
  // o usuĂĄrio estĂĄ logado?
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
$fantasia = tratar_entrada(utf8_decode(trim($_POST["fantasia"])));
$razao_social = tratar_entrada(utf8_decode(trim($_POST["razao_social"])));
$cpf_cnpj = tratar_entrada(utf8_decode(trim($_POST["cpf_cnpj"])));
$email = tratar_entrada(utf8_decode(trim($_POST["email"])));
$telefone = tratar_entrada(utf8_decode(trim($_POST["telefone"])));
$endereco = tratar_entrada(utf8_decode(trim($_POST["endereco"])));
$cidade = tratar_entrada(utf8_decode(trim($_POST["cidade"])));
$estado = tratar_entrada(utf8_decode(trim($_POST["estado"])));
$info = tratar_entrada(utf8_decode(trim($_POST["info"])));
$foto = tratar_entrada(utf8_decode(trim($_POST["foto"])));
  
  $hoje = date('d-m-Y');
  if(file_exists($diretorio_fotos_fornecedores . '/temp/' . $hoje . '/' . $foto . '.png')){
	 rename($diretorio_fotos_fornecedores . '/temp/' . $hoje . '/' . $foto . '.png', 
	    $diretorio_fotos_fornecedores . '/' . $foto . '.png');
  }
  
  $result = mysqli_query($conexao, "INSERT into fornecedores values (null, '$fantasia', '$razao_social', '$cpf_cnpj', 
     '$email', '$telefone', '$endereco', '$cidade', '$estado', '$info', '$foto', NOW(), '$id_usuario_logado')");
	 
  if($result){
	 
	     // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' cadastrou o fornecedor ' . $fantasia . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log 
	 
	 header("Location: novo_fornecedor.php?sucesso=ok");
     exit; 
  }
  else{
	$_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de fornecedores. Verifique se ja existe um fornecedor com este nome fantasia.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit;  
  }
?>

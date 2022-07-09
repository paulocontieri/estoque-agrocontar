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
$id_fornecedor = tratar_entrada(utf8_decode(trim($_POST["id_fornecedor"])));
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
	 // atualiza a foto	
	 $result = mysqli_query($conexao, "UPDATE fornecedores SET foto = '$foto' WHERE id = '$id_fornecedor'");
  }
  
  $result = mysqli_query($conexao, "UPDATE fornecedores SET fantasia = '$fantasia', razao_social = '$razao_social', 
     cpf_cnpj = '$cpf_cnpj', email = '$email', telefone = '$telefone', endereco = '$endereco', cidade = '$cidade', 
	 estado = '$estado', info = '$info' WHERE id = '$id_fornecedor'");
	 
  if($result){
	 
	    // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' atualizou o fornecedor ' . $fantasia . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log
	 
	 header("Location: fornecedor.php?fornecedor=$id_fornecedor&sucesso=ok");
	 exit; 
  }
  else{
	$_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de fornecedores. Verifique se ja existe um fornecedor com este nome fantasia.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit;  
  }
?>

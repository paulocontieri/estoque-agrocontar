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
$usuario = strtolower(tratar_entrada(utf8_decode(trim($_POST["usuario"]))));
$senha = tratar_entrada(utf8_decode(trim($_POST["senha"])));
$nome = tratar_entrada(utf8_decode(trim($_POST["nome"])));
$email = tratar_entrada(utf8_decode(trim($_POST["email"])));
$telefone = tratar_entrada(utf8_decode(trim($_POST["telefone"])));
$nivel = tratar_entrada(utf8_decode(trim($_POST["nivel"])));
$info = tratar_entrada(utf8_decode(trim($_POST["info"])));
$foto = tratar_entrada(utf8_decode(trim($_POST["foto"])));
  
  $hoje = date('d-m-Y');
  if(file_exists($diretorio_fotos_usuarios . '/temp/' . $hoje . '/' . $foto . '.png')){
	 rename($diretorio_fotos_usuarios . '/temp/' . $hoje . '/' . $foto . '.png', 
	    $diretorio_fotos_usuarios . '/' . $foto . '.png');
  }
  
  $result = mysqli_query($conexao, "INSERT into usuarios values (null, '$usuario', '$senha', '$nome', '$email', '$telefone',
	 '$nivel', '$info', '$foto', NOW(), NOW(), 'N', '$id_usuario_logado')");
	 
  if($result){
	 
	  // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' cadastrou o usuario ' . $usuario . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log
	 
	 header("Location: novo_usuario.php?sucesso=ok");
     exit; 
  }
  else{
	$_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de usuários. Verifique se ja existe um usuario com este nome.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit;  
  }
?>

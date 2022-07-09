<?php
session_start();
  require("config.php");
  // o usuĂĄrio estĂĄ logado?
  if(!isset($_SESSION["id_usuario_logado"])){
    header("Location: login.php");
    exit;
  }

// tem permissão para alterar?
if(!Permissao::podeAlterar($_SESSION["nivel_acesso"])){
  header("Location: erro_permissao.php");
  exit;  
}
// fim permissão para alterar

$id_usuario_logado = $_SESSION["id_usuario_logado"];
$id_usuario = tratar_entrada(utf8_decode(trim($_POST["id_usuario"])));
$usuario = tratar_entrada(utf8_decode(trim($_POST["usuario"])));
$nome = tratar_entrada(utf8_decode(trim($_POST["nome"])));
$telefone = tratar_entrada(utf8_decode(trim($_POST["telefone"])));

if($usuario != 'admin'){
  $nivel = tratar_entrada(utf8_decode(trim($_POST["nivel"])));
  $bloqueado = tratar_entrada(utf8_decode(trim($_POST["bloqueado"])));
}

$info = tratar_entrada(utf8_decode(trim($_POST["info"])));
$foto = tratar_entrada(utf8_decode(trim($_POST["foto"])));

  $hoje = date('d-m-Y');
  if(file_exists($diretorio_fotos_usuarios . '/temp/' . $hoje . '/' . $foto . '.png')){
	 rename($diretorio_fotos_usuarios . '/temp/' . $hoje . '/' . $foto . '.png', 
	    $diretorio_fotos_usuarios . '/' . $foto . '.png');
	 // atualiza a foto	
	 $result = mysqli_query($conexao, "UPDATE usuarios SET foto = '$foto' WHERE id = '$id_usuario'");	
  }
  
  if($usuario == 'admin'){
     $result = mysqli_query($conexao, "UPDATE usuarios SET nome = '$nome', telefone = '$telefone',
	   info = '$info' WHERE id = '$id_usuario'");
  }
  else{
     $result = mysqli_query($conexao, "UPDATE usuarios SET nome = '$nome', telefone = '$telefone',
	   nivel = '$nivel', info = '$info', bloqueado = '$bloqueado' WHERE id = '$id_usuario'");
  }
	 
  if($result){
	 
	 
	 // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' atualizou o cadastro do usuario ' . $usuario . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log
	 
	 
	 header("Location: usuario.php?usuario=$id_usuario&sucesso=ok");
     exit; 
  }
  else{
	echo "<h1>Houve um erro na atualizaçao do usuário: " . mysqli_error($conexao) . "</h1>";  
  }
?>

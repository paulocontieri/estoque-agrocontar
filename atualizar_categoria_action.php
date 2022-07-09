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
$id_categoria = tratar_entrada(utf8_decode(trim($_POST["id_categoria"])));
$nome = tratar_entrada(utf8_decode(trim($_POST["nome"])));
$descricao = tratar_entrada(utf8_decode(trim($_POST["descricao"])));

   $result = mysqli_query($conexao, "UPDATE categorias SET nome = '$nome', descricao = '$descricao'
	  WHERE id = '$id_categoria'");
 
  if($result){
	  
	  // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' atualizou a categoria ' . $nome . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log 
	  
	 header("Location: categoria.php?categoria=$id_categoria&sucesso=ok");
     exit; 
  }
  else{
	$_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de categorias. Verifique se ja existe uma categoria com este nome.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit;  
  }
?>

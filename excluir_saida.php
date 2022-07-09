<?php
session_start();
require("config.php");
// o usuĂĄrio estĂĄ logado?
if(!isset($_SESSION["id_usuario_logado"])){
  header("Location: login.php");
  exit;
}

// tem permissão para excluir?
if(!Permissao::podeExcluir($_SESSION["nivel_acesso"])){
  header("Location: erro_permissao.php");
  exit;  
}
// fim permissão para excluir

// id da saída a ser excluída
$saida = tratar_entrada(utf8_decode(trim($_GET["saida"])));
// url para voltar depois da exclusao
$url_volta = tratar_entrada($_GET["volta"]);

$result = mysqli_query($conexao, "DELETE FROM saidas WHERE id = '$saida'");
if($result){
  
   	    // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' excluiu a retirada ' . sprintf("%05d", $saida) . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log
  
  header("Location: $url_volta");
  exit; 
}
else{
  $_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de saidas no estoque. Verifique se existe produtos cadastrados nessa saida antes de efetuar a exclusao.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit;  
} 
?>

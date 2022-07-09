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

// id do usuário a ser excluído
$usuario = tratar_entrada(utf8_decode(trim($_GET["usuario"])));
$nome_usuario_exclusao = tratar_entrada(utf8_decode(trim($_GET["nome_usuario"])));
// url para voltar depois da exclusao
$url_volta = tratar_entrada($_GET["volta"]);

// precisamos excluir a foto deste produto
$result_ex = mysqli_query($conexao, "SELECT foto FROM usuarios WHERE id = '$usuario'");
$detalhes_ex = mysqli_fetch_array($result_ex);
$nome_foto = $detalhes_ex["foto"];
// fim excluir a foto do produto

$result = mysqli_query($conexao, "DELETE FROM usuarios WHERE id = '$usuario'");
if($result){
  
  // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' excluiu o usuario ' . $nome_usuario_exclusao . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log
  
  unlink($diretorio_fotos_usuarios . '/' . $nome_foto . '.png');
  header("Location: $url_volta");
  exit; 
}
else{
  $_SESSION["erro_banco_dados"] = "Houve um erro no cadastro de usuários. Se houver informacoes cadastradas por este usuario, ele nao podera ser excluido.<br><br>Erro: " . mysqli_error($conexao);
  header("Location: erro_banco_dados.php");
  exit;  
} 
?>

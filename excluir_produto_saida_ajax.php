<?php
session_start();
  require("config.php");
  // o usuário está logado?
  if(!isset($_SESSION["id_usuario_logado"])){
    header("Location: login.php");
    exit;
  }

  header("Cache-Control: no-cache, must-revalidate");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Content-Type: text/xml");

 // tem permissão para excluir?
  if(!Permissao::podeExcluir($_SESSION["nivel_acesso"])){
	header("Location: erro_permissao.php");
    exit;  
  }
  // fim permissão para cadastrar

$id_usuario_logado = $_SESSION["id_usuario_logado"];
$id_item_saida = tratar_entrada(utf8_decode(trim($_POST["id_item_saida"])));
$id_saida = tratar_entrada(utf8_decode(trim($_POST["id_saida"])));
$id_produto = tratar_entrada(utf8_decode(trim($_POST["id_produto"])));
$quantidade = tratar_entrada(utf8_decode(trim($_POST["quantidade"])));
$preco_unitario = tratar_entrada(utf8_decode(trim($_POST["preco_unitario"])));
$preco_total = tratar_entrada(utf8_decode(trim($_POST["preco_total"])));

  $result = mysqli_query($conexao, "DELETE FROM itens_saida WHERE id = '$id_item_saida'");
	 
  if($result){
	 
	 // vamos atualizar o estoque deste produto
	 $result = mysqli_query($conexao, "UPDATE produtos SET estoque = (estoque + '$quantidade') WHERE id = '$id_produto'");
	 // fim atualizar o estoque deste produto
	 
	 // vamos atualizar a saida
	 $result = mysqli_query($conexao, "UPDATE saidas SET quant_itens = (quant_itens - '$quantidade'),
	   quant_produtos = (quant_produtos - 1), valor = (valor - '$preco_total') WHERE id = '$id_saida'");
	 // fim atualizar a saida
	 
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
echo "<mensagens>\n";
  
echo "  <mensagem>\n";
echo "    <texto>" . $id_item_saida . "</texto>\n";
echo "  </mensagem>\n";
echo "</mensagens>\n";
   
  }
  else{
	echo "<h1>Houve um erro no cadastro de saidas: " . mysqli_error($conexao) . "</h1>";  
  }
?>

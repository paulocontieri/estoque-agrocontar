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

 // tem permissão para cadastrar?
  if(!Permissao::podeCadastrar($_SESSION["nivel_acesso"])){
	header("Location: erro_permissao.php");
    exit;  
  }
  // fim permissão para cadastrar

$id_usuario_logado = $_SESSION["id_usuario_logado"];
$id_entrada = tratar_entrada(utf8_decode(trim($_POST["id_entrada"])));
$id_produto = tratar_entrada(utf8_decode(trim($_POST["id_produto"])));
$quantidade = tratar_entrada(utf8_decode(trim($_POST["quantidade"])));
$preco_unitario = tratar_entrada(utf8_decode(trim($_POST["preco_unitario"])));
$total = $quantidade * $preco_unitario;

  // id	id_produto	id_entrada	valor_unitario	quantidade
  $result = mysqli_query($conexao, "INSERT into itens_entrada values (null, '$id_produto', '$id_entrada', 
    '$preco_unitario', '$quantidade', NOW(), '$id_usuario_logado')");
	 
  if($result){
	 // direciona para inserir os itens dessa entrada
	 $ultimo_id = mysqli_insert_id($conexao);
	 
         // vamos atualizar o estoque deste produto
	 $result = mysqli_query($conexao, "UPDATE produtos SET preco_compra = '$preco_unitario', estoque = (estoque + '$quantidade') WHERE id = '$id_produto'");
	 // fim atualizar o estoque deste produto

	 // vamos atualizar a entrada
	 $result = mysqli_query($conexao, "UPDATE entradas SET quant_itens = (quant_itens + '$quantidade'),
	   quant_produtos = (quant_produtos + 1), valor_nota = (valor_nota + '$total') WHERE id = '$id_entrada'");
	 // fim atualizar a entrada
	 
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
echo "<mensagens>\n";
echo "  <mensagem>\n";
echo "    <texto>" . $ultimo_id . "</texto>\n";
echo "  </mensagem>\n";
echo "</mensagens>\n";
   
  }
  else{
	echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
echo "<mensagens>\n";
echo "  <mensagem>\n";
echo "    <texto>erro</texto>\n";
echo "  </mensagem>\n";
echo "</mensagens>\n";  
  }
?>

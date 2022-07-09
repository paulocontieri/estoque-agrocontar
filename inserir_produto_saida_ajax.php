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
$id_saida = tratar_entrada(utf8_decode(trim($_POST["id_saida"])));
$id_produto = tratar_entrada(utf8_decode(trim($_POST["id_produto"])));
$quantidade = tratar_entrada(utf8_decode(trim($_POST["quantidade"])));
$preco_unitario = tratar_entrada(utf8_decode(trim($_POST["preco_unitario"])));
$total = $quantidade * $preco_unitario;

  // vamos verificar se o estoque deste produto é compatível com esta retirada
  $result = mysqli_query($conexao, "SELECT estoque FROM produtos WHERE id = '$id_produto'");
  $detalhes = mysqli_fetch_array($result);
  $estoque_atual = $detalhes["estoque"];
  
  if($estoque_atual < $quantidade){
	 echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
     echo "<mensagens>\n"; 
       echo "  <mensagem>\n";
          echo "    <texto>estoque</texto>\n";
		  echo "    <estoque>" . $estoque_atual . "</estoque>\n";
       echo "  </mensagem>\n";
     echo "</mensagens>\n";
	 exit; 
  }
  // fim verificar se o estoque deste produto é compatível com esta retirada

  // id	id_produto	id_saida	valor_unitario	quantidade	data_cadastro	id_usuario
  $result = mysqli_query($conexao, "INSERT into itens_saida values (null, '$id_produto', '$id_saida', 
    '$preco_unitario', '$quantidade', NOW(), '$id_usuario_logado')");
	 
  if($result){
	 // direciona para inserir os itens dessa saída
	 $ultimo_id = mysqli_insert_id($conexao);
	 
	 // vamos atualizar o estoque deste produto
	 $result = mysqli_query($conexao, "UPDATE produtos SET estoque = (estoque - '$quantidade') WHERE id = '$id_produto'");
	 // fim atualizar o estoque deste produto
	 
	 // vamos atualizar a saida
	 $result = mysqli_query($conexao, "UPDATE saidas SET quant_itens = (quant_itens + '$quantidade'),
	   quant_produtos = (quant_produtos + 1), valor = (valor + '$total') WHERE id = '$id_saida'");
	 // fim atualizar a saída 
	 
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

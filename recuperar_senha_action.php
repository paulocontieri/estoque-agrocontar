<?php
  session_start();
  require("config.php");

  $email = strtolower(tratar_entrada(utf8_decode(trim($_POST["email"]))));
   
  $result = mysqli_query($conexao, "SELECT * FROM usuarios WHERE email = '$email'");
  $quant = mysqli_num_rows($result);
  if($quant > 0){ // encontrei
    $detalhes = mysqli_fetch_array($result);
    $id_usuario = $detalhes["id"];
	$usuario = $detalhes["usuario"];
	$senha_usuario = $detalhes["senha"];
	
	// vamos registrar esse log
	$ip = getUserIpAddr();
	$texto_log = 'O usuario ' . $usuario . ' recuperou sua senha de acesso.';
	$result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario', '$texto_log', 
      NOW(), '$usuario', '$ip')");
	// fim registrar o log
    
	// envia os e-mail com os dados de acesso
	
	// fim enviar e-mail
	
	header("Location: recuperar_senha.php?sucesso=ok&email=" . $email);
    exit;
  }
  else{ // nao encontrei
    header("Location: recuperar_senha.php?erro=1");
    exit;
  }
?>
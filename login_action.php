<?php
  session_start();
  require("config.php");

  $usuario = strtolower(tratar_entrada(utf8_decode(trim($_POST["usuario"]))));
  $senha = tratar_entrada(utf8_decode(trim($_POST["senha"])));
   
  $result = mysqli_query($conexao, "SELECT id, usuario, senha, nivel, DATE_FORMAT(ultimo_acesso, '%d/%m/%Y - %H:%i') 
     as ultimo_acesso FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha' AND bloqueado = 'N'");

  $quant = mysqli_num_rows($result);
  if($quant > 0){ // encontrei
    $detalhes = mysqli_fetch_array($result);
    $id_usuario_logado = $detalhes["id"];
	$usuario_logado = $detalhes["usuario"];
	$senha_usuario_logado = $detalhes["senha"];
	$ultimo_acesso = $detalhes["ultimo_acesso"];
	$nivel_acesso = $detalhes["nivel"];
	
	$_SESSION["id_usuario_logado"] = $id_usuario_logado;
	$_SESSION["usuario_logado"] = $usuario_logado;
	$_SESSION["senha_usuario_logado"] = $senha_usuario_logado;
	$_SESSION["ultimo_acesso"] = $ultimo_acesso;
	$_SESSION["nivel_acesso"] = $nivel_acesso;
	
	$result_2 = mysqli_query($conexao, "update usuarios set ultimo_acesso = NOW() where id = '$id_usuario_logado'");
	
	// vamos registrar esse log
	$ip = getUserIpAddr();
	$texto_log = 'O usuario ' . $usuario_logado . ' acessou o sistema';
	$result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
      NOW(), '$usuario_logado', '$ip')");
	// fim registrar o log
    
	header("Location: index.php?usuario_logado=" . time());
    exit;
  }
  else{ // nao encontrei
    header("Location: login.php?erro=1");
    exit;
  }
?>
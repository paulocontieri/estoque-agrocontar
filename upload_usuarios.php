<?php
session_start();
  require("config.php");
  // o usuĂĄrio estĂĄ logado?
  if(!isset($_SESSION["id_usuario_logado"])){
    header("Location: login.php");
    exit;
  }

if(isset($_POST['image'])){
  $data = $_POST['image'];
  $nome_imagem = $_POST['nome_imagem'];
  $image_array_1 = explode(";", $data);
  $image_array_2 = explode(",", $image_array_1[1]);
  $data = base64_decode($image_array_2[1]);
  
  // um pasta com a data de hoje já existe?
  $hoje = date('d-m-Y');
  if(!file_exists($diretorio_fotos_usuarios . '/temp/' . $hoje)){
	 mkdir($diretorio_fotos_usuarios . '/temp/' . $hoje); 
  }
  
  $image_name = $diretorio_fotos_usuarios . '/temp/' . $hoje . '/' . $nome_imagem . '.png';
  file_put_contents($image_name, $data);
  $image_name = $url_fotos_usuarios . '/temp/' . $hoje . '/' . $nome_imagem . '.png';
  
  echo $image_name;
}
?>

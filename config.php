<?php
  //ini_set('default_charset', "");  

  // vamos efetuar a conexao com o banco
  $servidor = "localhost";
  $usuario = "root";
  $senha = '';
  $base_dados = "controle_estoque";
  $conexao = mysqli_connect($servidor, $usuario,
    $senha, $base_dados);

  // conexao efetuada com sucesso?
  if(mysqli_connect_errno()) {
    echo "<h1>Nao foi possível efetuar a conexao com o MySQL: "
      . mysqli_connect_error() . "</h1>";
    // vamos sair daqui
    die();
  }
  else{
	///echo '<h1>Conexao efetuada com sucesso.</h1>';  
  }
  
  // define o título da aplicaçao. Aparece na tag title das páginas
  $titulo_aplicacao = "Controle de Estoque";
  
  // URL base da aplicaçao. Se for HTTP, nao se esqueça de colocar
  // Exemplo: "http://localhost/controle_estoque_php_bootstrap_mysql_jquery"
  $url_base_aplicacao = "https://www.seusite.com.br/estoque";
  
  // caminho completo para a aplicaçao no servidor. Na dúvida use phpinfo(); para descobrir
  // $caminho_base_aplicacao = "C:\\xampp\\htdocs\\controle_estoque_php_bootstrap_mysql_jquery";
  // Exemplo: "/home/arquivodecodigos/www/"
  $caminho_base_aplicacao = "/home/seusite/www/controle_estoque_php_bootstrap_mysql_jquery";
  
  // caminho no servidor para o diretório de fotos dos usuários. Deve ter permissao de escrita
  // atençao: há um diretório temporário que é limpo todos os dias
  $diretorio_fotos_usuarios = $caminho_base_aplicacao . "/uploads/usuarios";
  $diretorio_fotos_produtos = $caminho_base_aplicacao . "/uploads/produtos";
  $diretorio_fotos_fornecedores = $caminho_base_aplicacao . "/uploads/fornecedores";
  
  // url o diretório de fotos dos usuários.
  $url_fotos_usuarios = $url_base_aplicacao . "/uploads/usuarios";
  $url_fotos_produtos = $url_base_aplicacao . "/uploads/produtos";
  $url_fotos_fornecedores = $url_base_aplicacao . "/uploads/fornecedores";
  
  // quantidade de registros por página
  $paginacao_padrao = 20;
  $opcoes_paginacao = array(5, 10, 20, 50, 100);
  $opcoes_paginacao_logs = array(100, 200, 300, 400, 500);
  
  // trata os níveis de acesso dos usuários
  class Permissao{
	public static $niveis = array();
	
	public static function obterNiveis(){
	  $niveis = array();
	  $niveis[1] = "Nível 1 - Administrador";
	  $niveis[2] = "Nível 2 - Cadastra, Altera, Exclui";
	  $niveis[3] = "Nível 3 - Cadastra e Altera";
	  $niveis[4] = "Nível 4 - Só Cadastra";
	  $niveis[5] = "Nível 5 - Somente Leitura";
	  return $niveis;	
	}
	
	public static function podeCadastrar($nivel){
	  if($nivel != 5){
		return true;  
	  }
	  return false;
	}
	
	public static function podeExcluir($nivel){
	  if(($nivel == 1) || ($nivel == 2)){
		return true;  
	  }
	  return false;
	}
	
	public static function podeAlterar($nivel){
	  if(($nivel == 1) || ($nivel == 2) || ($nivel == 3)){
		return true;  
	  }
	  return false;
	}
  }
  
  // unidades
  $nomes_unidades = array("UN", "MT", "KG", "CX", "LT", 'PC', 'DZ');
  
  // função que permite informar o id e retornar o nome de usuário que efetuou alguma ação
  function obterNomeUsuarioId($id){
    // por padrão, vamos assumir "admin"
	$nome = "admin";
	global $conexao;
	$result = mysqli_query($conexao, "select usuario from usuarios where id = '$id'");
    if($result){
	   while($detalhes = mysqli_fetch_array($result)){
	     $nome = $detalhes["usuario"];
	   }
	}
	
	return $nome;
  }
  
  // funçao que permite obter o IP do usuário logado
  function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
	if(strlen(trim($ip)) < 5){
	  return getHostByName(getHostName()); // rodando localmente?
	}
	else{
	  return $ip;	
	}
  }

  function tratar_entrada($string){
    // há muitos tratamentos a serem feitos, mas inicialmente vamos
    // apenas retirar as aspas simples. São elas que costumam ser usadas
    // para SQL injection
    $string = str_replace("'", '', $string);
    // vamos retirar tags também
    $string = str_replace('<', '', $string);
    $string = str_replace('>', '', $string);
	// alguns engraçadinhos gostam de testar diretórios
	$string = str_replace('../', '', $string);
	$string = str_replace('..%2F', '', $string);
    return $string;	
  }
?>
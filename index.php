<?php
  session_start();
  require("config.php");
  // o usu�rio est� logado?
  if(!isset($_SESSION["id_usuario_logado"])){
    header("Location: login.php");
    exit;
  }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Controle de Estoque - P&aacute;gina Inicial</title>
  <link rel="stylesheet" href="bootstrap-icons-1.5.0/font/bootstrap-icons.css" />
  <link href="css/styles.css?<?= date('Y-m-d_H:i:s'); ?>" rel="stylesheet" />
  <script src="font-awesome/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">

<?php require("cima.php"); ?>

<div id="layoutSidenav">
            
            <?php require("menu_lado.php"); ?>
            
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4"><i class="bi-bar-chart-line"></i> P&aacute;gina Inicial</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Vis&atilde;o Geral do Controle de Estoque</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">
                                    
                                    <table height="100%">
                                      <tr>
                                        <td>
                                          
                                          <?php
										    // obt�m a quantidade de produtos cadastrados no sistema
											$result = mysqli_query($conexao, "SELECT id FROM produtos");
                                            $quant = mysqli_num_rows($result);
											echo $quant . ' Produtos Cadastrados';
										  ?>
                                          
                                        </td>
                                      </tr>
                                      <tr>  
                                        <td>
                                          
                                          <?php
										    // obt�m a quantidade de itens no estoque
											$result = mysqli_query($conexao, "SELECT SUM(estoque) AS itens FROM produtos");
                                            $detalhes = mysqli_fetch_array($result);
                                            $quant_itens = $detalhes["itens"];
											echo $quant_itens . ' Itens no Estoque';
										  ?>
                                          
                                        </td>
                                      </tr>
                                      </tr>
                                    </table>
                                    
                                    </div>
                                    
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="produtos.php">Ver Rela&ccedil;&atilde;o de Produtos</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">
                                    
                                    <table height="100%">
                                      <tr>
                                        <td>
                                          
                                          <?php
										    // obt�m os produtos com estqoue zerado
											$result = mysqli_query($conexao, "SELECT id FROM produtos WHERE estoque = 0");
                                            $quant = mysqli_num_rows($result);
											echo $quant . ' Produtos Com Estoque Zerado';
										  ?>
                                          
                                        </td> 
                                      </tr>
                                    </table>
                                    
                                    </div>
                                    
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="produtos.php?tipo=estoque_zerado">Ver Produtos Estoque Zerado</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">
                                    
                                    <table height="100%">
                                      <tr>
                                        <td>
                                          
                                          <?php
										    // obt�m os produtos com estqoue m�nimo
											$result = mysqli_query($conexao, "SELECT id FROM produtos WHERE estoque <= estoque_min");
                                            $quant = mysqli_num_rows($result);
											echo $quant . ' Produtos Com Estoque M&iacute;nimo';
										  ?>
                                          
                                        </td> 
                                      </tr>
                                    </table>
                                    
                                    </div>
                                    
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="produtos.php?tipo=estoque_minimo">Ver Produtos Estoque M&iacute;nimo</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">
                                    
                                    <table height="100%">
                                      
                                      <?php
										    // obt�m o investimento e lucro previsto no estqoue
											$result = mysqli_query($conexao, "SELECT SUM(estoque * preco_compra) AS compra FROM produtos");
                                            $detalhes = mysqli_fetch_array($result);
                                            $investimento = $detalhes["compra"];
											$result = mysqli_query($conexao, "SELECT SUM(estoque * preco_venda) AS venda FROM produtos");
                                            $detalhes = mysqli_fetch_array($result);
                                            $retorno = $detalhes["venda"];
									   ?>
                                      
                                      <tr>
                                        <td>
                                          Investimento: R$ <?= number_format($investimento, 2, ',', '.') ?>
                                        </td>
                                      </tr>
                                      <tr>  
                                        <td nowrap>
                                          Retorno Previsto: R$ <?= number_format($retorno, 2, ',', '.') ?>
                                        </td>
                                      </tr> 
                                      </tr>
                                    </table>
                                    
                                    </div>
                                    
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        
                                        <?php
										  // c�lculo da margem de lucro do estoque
										  if($retorno > 0){
											$margem = ((($retorno - $investimento) / $retorno)) * 100; 
										  }
										  else{
											$margem = 0;   
										  }
										?>
                                        
                                        <span class="small text-white stretched-link">Margem de Lucro: <?= number_format($margem, 2, ',', '.') ?>%</span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                    Entradas e Sa&iacute;das &Uacute;ltimos 10 Dias</div>
                                    <div class="card-body">
                                    
                                    <div>
                                      <canvas id="chart_entradas_saidas" width="100%"></canvas>
                                    </div>
                                    
                                  </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Atividades no Sistema &Uacute;ltimos 10 Dias
                                    </div>
                                    <div class="card-body">
                                       <canvas id="chart_atividades" width="100%"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </main>
                
                <?php require("rodape.php"); ?>
                
            </div>
        </div>
        <script src="bootstrap-5.0.2/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="js/chart.js"></script>
    
    <?php
	     // rotina para constuir o gr�fico de entradas dos �ltimos 10 dias
		 $datas_entradas = "";
		 $valores_entradas = "";
		 $datas = array();
		 $date = new DateTime('now');
         date_sub($date,date_interval_create_from_date_string("10 days"));
         $datas[date_format($date,"d/m")] = 0;
		 for($i = 0; $i < 9; $i++){
		   date_add($date,date_interval_create_from_date_string("1 days"));
		   $datas[date_format($date,"d/m")] = 0;	 
		 }
		 
		 $result = mysqli_query($conexao, "select id, DATE_FORMAT(data_cadastro, '%d/%m') as data_cadastro from entradas
		   ORDER by id");
         if($result){
	        while($detalhes = mysqli_fetch_array($result)){
	          $data = utf8_encode($detalhes["data_cadastro"]);
			  if(isset($datas[$data])){
			    $datas[$data] = $datas[$data] + 1;
			  }
			}
		 }
		 
		 foreach($datas as $data => $valor) {
           $datas_entradas = $datas_entradas . "'" . $data . "', ";
		   $valores_entradas = $valores_entradas . $valor . ", ";
		 }
		 
		 // rotina para constuir o gr�fico de sa�das dos �ltimos 10 dias
		 $datas_saidas = "";
		 $valores_saidas = "";
		 $datas = array();
		 $date = new DateTime('now');
         date_sub($date,date_interval_create_from_date_string("10 days"));
         $datas[date_format($date,"d/m")] = 0;
		 for($i = 0; $i < 9; $i++){
		   date_add($date,date_interval_create_from_date_string("1 days"));
		   $datas[date_format($date,"d/m")] = 0;	 
		 }
		 
		 $result = mysqli_query($conexao, "select id, DATE_FORMAT(data_cadastro, '%d/%m') as data_cadastro from saidas
		   ORDER by id");
         if($result){
	        while($detalhes = mysqli_fetch_array($result)){
	          $data = utf8_encode($detalhes["data_cadastro"]);
			  if(isset($datas[$data])){
			    $datas[$data] = $datas[$data] + 1;
			  }
			}
		 }
		 
		 foreach($datas as $data => $valor) {
           $datas_saidas = $datas_saidas . "'" . $data . "', ";
		   $valores_saidas = $valores_saidas . $valor . ", ";
		 }
		 
		 // rotina para constuir o gr�fico de atividades dos �ltimos 10 dias
		 $datas_atividades = "";
		 $valores_atividades = "";
		 $datas = array();
		 $date = new DateTime('now');
         date_sub($date,date_interval_create_from_date_string("10 days"));
         $datas[date_format($date,"d/m")] = 0;
		 for($i = 0; $i < 9; $i++){
		   date_add($date,date_interval_create_from_date_string("1 days"));
		   $datas[date_format($date,"d/m")] = 0;	 
		 }
		 
		 $result = mysqli_query($conexao, "select id, DATE_FORMAT(data, '%d/%m') as data from logs
		   ORDER by id");
         if($result){
	        while($detalhes = mysqli_fetch_array($result)){
	          $data = utf8_encode($detalhes["data"]);
			  if(isset($datas[$data])){
			    $datas[$data] = $datas[$data] + 1;
			  }
			}
		 }
		 
		 foreach($datas as $data => $valor) {
           $datas_atividades = $datas_atividades . "'" . $data . "', ";
		   $valores_atividades = $valores_atividades . $valor . ", ";
		 }
	   ?>
    
    <script type="text/javascript">
	   function exibir_chart_entradas_saidas(){
	     const labels = [<?= $datas_entradas ?>];
         const data = {
           labels: labels,
           datasets: [{
             label: 'Entradas',
             backgroundColor: 'rgb(83, 109, 254)',
             borderColor: 'rgb(83, 109, 254)',
             data: [<?= $valores_entradas ?>],
           },
		   {
             label: 'Saidas',
             backgroundColor: 'rgb(124, 179, 66)',
             borderColor: 'rgb(124, 179, 66)',
             data: [<?= $valores_saidas ?>],
           }]
         };
	   
	     const config = {
           type: 'line',
           data: data,
             options: {}
         };

         var grafico = new Chart(
           document.getElementById('chart_entradas_saidas'), config
         );
	   }

       function exibir_chart_atividades_sistema(){
	     const labels = [<?= $datas_atividades ?>];
         const data = {
           labels: labels,
           datasets: [{
             label: 'Atividades no Sistema',
             backgroundColor: 'rgb(255, 109, 0)',
             borderColor: 'rgb(255, 109, 0)',
             data: [<?= $valores_atividades ?>],
           }]
         };
	   
	     const config = {
           type: 'line',
           data: data,
             options: {}
         };

         var grafico = new Chart(
           document.getElementById('chart_atividades'), config
         );
	   }

       exibir_chart_entradas_saidas();
	   exibir_chart_atividades_sistema();
	</script>
    
    </body>
</html>


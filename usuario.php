<?php
  session_start();
  require("config.php");
  // o usuário está logado?
  if(!isset($_SESSION["id_usuario_logado"])){
    header("Location: login.php");
    exit;
  }
  
  // id do usuário a ser visualizado
  if(isset($_GET["usuario"])){
     $id_usuario = (int)(trim($_GET["usuario"]));
  }
  else{
     $id_usuario = $_SESSION["id_usuario_logado"];
  }
  
  $result = mysqli_query($conexao, "select id, usuario, nome, email, telefone, nivel, foto, info, 
    DATE_FORMAT(ultimo_acesso, '%d/%m/%Y - %H:%i') as ultimo_acesso, 
    DATE_FORMAT(data_cadastro, '%d/%m/%Y - %H:%i') as data_cadastro, bloqueado, usuario_cadastro from usuarios WHERE id = '$id_usuario'");
  if($result){
	while($detalhes = mysqli_fetch_array($result)){
	   $usuario = utf8_encode($detalhes["usuario"]);
	   $nome = utf8_encode($detalhes["nome"]);
	   $foto = $detalhes["foto"];
	   $email = utf8_encode($detalhes["email"]);
	   $nivel = $detalhes["nivel"];
	   $telefone = $detalhes["telefone"];
	   $info = utf8_encode($detalhes["info"]);
	   $bloqueado = $detalhes["bloqueado"];
	   $ultimo_acesso = $detalhes["ultimo_acesso"];
	   $data_cadastro = $detalhes["data_cadastro"];
	   $usuario_cadastro = $detalhes["usuario_cadastro"];
	
	   if(file_exists($diretorio_fotos_usuarios . '/' . $foto . '.png')){
		  $foto = $url_fotos_usuarios . '/' . $foto . '.png';
	   }
	   else{
		  $foto = $url_base_aplicacao . '/imagens/usuario_sem_foto.jpg'; 
	    }
	}
  }
  
  // vamos registrar esse log
  $ip = getUserIpAddr();
  $id_usuario_logado = $_SESSION["id_usuario_logado"];
  $usuario_logado = $_SESSION["usuario_logado"];
  $texto_log = 'O usuario ' . $usuario_logado . ' acessou o cadastro do usuario ' . $usuario . '.';
  $result_2 = mysqli_query($conexao, "INSERT into logs values (null, '$id_usuario_logado', '$texto_log', 
    NOW(), '$usuario_logado', '$ip')");
  // fim registrar o log
  
  
  $foto_usuario = time();
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title><?= $titulo_aplicacao ?> - Detalhes do Usuário</title>
  <link rel="stylesheet" href="bootstrap-icons-1.5.0/font/bootstrap-icons.css" />
  <link href="css/styles.css?<?= date('Y-m-d_H:i:s'); ?>" rel="stylesheet" />
  
  <script src="js/jquery.min.js"></script>
  <link href="css/cropper.css" rel="stylesheet"/>
  <script src="js/cropper.js"></script>
  
  <script src="font-awesome/js/all.min.js" crossorigin="anonymous"></script>
  
  <style type="text/css">
    a{text-decoration:none} 
    .has-search .form-control {
    padding-left: 2.375rem;
    }

   .has-search .form-control-feedback {
    position: absolute;
    z-index: 2;
    display: block;
    width: 2.375rem;
    height: 2.375rem;
    line-height: 2.375rem;
    text-align: center;
    pointer-events: none;
    color: #aaa;
}

.blue-text {
    color: #00BCD4
}

.form-control-label {
    margin-bottom: 0
}

input,
textarea,
button {
    padding: 8px 15px;
    border-radius: 5px !important;
    margin: 5px 0px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    font-size: 18px !important;
    font-weight: 300
}

input:focus,
textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #00BCD4;
    outline-width: 0;
    font-weight: 400
}

.btn-block {
    text-transform: uppercase;
    font-size: 15px !important;
    font-weight: 400;
    height: 43px;
    cursor: pointer
}

.btn-block:hover {
    color: #fff !important
}

button:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    outline-width: 0
}

.image_area {
		  position: relative;
		}

		img {
		  	display: block;
		  	max-width: 100%;
		}

		.preview {
  			overflow: hidden;
  			width: 160px; 
  			height: 160px;
  			margin: 10px;
  			border: 1px solid red;
		}

		.modal-lg{
  			max-width: 1000px !important;
		}

		.overlay {
		  position: absolute;
		  bottom: 10px;
		  left: 0;
		  right: 0;
		  background-color: rgba(255, 255, 255, 0.5);
		  overflow: hidden;
		  height: 0;
		  transition: .5s ease;
		  width: 100%;
		}

		.image_area:hover .overlay {
		  height: 50%;
		  cursor: pointer;
		}

		.text {
		  color: #333;
		  font-size: 20px;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  -webkit-transform: translate(-50%, -50%);
		  -ms-transform: translate(-50%, -50%);
		  transform: translate(-50%, -50%);
		  text-align: center;
		}

  </style>
</head>

<body class="sb-nav-fixed">

<?php require("cima.php"); ?>

<div id="layoutSidenav">
           
           <?php require("menu_lado.php"); ?>
           
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        
                        <h1 class="mt-4"><i class="bi-person"></i> <?= $usuario ?></h1>
                        
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">P&aacute;gina Inicial</a></li>
                            <li class="breadcrumb-item active">Visualizar Usu&aacute;rio</li>
                        </ol>
                        
                        <?php if(isset($_GET["sucesso"])){ ?>
						
                        <div class="alert alert-success" role="alert">
                          O usuário foi atualizado com sucesso. Lembre-se de que alguns dados, tais como o código, o nome de usuário e e-mail, nao podem ser modificados.
                         </div>
                         
                        <?php } ?>
						
                        <div class="row">
                            <div class="col-xl-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="bi-clipboard-data"></i>
                                        Dados do Usuário
                                    </div>
                                    <div class="card-body">
                                    
                                    <form id="cadastro_usuario" nome="cadastro_usuario" action="atualizar_usuario_action.php" method="post" class="form-card">
                                    
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                         <label style="padding-left: 5px" class="form-control-label">C&oacute;digo<span class="text-danger"> *</span></label>
                                         <input required type="text" disabled="disabled" value="<?= $id_usuario ?>" id="id_usuario2" name="id_usuario2" placeholder="">
                                       </div>
                                       
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                         <label style="padding-left: 5px" class="form-control-label">Usu&aacute;rio<span class="text-danger"> *</span></label>
                                         <input required type="text" disabled="disabled" value="<?= $usuario ?>" id="usuario2" name="usuario2" placeholder="">
                                       </div>
                                    </div>
                    
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                         <label style="padding-left: 5px" class="form-control-label">Nome Completo<span class="text-danger"> *</span></label>
                                         <input onkeyup="atualizar_info_lado()" required type="text" id="nome" value="<?= $nome ?>" name="nome" placeholder="">
                                       </div>
                                       
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                          <label style="padding-left: 5px" class="form-control-label">E-Mail<span class="text-danger"> *</span></label>
                                          <input required type="text" id="email" disabled="disabled" value="<?= $email ?>" name="email" placeholder="">
                                       </div>
                                    </div>
                                    
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                         <label style="padding-left: 5px" class="form-control-label">Telefone<span class="text-danger"> *</span></label>
                                         <input onkeyup="atualizar_info_lado()" required type="text" id="telefone" value="<?= $telefone ?>" name="telefone" placeholder="">
                                       </div>
                                       
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                          <label style="padding-left: 5px" class="form-control-label">N&iacute;vel de Acesso<span class="text-danger"> *</span></label>
                                          <select name="nivel" <?= ($usuario == "admin" ? 'disabled="disabled"' : '') ?> id="nivel" style="margin-top: 10px" class="form-select">
                                             <?php
											   $niveis = Permissao::obterNiveis();
											   for($i = 1; $i <= 5; $i++){
											      if($i == $nivel){
												    echo '<option selected="selected" value="' . $i . '">' . $niveis[$i] . '</option>';
												  }
												  else{
													echo '<option value="' . $i . '">' . $niveis[$i] . '</option>';  
												  }
											   }
											 ?>
                                          </select>
                                          
                                       </div>
                                    </div>
                                       
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                         <label style="padding-left: 5px" class="form-control-label">Data de Cadastro<span class="text-danger"> *</span></label>
                                         <input required type="text" disabled="disabled" id="data_cadastro" value="<?= $data_cadastro ?>" name="data_cadastro" placeholder="">
                                       </div>
                                       
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                          <label style="padding-left: 5px" class="form-control-label">Último Acesso<span class="text-danger"> *</span></label>
                                          <input required type="text"  id="ultimo_acesso" disabled="disabled" value="<?= $ultimo_acesso ?>" name="ultimo_acesso" placeholder="">
                                       </div>
                                    </div>
                                    
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                         <label style="padding-left: 5px" class="form-control-label">Bloqueado<span class="text-danger"> *</span></label>
                                         
                                         <select name="bloqueado" <?= ($usuario == "admin" ? 'disabled="disabled"' : '') ?> id="bloqueado" style="margin-top: 10px" class="form-select">
                                             <?php
											   if($bloqueado == 'S'){
												  echo '<option selected="selected" value="S">Sim</option>';
												  echo '<option value="N">Nao</option>';
											   }
											   else{
												  echo '<option value="S">Sim</option>';
												  echo '<option selected="selected" value="N">Nao</option>'; 
											   }
											 ?>
                                          </select>
                                       </div>
                                       <div class="form-group col-sm-6 flex-column d-flex">
                                           <label style="padding-left: 5px" class="form-control-label">Usu&aacute;rio Cadastro<span class="text-danger"> *</span></label>
                                         <input required type="text" disabled="disabled" id="usuario_cadastro" value="<?= obterNomeUsuarioId($usuario_cadastro) ?>" name="usuario_cadastro" placeholder="">
                                       </div>
                                    </div> 
                                       
                                    <div class="row justify-content-between text-left">
                                       <div class="form-group col-12 flex-column d-flex">
                                          <label style="padding-left: 5px" class="form-control-label">Informa&ccedil;&otilde;es Adicionais<span class="text-danger"> *</span></label>
                                          <textarea onkeyup="atualizar_info_lado()" required class="form-control" name="info" id="info" rows="3"><?= $info ?></textarea>
                                       </div>
                                    </div>
                                    
                                    <div class="row justify-content-end">
                                       <div class="form-group col-12 flex-column d-flex">
                                          <div class="btn-group" role="group">
                                            
                                            <?php if(Permissao::podeAlterar($_SESSION["nivel_acesso"])){ ?>
                                              <button type="submit" name="gravar" style="margin-right: 8px; width: 180px" 
                                              class="btn-secondary">Atualizar Dados</button>
                                            <?php } ?>
                                            
                                            <?php if(Permissao::podeExcluir($_SESSION["nivel_acesso"])){ ?>
                                              <button type="button" <?= ($usuario == "admin" ? 'disabled="disabled"' : '') ?> 
                                              onclick="excluir_registro('<?= $id_usuario ?>', '<?= $usuario ?>')" name="excluir" 
                                              style="width: 180px" class="btn-secondary">Excluir Usu&aacute;rio</button>
                                            <?php } ?> 
                                          
                                          </div>
                                          <input name="id_usuario" type="hidden" id="id_usuario" value="<?= $id_usuario ?>">
                                          <input name="usuario" type="hidden" id="foto" value="<?= $usuario ?>">
                                          <input name="foto" type="hidden" id="foto" value="<?= $foto_usuario ?>">
                                       </div>
                                    </div>
                                    
                                    </form>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="bi-file-earmark-person"></i>
                                        Foto (Opcional)
                                    </div>
                                    <div class="card-body">
                                    
                                    <div style="background-image: url('imagens/fundo_fotos.jpg'); background-repeat: repeat-x; text-align:center" class="image_area">
								      <form method="post">
									    <label for="upload_image">
										   <img width="150px" src="<?= $foto ?>" id="uploaded_image" class="rounded-circle" style="width:200;" />
									     <div class="overlay">
											<div class="text">Click para trocar a imagem</div>
										  </div>
										<input type="file" name="image" class="image" id="upload_image" style="display:none" />
									    </label>
								      </form>
							        </div>
                                    
                                    <table width="100%">
                                      <tr>
                                        <td align="center"><span id="foto_id_usuario"><b><?= $usuario ?></b></span></td>
                                      </tr>
                                      <tr>  
                                        <td align="center"><span id="foto_nome_usuario"><?= $nome ?></span></td>
                                      </tr>
                                      <tr>
                                        <td align="center"><span id="foto_email_usuario"><?= $email ?></span></td>
                                      </tr>
                                      <tr>
                                        <td style="padding-top: 15px" align="center"><span id="foto_info_usuario"><?= $info ?></span></td>
                                      </tr>
                                    </table>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </main>
                
                <?php require("rodape.php"); ?>
                
            </div>
        
        
        <div id="janela_excluir_usuario" class="modal fade" role="dialog" style="width:100%">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content" >
            <div class="modal-header">Excluir</div>
            <div class="modal-body"><span id="msg_excluir"></span></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="confirmar_exclusao()">Confirmar</button>
                <button type="button" class="btn btn-secondary" onclick="fechar_exclusao()" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
    </div>
        
        
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">Recorte a imagem antes de salvar</h5>
									<button type="button" onclick="fecharCrop()" class="close" data-dismiss="modal" aria-label="Fechar">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="img-container">
										<div class="row">
											<div class="col-md-8">
												<img src="" id="sample_image" />
											</div>
											<div class="col-md-4">
												<div class="preview"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" id="crop" class="btn btn-primary">Recortar e Salvar</button>
									<button type="button" onclick="fecharCrop()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
								</div>
							</div>
						</div>
					</div>
  				</div>
        
        
        </div>
        <script src="bootstrap-5.0.2/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    
    
    <script type="text/javascript">

    var $modal = undefined; 

$(document).ready(function(){

	$modal = $('#modal');

	var image = document.getElementById('sample_image');

	var cropper;

	$('#upload_image').change(function(event){
		var files = event.target.files;

		var done = function(url){
			image.src = url;
			$('#crop').attr('disabled',false);
			$('#crop').html('Recortar e Salvar');
			$modal.modal('show');
		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 3,
			preview:'.preview'
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});

	$('#crop').click(function(){
		$('#crop').attr('disabled','disabled');
		$('#crop').html('<i class="fa fa-circle-o-notch fa-spin"></i> Processando...');
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});

		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
				$.ajax({
					url:'upload_usuarios.php',
					method:'POST',
					data:{image:base64data, nome_imagem: '<?= $foto_usuario ?>'},
					success:function(data)
					{
						$modal.modal('hide');
						$('#uploaded_image').attr('src', data);
					}
				});
			};
		});
	});
	
});

  function fecharCrop(){
	 $modal.modal('hide');
  }
  
  var $modal_excluir = $('#janela_excluir_usuario');
  var id_exclusao = 0;
  var nome_usuario_exclusao;
	   
  function excluir_registro(id, usuario){
	id_exclusao = id;
	nome_usuario_exclusao = usuario;
	$('#msg_excluir').html("Voce deseja mesmo excluir o usuario <b>" + usuario + "</b>?<br><br>Se houver lancamentos efetuados por este usuario a exclusao sera abortada.");
	$modal_excluir.modal('show');    
  }
	   
  function confirmar_exclusao(){
	 var url_volta = 'usuarios.php';
     window.location = "excluir_usuario.php?usuario=" + id_exclusao + "&nome_usuario=" + nome_usuario_exclusao + "&volta=" + url_volta;    
  }
	   
  function fechar_exclusao(){
	 $modal_excluir.modal('hide');
  }
  
  function atualizar_info_lado(){
	$('#foto_usuario').html('<b>' + $('#usuario').val() + '</b>');
	$('#foto_nome_usuario').html($('#nome').val());
	$('#foto_email_usuario').html($('#email').val());
	$('#foto_info_usuario').html($('#info').val());   
  }
</script>
    
    
    </body>
</html>


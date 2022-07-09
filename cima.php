<nav class="sb-topnav navbar navbar-expand navbar-dark bg-cima">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php"><img width="200px" src="imagens/logo-cima2.png"/></a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <div class="d-none d-md-inline-block ms-auto me-0 me-md-3 my-2 my-md-0">
            <span style="color:#e0e0e0">
            
            <?php
			  echo 'Voce está logado como <b>' . $_SESSION["usuario_logado"] . '</b> - IP: ' . getUserIpAddr();
			?>
            
            </span>
            </div>
            
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="usuario.php">Meus Dados</a></li>
                        <li><a class="dropdown-item" href="logs.php">Log de Atividades</a></li>
                        <li><a class="dropdown-item" href="alterar_senha.php">Alterar Senha</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="sair_action.php">Sair</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
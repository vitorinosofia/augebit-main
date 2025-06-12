<?php
// Inicia a sessão
session_start();

// Conexão com o banco de dados
$host = '127.0.0.1';
$dbname = 'augebit';
$username = 'root'; // Altere conforme seu usuário
$password = ''; // Altere conforme sua senha

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Verifica se o usuário está logado (simulação - você deve implementar seu sistema de login)
$usuario_id = 1; // ID do usuário Gabriel (admin) como exemplo
$usuario_nome = 'Gabriel';

// Busca os projetos do usuário
$stmt = $pdo->prepare("SELECT * FROM projetos WHERE cliente_id = ?");
$stmt->execute([$usuario_id]);
$projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca as tarefas relacionadas aos projetos do usuário
$tarefas_por_projeto = [];
foreach ($projetos as $projeto) {
    $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE projeto_id = ?");
    $stmt->execute([$projeto['id']]);
    $tarefas_por_projeto[$projeto['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* RESET & BASE STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            
        }
        
        body {
            background-color: #f8f9fa;
            line-height: 1.6;
             overflow-x: hidden;
        }
        
        @font-face {
            font-family: 'Poppins';
            src: url('../assets/fonte/Poppins-SemiBold.ttf') format('truetype');
            font-weight: 600;
        }
           
        @font-face {
            font-family: 'Poppins';
            src: url('../assets/fonte/Poppins-Regular.ttf') format('truetype');
            font-weight: 450;
        }

        @font-face {
            font-family: 'Poppins';
            src: url('../assets/fonte/Poppins-Medium.ttf') format('truetype');
            font-weight: 500;
        }

        @font-face {
            font-family: 'Poppins';
            src: url('../assets/fonte/Poppins-Italic.ttf') format('truetype');
            font-weight: 400;
            font-style: italic;
        }
        
        @font-face {
            font-family: 'Poppins';
            src: url('../assets/fonte/Poppins-ExtraLight.ttf') format('truetype');
            font-weight: 200;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: relative;
            z-index: 10;
            padding: 10px 0;
        }

        .logo-header {
            display: flex;
            align-items: center;
            
        }

        .logo-header img {
            position: relative;
            width: 70px;
            height: 80px;
            top: -16px; 
            left:30px
        }

        .logo-header h1 {
          position:relative; 
            font-size: 30px;
            font-family: 'Poppins';
            font-weight: 300;
            left: 30px;
        }

        nav ul {
            padding-right: 100px;
            display: flex;
            gap: 80px;
            list-style: none;
        }

        nav a {
            color: #3E236A;
            text-decoration: none;
            font-size: 19px;
            font-family: 'Poppins';
            transition: all 0.3s ease;
            font-weight: 450;
        }

        nav a:hover {
            color: #9999FF;
        }

        .active {
            color: #3E236A;
        }

        .acount {
            color: #3E236A;
        }

        .contato {
            color: #3E236A;
        }
       
        section {
            padding: 30px;
        }
        
        section:last-child {
            border-bottom: none;
        }
        
       .greeting {
    position: relative; /* Adicionado para posicionamento relativo */
    height: 300px;
    display: flex;
  left:500px;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    padding-top: 60px; /* Ajuste conforme necessário */
}

.new-project-btn {
    height: 30px;
    width: 250px;
    padding: 20px;
    font-family: 'Poppins';
    font-weight: 450;
    font-size: 15px;
    color: white;
    border-radius: 30px;
    background-color: #9999FF;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    margin-left:85px;
    margin-top: 20px; /* Espaço entre o texto e o botão */
    transition: background-color 0.3s ease;
}

.new-project-btn:hover {
    background-color: #3E236A;
}
        
        .greeting h1 {
            font-size: 40px;
            font-weight: 200;
            font-family: 'Poppins';
            padding-top: 100px; 
            padding-left:76px;
        }
        
        .username {
            color: #6741d9;
            font-weight: 500;
            font-family: 'Poppins';
            font-size: 40px;
        }
        
        /* PROJECTS SECTION */
        .projects-section {
            margin-top: 30px;
        }
        
        .projects-section h1 {
            font-size: 2rem;
            font-weight: 500;
            color: #3E236A;
            margin-bottom: 30px;
            font-family: 'Poppins';
            margin-left:30px
        }
        
        .project-cards {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
        
        .project-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }
        
        .project-card h2 {
            font-size: 1.4rem;
            font-weight: 500;
            color: #3E236A;
            margin-bottom: 15px;
            font-family: 'Poppins';
        }
        
        .project-card p {
            color: #6c757d;
            margin-bottom: 20px;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .project-card .access-btn {
            display: inline-block;
            background: none;
            color: #6741d9;
            font-weight: 500;
            text-decoration: none;
            padding: 8px 0;
            font-family: 'Poppins';
            font-size: 1rem;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }
        
        .project-card .access-btn:hover {
            border-bottom-color: #6741d9;
        }
        
        /* CONTACT SECTION */
        .contact h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
        }
        
        .contact p {
            color: #868e96;
            font-size: 1rem;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            nav ul {
                padding-right: 20px;
                gap: 30px;
            }
            
            .greeting {
                height: 200px;
            }
            
            .greeting h1 {
                font-size: 32px;
                padding-top: 50px;
            }
            
            .username {
                font-size: 32px;
            }
        }
        
        @media (max-width: 600px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            nav ul {
                padding: 20px 0;
                gap: 20px;
                flex-direction: column;
            }
            
            section {
                padding: 20px;
            }
            
            .projects-section h1 {
                font-size: 1.6rem;
            }
            
            .project-card h2 {
                font-size: 1.2rem;
            }
        }

       .contact {
    display: flex;          /* Turns into a flex container */
    align-items: center;    /* Vertically centers items */
    margin-bottom: 30px; 
    margin-top:30px;
       /* Adds space between sections */

     
}

.contact .text {
    flex: 1;   
    position;absolute;
    left:180px;             /* Takes available space, pushing button to the right */
}
.contact p{
  position: relative; 
  font-family:'Poppins';
  font-weight:400;
  color: #3E236A;
  padding-left: 99px; 
}

.contact h2{
  position:relative; 
  font-family: 'Poppins';
  font-weight:600px;
  color:  #3E236A;
  font-size:40px;
  padding-left:90px; 
}
.contact button {
  position:relative; 
  right: 200px;
    height: 30px;
    width:190px;
    padding: 20px;
    font-family: 'Poppins';
    font-weight: 400;
    font-size:15px;
    color: white;
    border-radius: 30px;
    background-color: #9999FF;
    border: none; 
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  transition: background-color 0.3s ease;
   
}
.contact button:hover {
  background-color: #3E236A;
}
.contato {
    margin-top: 20px;       /* Extra spacing if needed */
}

    /* Seção Contatos */
    .contatos {
    
      text-align: center;
      justify-content: center;
      padding: 90px 70px;
      height: 300px;
    }

    .contato-section {
  position: relative;
  z-index: 10; 
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      max-width: 1000px;
      margin: 0 auto;
    }
    .contato-section h3{
      font-family:"Poppins";
      font-weight:500; 
    }

    .trabalhos p{
      font-family: ' Poppins';
      font-weight:300;
    }
    .texto h3 {

      position: relative;
      color: #3e206e;
      font-size: 1.8rem;
      text-align: left;
      text-transform: none;
      letter-spacing: normal;
      
    }

    .redes {
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .rede {
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: var(--transicao);
      padding: 20px;
      border-radius: 10px;
    }

    .rede:hover {
      background: rgba(126, 117, 227, 0.1);
      transform: translateY(-5px);
    }

    .rede img {
      width: 40px;
      height: 40px;
      margin-bottom: 10px;
    }

    .rede p {
      font-weight: 400;
      color:  #9999FF
      ;
      font-size: 0.9rem;
      font-family: 'Poppins';
    }

    /*dverdade*/

.dverdade {
  width: 100%;
  min-height: 400px;
  height: auto;
  padding: 40px 20px;
  display: flex;
  align-items: center;
  justify-content: center;

}
.augebit{
  width: 700px;
}

.dverdade p {
  font-family: 'Medium';
  font-size: 20px;
  text-align: center;
}
.dverdade em {
  color: #ac89e6;
  font-style: normal;
  font-weight: 200
}
.rodape {
  background-color: #9999FF;
  width: 100vw; /* Usa a largura total da viewport */
  min-height: 300px;
  padding: 60px 15px;
  color: #ffffff;
  display: flex;
  justify-content: space-around;
  align-items: center;
  flex-wrap: wrap;
  gap: 40px;
  position: relative;
  margin-top: 80px;
  left: 0;
  box-sizing: border-box;
}


.logo-rodape {
  text-align: center;
  display: flex;
  padding-left: 50px;
  flex-direction: column;
  align-items: center;
}

.logo-rodape img {
  width: 100px;
  margin-left: 20px;
  margin-bottom: 15px;
}

.logo-rodape h1 {
  font-size: 1.8rem;
  font-family: 'Poppins';
  margin-bottom: 5px;
  color: #ffffff;
  font-weight: 500;
  margin-left: 25px;
}

.logo-rodape p {
  font-size: 0.9rem;
  margin-left: 25px;
  font-family: 'Poppins';
   font-weight: 300;
}

.pages {
  margin-left: -110px;
}
.pages a {
  display: block; /* Faz cada link ocupar sua própria linha */
  margin-bottom: 15px;
  color: white;
  text-decoration: none;
  font-size: 0.9rem;
  font-family:"Poppins";
  font-weight:300; 
  transition: all 0.4s cubic-bezier(0.65, 0, 0.35, 1);
  position: relative;
}

.pages a::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 1px;
  background: white;
  transition: all 0.4s cubic-bezier(0.65, 0, 0.35, 1);
}

.pages a:hover::after {
  width: 100%;
}

/* Mantenha o estilo existente para mobile */
@media (max-width: 768px) {
  .pages a {
    margin-bottom: 10px;
    font-size: 0.8rem;
  }
}

.redescociais {
  display: flex;
  gap: 20px;
  align-items: center;
}

.redescociais img {
  width: 25px;
  height: 25px;
  filter: brightness(0) invert(1);
  transition: all 0.3s ease;
  cursor: pointer;
}

.redescociais img:hover {
  transform: scale(1.2);
}
    </style>
</head>
<body>
    <header>
        <div class="logo-header">    
            <img src="../assets/img/augebit.logo.png" alt="Logo da empresa"> 
         
        </div>    
        <nav>       
            <ul>         
                <li><a href="#" class="active">Home</a></li>         
                <li><a href="#quem-somos" class="acount">Projetos</a></li>         
                <li><a href="#contato-section" class="contato">Sair</a></li>    
            </ul>     
        </nav>   
    </header>    
    <div class="dashboard">
        <section class="greeting">
            <h1>Olá <span class="username"><?php echo htmlspecialchars($usuario_nome); ?></span></h1>
            <button class="new-project-btn">Solicitar novo projeto</button>
        </section>
        
        <section class="projects-section">
            <h1>Seus Projetos</h1>
            
            <div class="project-cards">
                <?php if (count($projetos) > 0): ?>
                    <?php foreach ($projetos as $projeto): ?>
                        <div class="project-card">
                            <h2><?php echo htmlspecialchars($projeto['titulo']); ?></h2>
                            <p><?php echo htmlspecialchars($projeto['descricao']); ?></p>
                            <p><strong>Status:</strong> <?php echo ucfirst(str_replace('_', ' ', $projeto['status'])); ?></p>
                            
                            <?php if (isset($tarefas_por_projeto[$projeto['id']])): ?>
                                <div class="tarefas">
                                    <h3>Tarefas:</h3>
                                    <ul>
                                        <?php foreach ($tarefas_por_projeto[$projeto['id']] as $tarefa): ?>
                                            <li>
                                                <?php echo htmlspecialchars($tarefa['titulo']); ?> - 
                                                <span class="status-<?php echo $tarefa['status']; ?>">
                                                    <?php echo ucfirst(str_replace('_', ' ', $tarefa['status'])); ?>
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <a href="projeto.php?id=<?php echo $projeto['id']; ?>" class="access-btn">Acessar</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="project-card">
                        <h2>Nenhum projeto encontrado</h2>
                        <p>Você ainda não possui projetos cadastrados.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        
        <section class="contact">
            <div class="text">
                <h2>Precisa conversar?</h2>
                <p>Acesse o chat</p>
            </div>
            <button>Acesse aqui</button>
        </section>

        <section id="contato-section" class="contatos">
            <div class="contato-section">
                <div class="texto">
                    <h3>Entre em Contato</h3>
                </div>
                <div class="redes">
                    <div class="rede">
                        <img src="../assets/img/emailroxo.png" alt="Email">
                        <p>Email</p>
                    </div>
                    <div class="rede">
                        <img src="../assets/img/linkedinroxo.png" alt="Linkedin">
                        <p>Linkedin</p>
                    </div>
                    <div class="rede">
                        <img src="../assets/img/zaproxo.png" alt="Whatsapp">
                        <p>Whatsapp</p>
                    </div>
                    <div class="rede">
                        <img src="../assets/img/instaroxo.png" alt="Instagram">
                        <p>Instagram</p>
                    </div>
                </div>
            </div>
        </section>

  <footer class="rodape">
    <div class="pages">
       <a href="index.html">Home</a>
  <a href="projetos.html">Projetos</a>
  <a href="#contato-section">Entre em contato</a>
   <a href="logout.php">Sair</a>
    </div>
    <div class="logo-rodape">
      <img src="../assets/img/logobranca.png" alt="Logo Augebit">
      <h1>AUGEBIT</h1>
      <p>Industrial design</p>
    </div>
    <div class="redescociais">
      <img src="../assets/img/emailbranco.png" alt="Email">
      <img src="../assets/img/instabranco.png" alt="Instagram">
      <img src="../assets/img/linkedinbranco.png" alt="Linkedin">
      <img src="../assets/img/zapbranco.png" alt="Whatsapp">
    </div>
  </footer>
    </div>
</body>
</html>
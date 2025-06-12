<?php
require_once '../conexao.php';

// Verifica se a conexão foi estabelecida
if (!$conn) {
    die("Erro de conexão: " . $conn->connect_error);
}

try {
    // Busca todos os funcionários com tratamento de erro
    $query = "
        SELECT 
            u.id, u.nome, u.email,
            (SELECT COUNT(*) FROM tarefas t WHERE t.funcionario_id = u.id) AS total_tarefas,
            (SELECT COUNT(*) FROM tarefas t WHERE t.funcionario_id = u.id AND t.status = 'em_progresso') AS em_progresso,
            (SELECT COUNT(*) FROM tarefas t WHERE t.funcionario_id = u.id AND t.status = 'concluido') AS concluidas
        FROM usuarios u
        WHERE u.tipo = 'funcionario'
        ORDER BY u.nome
    ";
    
    $funcionarios = $conn->query($query);
    
    if (!$funcionarios) {
        throw new Exception("Erro na consulta: " . $conn->error);
    }

} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários | AUGEBIT</title>
    <style>
        /* Estilos Gerais */
        :root {
            --primary-color: #9999FF;
            --secondary-color: #9999FF;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --border-radius: 6px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
           
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark-color);
            line-height: 1.6;
              font-family: 'Poppins';
            font-weight: 400; 
          
        }
  @font-face {
            font-family: 'Poppins';
            src: url('../assets/fontes/Poppins/Poppins-SemiBold.ttf') format('truetype');
            font-weight: 600;
        }

        @font-face {
            font-family: 'Medium';
            src: url('../assets/fontes/Poppins/Poppins-Medium.ttf') format('truetype');
            font-weight: 500;
        }

        @font-face {
            font-family: 'Poppins';
            src: url('../assets/fontes/Poppins/Poppins-Italic.ttf') format('truetype');
            font-weight: 400;
            font-style: italic;
        }

        h2{
             font-family: 'Poppins';
            font-weight: 500; 
            font-weight:100;
            color: #3E236A; 
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
            
        }

        /* Header */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
            font-family: 'Poppins';
            font-weight: 400; 
        }

        .header-container h2 {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 600;
        }

        /* Botões */
        .add-button {
            background-color: var(--secondary-color);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
        }

        .add-button:hover {
            background-color: #3E236A;
            transform: translateY(-2px);
            box-shadow: var(--box-shadow);
        }

        /* Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            background-color: white;
            box-shadow: var(--box-shadow);
            border-radius: 30px;
            overflow: hidden;
        }

        thead {
            background-color: var(--primary-color);
            color: white;
        }

        th, td {
            padding: 1rem;
            text-align: left;
        }

        th {
            font-weight: 600;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            transition: var(--transition);
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Badges de Status */
        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-badge.progress {
            background-color:rgba(124, 124, 203, 0.21);
            color:rgb(124, 124, 203);
        }

        .status-badge.completed {
            background-color: rgba(46, 204, 113, 0.2);
            color: #27ae60;
        }

        /* Links de Ação */
        td a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
.img{
width: 50px;
height:50px;
}
        td a:hover {
            color: #3E236A;
            text-decoration: underline;
        }

        /* Sem Resultados */
        .no-results {
            text-align: center;
            padding: 3rem;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .no-results p {
            margin-bottom: 1.5rem;
            color: #7f8c8d;
            font-size: 1.1rem;
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

        /* Responsividade */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            th, td {
                padding: 0.8rem;
            }
            
            .no-results {
                padding: 2rem 1rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 10px;
            }
            
            .header-container h2 {
                font-size: 1.5rem;
            }
            
            .add-button {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            
            th, td {
                padding: 0.6rem;
                font-size: 0.9rem;
            }
            
            .status-badge {
                font-size: 0.75rem;
            }
            
            td a {
                font-size: 0.85rem;
            }



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
        .container{
            margin-top:120px;

        }
        h2 {
            color: var(--primary-dark);
            margin-bottom: 25px;
            margin-left:290px; 
            font-size: 24px;
            border-bottom: 2px solid var(--secondary-light);
            padding-bottom: 10px;
        }

        .header-container img{
height: 30px;
width:30px; 
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
  margin-top: 180px;
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

.pages p {
  margin-bottom: 15px;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  font-size: 0.9rem;
  font-family: 'Poppins';
  font-weight: 400;
}

.pages p::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 1px;
  background: #ffffff;
  transition: all 0.3s ease;
}

.pages p:hover::after {
  width: 100%;
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
                <li><a href="#contato-section" class="contato">Conecte-se</a></li>    
            </ul>     
        </nav>   
    </header>
    <div class="container">
        <div class="header-container">
            <h2> Funcionários Cadastrados</h2>
            <a href="cadastrar_funcionario.php" class="add-button">
             <img src="../assets/img/mais.png" alt="Adicionar" width="16">  Adicionar Funcionário
            </a>
        </div>

        <?php if ($funcionarios->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Total de Tarefas</th>
                        <th>Em Progresso</th>
                        <th>Concluídas</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($f = $funcionarios->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['nome']) ?></td>
                            <td><?= htmlspecialchars($f['email']) ?></td>
                            <td><?= $f['total_tarefas'] ?></td>
                            <td>
                                <span class="status-badge progress"><?= $f['em_progresso'] ?></span>
                            </td>
                            <td>
                                <span class="status-badge completed"><?= $f['concluidas'] ?></span>
                            </td>
                            <td>
                                <a href="../tarefas/listar_tarefas.php?funcionario_id=<?= $f['id'] ?>">
                                  <img src="../assets/img/lupa.png" alt="Ver Tarefas" width="16"> Ver Tarefas
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-results">
                <p>Nenhum funcionário cadastrado ainda.</p>
                <a href="cadastrar_funcionario.php" class="add-button">
                    ➕ Cadastrar Primeiro Funcionário
                </a>
            </div>
        <?php endif; ?>
    </div>
    <footer class="rodape">
    <div class="pages">
      <p>Home</p>
      <p>Quem Somos</p>
      <p>Nossos serviços</p>
      <p>Entre em contato</p>
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
</body>
</html>
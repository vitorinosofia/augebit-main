<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Navegação</title>
    <style>
        @font-face {
            font-family: 'Poppins';
            src: url('../assets/fontes/Poppins/Poppins-SemiBold.ttf') format('truetype');
            font-weight: 600;
        }

        @font-face {
            font-family: 'Poppins';
            src: url('../assets/fontes/Poppins/Poppins-Medium.ttf') format('truetype');
            font-weight: 500;
        }

        @font-face {
            font-family: 'Poppins';
            src: url('../assets/fontes/Poppins/Poppins-Italic.ttf') format('truetype');
            font-weight: 400;
            font-style: italic;
        }

        body {
            font-family: 'Poppins'; 
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
          
            font-weight: 500;
        }

        .sidebar {
            width: 220px;
            padding: 20px;
            border-radius: 55px;
            background-color:rgba(153, 153, 255, 0.89); 
         opacity: 90%;
            box-shadow: 20px 20px 20px rgba(0, 0, 0, 0.05);
            height: 390px;
            position: absolute;
            left: 40px;
            top: 145px;
            display: flex;
            flex-direction: column;
            align-items: center;
          
        }

        .logo {
            width: 93px;
            height: 93px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            left: 173px;
            transform: translateX(-50%);
            top: 20px; /* Ajuste este valor para mover para baixo */
            z-index: 10;
        }

        .logo img {
            width: 100%;
            height: auto;
            border-radius: 50%;
            object-fit: contain;
        }


        .menu-title {
            color: #333;
            font-size: 18px;
            font-weight: 580;
            margin-bottom: 25px;
            padding-bottom: 10px;
            text-align: center;
            width: 100%;
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .menu-item {
            margin-bottom: 15px;
        }

        .menu-link {
            color: #3E236A;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 15px;
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .menu-link:hover {
          
            color:rgb(255, 255, 255);
        }

        .menu-link:hover .menu-icon img {
            filter: brightness(0) invert(1);
        }

        .menu-icon {
            margin-right: 12px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-icon img {
            width: 100%;
            height: auto;
            object-fit: contain;
            transition: all 0.2s ease;
        }

        .logout .menu-link {
            color: #3e206e;
        }

        .logout .menu-link:hover {
         
            color:rgb(255, 255, 255);
        }
    </style>
</head>
<body>
    <?php
    $menuItems = array(
        array(
            'icon' => 'tarefas.png',
            'label' => 'Tarefas',
            'url' => '#'
        ),
        array(
            'icon' => 'documentacao.png',
            'label' => 'Documentação',
            'url' => '#'
        ),
        array(
            'icon' => 'clientes.png',
            'label' => 'Clientes',
            'url' => '#'
        ),
        array(
            'icon' => 'perfil.png',
            'label' => 'Seu perfil',
            'url' => '#'
        ),
        array(
            'icon' => 'sair.png',
            'label' => 'Sair',
            'url' => '#',
            'class' => 'logout'
        )
    );
    ?>
    
      <div class="logo">
            <img src="../assets/imgs/augebit.logo.png" alt="Logo" style="width: 100%; height: auto; border-radius: 50%;">
        </div>
    <div class="sidebar">
      
        <h3 class="menu-title">Navegue</h3>
        
        <ul class="menu-list">
            <?php foreach ($menuItems as $item): ?>
                <li class="menu-item <?= isset($item['class']) ? $item['class'] : '' ?>">
                    <a href="<?= $item['url'] ?>" class="menu-link">
                        <span class="menu-icon">
                            <img src="../assets/imgs/<?= $item['icon'] ?>" alt="<?= $item['label'] ?>">
                        </span>
                        <span><?= $item['label'] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
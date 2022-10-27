<?php
    $titulo="- Escolha o nível";
    include("../head-foot/header.php");
    
    if(!isset($_GET["tipo"]))
        die;
?>

   <h2 class="mb-5 text-center white-dark">Qual seu nível de uso?</h2>
   <div class="container mb-5 d-flex align-items-center justify-content-evenly flex-wrap">
      <div class="rotate-card mb-5 mb-md-0">
         <div class="rotate-card-inner">
            <div class="front-side rounded-3 d-flex flex-column align-items-center">
               <img src="../img/pcs/nivel/<?php if($_GET["tipo"]==0) echo "desktop-1.png"; else echo "notebook-1.png"; ?>" class="mb-3" alt="">
               <h2 class="dark-white d-flex align-items-center">
                  <i class="bi bi-<?php if($_GET["tipo"]==0) echo "pc-display"; else echo "laptop"; ?> color-pink me-2 fs-3"></i>
                  Básico
               </h2>
               <p class="d-flex align-items-center dark-white">
                  <i class="bi bi-arrow-clockwise me-2 fs-5 color-pink"></i> Passe o mouse
               </p>
            </div>
            <div class="left-side rounded-3 d-flex flex-column align-items-center p-4">
               <h2 class="dark-white d-flex align-items-center mb-4">
                  <i class="bi bi-<?php if($_GET["tipo"]==0) echo "pc-display"; else echo "laptop"; ?> color-pink me-2 fs-2"></i>
                  Básico
               </h2>
               <div class="description-pc mb-3 w-100 d-flex flex-column align-items-start">
                  <b class="mb-1 fs-4 dark-white">Ideal para</b>
                  <p class="dark-white mb-3">Navegar na internet, criar arquivos de texto, planilhas, slides, checar emails, realiar reuniões de vídeo e jogar jogos leves como: LOL, Cuphead, The sims 4, Undertale, Minecraft, etc.</p>
                  <b class="mb-1 fs-4 dark-white">Indicado para</b>
                  <ul class="dark-white">
                    <li class="mb-1">Estudantes</li>
                    <li class="mb-1">Professores</li>
                    <li class="mb-1">Trabalhadores autônomos</li>
                    <li class="mb-1">Pessoas novas no mundo da tecnologia</li>
                  <ul>
               </div>
               <a href="result.php?tipo=<?php echo $_GET["tipo"] ?>&nivel=0" class="btn background-pink hover text-white text-center col-12 fs-4">
                  Escolher
               </a>
            </div>
         </div>
      </div>
      <div class="rotate-card mb-5 mb-md-0">
         <div class="rotate-card-inner">
            <div class="front-side rounded-3 d-flex flex-column align-items-center">
               <img src="../img/pcs/nivel/<?php if($_GET["tipo"]==0) echo "desktop-2.png"; else echo "notebook-2.png"; ?>" class="mb-3" alt="">
               <h2 class="dark-white d-flex align-items-center">
                  <i class="bi bi-<?php if($_GET["tipo"]==0) echo "pc-display"; else echo "laptop"; ?> color-pink me-2 fs-3"></i>
                  Médio
               </h2>
               <p class="d-flex align-items-center dark-white">
                  <i class="bi bi-arrow-clockwise me-2 fs-5 color-pink"></i> Passe o mouse
               </p>
            </div>
            <div class="left-side rounded-3 d-flex flex-column align-items-center p-4">
               <h2 class="dark-white d-flex align-items-center mb-4">
                  <i class="bi bi-<?php if($_GET["tipo"]==0) echo "pc-display"; else echo "laptop"; ?> color-pink me-2 fs-2"></i>
                  Médio
               </h2>
               <div class="description-pc mb-3 w-100 d-flex flex-column align-items-start">
                  <b class="mb-1 fs-4 dark-white">Ideal para</b>
                  <p class="dark-white mb-3">Multitarefas, desenvolver sistemas leves, edições de vídeo e 3D leves, gerenciamento de negócios como: controle de gastos, gestão da empresa, etc; e jogar jogos médios como: CS:GO, PUBG Lite, GTA V, Far Cry 4, Dark Souls 2, WRC 8, Devil May Cry, etc.</p>
                  <b class="mb-1 fs-4 dark-white">Indicado para</b>
                  <ul class="mb-3 dark-white align-self-start">
                     <li class="mb-1">Gerentes de empresa</li>
                     <li class="mb-1">Estudantes de TI</li>
                     <li class="mb-1">Desenvolvedores de software</li>
                     <li class="mb-1">Editores de fotos e vídeos</li>
                     <li class="mb-1">Gamers Casuais</li>
                  </ul>
               </div>
               <a href="result.php?tipo=<?php echo $_GET["tipo"] ?>&nivel=1" class="btn background-pink hover text-white text-center col-12 fs-4">
                  Escolher
               </a>
            </div>
         </div>
      </div>
      <div class="rotate-card">
         <div class="rotate-card-inner">
            <div class="front-side rounded-3 d-flex flex-column align-items-center">
               <img src="../img/pcs/nivel/<?php if($_GET["tipo"]==0) echo "desktop-3.png"; else echo "notebook-3.png"; ?>" class="mb-3" alt="">
               <h2 class="dark-white d-flex align-items-center">
                  <i class="bi bi-<?php if($_GET["tipo"]==0) echo "pc-display"; else echo "laptop"; ?> color-pink me-2 fs-3"></i>
                  Gamer/pesado
               </h2>
               <p class="d-flex align-items-center dark-white">
                  <i class="bi bi-arrow-clockwise me-2 fs-5 color-pink"></i> Passe o mouse
               </p>
            </div>
            <div class="left-side rounded-3 d-flex flex-column align-items-center p-4">
               <h2 class="dark-white d-flex align-items-center mb-4">
                  <i class="bi bi-<?php if($_GET["tipo"]==0) echo "pc-display"; else echo "laptop"; ?> color-pink me-2 fs-2"></i>
                  Gamer/pesado
               </h2>
               <div class="description-pc mb-3 w-100 d-flex flex-column align-items-start">
                  <b class="mb-1 fs-4 dark-white">Ideal para</b>
                  <p class="dark-white mb-3">Edições de vídeo e 3D avançadas, desenvolvimento de sistemas grandes, trabalhar com várias aplicações abertas e jogar jogos pesados como: Assassin's Creed Valhalla, COD Warzone, Red Dead Redemption 2, CyberPunk 2077, Final Fantasy XV, etc.</p>
                  <b class="mb-1 fs-4 dark-white">Indicado para</b>
                  <ul class="mb-3 dark-white align-self-start">
                     <li class="mb-1">Desenvolvedores de software avançados</li>
                     <li class="mb-1">Gamers profissionais</li>
                     <li class="mb-1">Editores de imagem e vídeos</li>
                     <li class="mb-1">Modeladores 3D</li>
                  </ul>
               </div>
               <a href="result.php?tipo=<?php echo $_GET["tipo"] ?>&nivel=2" class="btn background-pink hover text-white text-center col-12 fs-4">
                  Escolher
               </a>
            </div>
         </div>
      </div>
   </div>

<?php
    include("../head-foot/footer.php");
?>
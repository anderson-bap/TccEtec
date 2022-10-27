<?php
    $titulo="- Tipo de computador";
    include("../head-foot/header.php");
?>

   <h2 class="mb-5 text-center white-dark">Qual tipo você quer?</h2>
   <div class="container mb-5 d-flex align-items-center justify-content-evenly flex-wrap">
      <div class="rotate-card mb-5 mb-md-0">
         <div class="rotate-card-inner">
            <div class="front-side rounded-3 d-flex flex-column align-items-center">
               <img src="../img/pcs/desktop.png" class="mb-3" alt="">
               <h2 class="dark-white d-flex align-items-center">
                  <i class="bi bi-pc-display color-pink me-2 fs-3"></i>
                  Desktop
               </h2>
               <p class="d-flex align-items-center dark-white">
                  <i class="bi bi-arrow-clockwise me-2 fs-5 color-pink"></i> Passe o mouse
               </p>
            </div>
            <div class="left-side rounded-3 d-flex flex-column align-items-center p-4">
               <h2 class="dark-white d-flex align-items-center mb-4">
                  <i class="bi bi-pc-display color-pink me-2 fs-2"></i>
                  Desktop
               </h2>
               <div class="description-pc mb-3 w-100 d-flex flex-column align-items-start">
                  <b class="mb-1 fs-4 dark-white">Pros</b>
                  <ul class="mb-3 dark-white align-self-start">
                     <li class="mb-1">Altamente customizável</li>
                     <li class="mb-1">Melhor refrigeração dos componentes internos</li>
                     <li class="mb-1">Manutenção mais simples</li>
                  </ul>
                  <b class="mb-1 fs-4 dark-white">Contras</b>
                  <ul class="mb-3 dark-white align-self-start">
                     <li class="mb-1">Não oferece mobilidade</li>
                     <li class="mb-1">Maior peso</li>
                     <li class="mb-1">Periféricos como monitor, teclado e mouse devem ser comprados a parte</li>
                  </ul>
               </div>
               <a href="nivel.php?tipo=0" class="btn background-pink hover text-white text-center col-12 fs-4">
                  Escolher
               </a>
            </div>
         </div>
      </div>
      <div class="rotate-card">
         <div class="rotate-card-inner">
            <div class="front-side rounded-3 d-flex flex-column align-items-center">
               <img src="../img/pcs/notebook.png" class="mb-3" alt="">
               <h2 class="dark-white d-flex align-items-center">
                  <i class="bi bi-laptop color-pink me-2 fs-3"></i>
                  Notebook
               </h2>
               <p class="d-flex align-items-center dark-white">
                  <i class="bi bi-arrow-clockwise me-2 fs-5 color-pink"></i> Passe o mouse
               </p>
            </div>
            <div class="left-side rounded-3 d-flex flex-column align-items-center p-4">
               <h2 class="dark-white d-flex align-items-center mb-4">
                  <i class="bi bi-laptop color-pink me-2 fs-2"></i>
                  Notebook
               </h2>
               <div class="description-pc mb-3 w-100 d-flex flex-column align-items-start">
                  <b class="mb-1 fs-4 dark-white">Pros</b>
                  <ul class="mb-3 dark-white align-self-start">
                     <li class="mb-1">Oferece muita mobilidade</li>
                     <li class="mb-1">Compacto</li>
                     <li class="mb-1">Já possui mouse, teclado, monitor e caixas de som</li>
                  </ul>
                  <b class="mb-1 fs-4 dark-white">Contras</b>
                  <ul class="mb-3 dark-white align-self-start">
                     <li class="mb-1">Refrigeração dos componentes internos pior que a de um desktop</li>
                     <li class="mb-1">poucas opções de customização</li>
                     <li class="mb-1">Manutenção mais cara e trabalhosa</li>
                  </ul>
               </div>
               <a href="nivel.php?tipo=1" class="btn background-pink hover text-white text-center col-12 fs-4">
                  Escolher
               </a>
            </div>
         </div>
      </div>
   </div>

<?php
    include("../head-foot/footer.php");
?>
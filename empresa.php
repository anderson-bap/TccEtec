<?php
    $titulo="- Informações sobre a empresa";
    include("head-foot/header.php");
?>
<h2 class="mb-5 text-center white-dark" id="historia">Informações da empresa</h2>
   
   <h3 class="white-dark container mb-4 d-flex align-items-center">História<div class="w-100 border-bottom-pink ms-4"></div></h3>
   <p class="mb-2 white-dark container">A empresa “Bufos Regulares” foi fundada em 2021 por estudantes do curso Modular com Habilitação Profissional de Técnico em Desenvolvimento de Sistemas da escola técnica Etec de Itaquera, na cidade de São Paulo, para realização do trabalho de conclusão de curso.</p>
   <p class="mb-5 white-dark container">Com a relevância de seu primeiro projeto, essa equipe super profissional, dedicada e com ótima comunicação, fundou uma empresa com o intuito de amadurecimento profissional e proporcionar ajuda à pessoas sem conhecimento prévio em informática, graças ao apoio de seus professores.</p>

   <h3 class="white-dark container mb-4 d-flex align-items-center">Missão<div class="w-100 border-bottom-pink ms-4"></div></h3>
   <p class="mb-5 white-dark container">Inovar o atual setor de venda de computadores pessoais introduzindo um sistema que auxilia nossos clientes a escolher o pc ideal para seu uso e orçamento.</p>

   <h3 class="white-dark container mb-4 d-flex align-items-center">Valores<div class="w-100 border-bottom-pink ms-4"></div></h3>
   <div class="row mb-5 container mx-auto p-0">
      <div class="col-12 col-md-6">
         <ul class="white-dark">
            <li class="mb-2">Inovação</li>
            <li class="mb-2">Liderança</li>
            <li class="mb-2">Responsabilidade</li>
            <li class="mb-2">Integridade</li>
         </ul>
      </div>
      <div class="col-12 col-md-6">
         <ul class="white-dark">
            <li class="mb-2">Paixão</li>
            <li class="mb-2">Colaboração</li>
            <li class="mb-2">Diversidade</li>
            <li class="mb-2">Qualidade</li>
         </ul>
      </div>
   </div>

   <h3 class="white-dark container mb-4 d-flex align-items-center">Visão<div class="w-100 border-bottom-pink ms-4"></div></h3>
   <p class="mb-5 white-dark container">Tornar a compra de um computador mais segura e acessível.</p>
   
   <h3 class="white-dark container mb-4 d-flex align-items-center">Endereço<div class="w-100 border-bottom-pink ms-4"></div></h3>
   <div class="container d-flex flex-column align-items-center mb-5">
      <iframe class="endereco-empresa mb-1 rounded-3" src="https://www.google.com/maps/embed?pb=!4v1656025489841!6m8!1m7!1sqN88zd6EKQsHoRnmioip3A!2m2!1d-23.54698523617513!2d-46.43923010305565!3f89.09441682248709!4f1.5436665667577927!5f0.7820865974627469" width="1500" height="700" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      <span class="align-self-start color-secondary-light-dark" id="creators">R. Virgínia Ferni, 400 - Itaquera, São Paulo - SP, 08253-000</span>
   </div>
   
   <h3 class="white-dark container mb-4 d-flex align-items-center">Fundadores<div class="w-100 border-bottom-pink ms-4"></div></h3>

   <div class="container d-flex flex-column align-items-center creators mb-5">
      <div class="first-line mb-5 d-flex align-items-center justify-content-center flex-wrap">
         <div class="flip-card">
            <div class="flip-card-inner"
            onclick="
               if(this.getAttribute('checked')==null){
                  this.style.transform='rotateY(180deg)';
                  this.setAttribute('checked','');
               }else{
                  this.style.transform='rotate(0deg)';
                  this.removeAttribute('checked');
               }
            ">
               <div class="flip-card-front d-flex flex-column align-items-center p-4">
                  <img class="border-pink mb-2" src="../img/persons/alberto.png" alt="">
                  <h4 class="dark-white mb-1">Alberto</h4>
                  <h4 class="dark-white mb-3">Frigatto</h4>
                  <h5 class="color-pink mb-3">CEO</h5>
                  <p class="d-flex align-items-center dark-white">
                     <i class="bi bi-arrow-clockwise me-2 fs-5 color-pink"></i> Clique para ver mais
                  </p>
               </div>
               <div class="flip-card-back d-flex align-items-center flex-column p-4">
                  <i class="bi bi-arrow-counterclockwise fs-5 color-pink"></i>
                  <h4 class="dark-white mb-1">Alberto</h4>
                  <h4 class="dark-white mb-3">Frigatto</h4>
                  <p class="dark-white mb-4">CEO e desenvolvedor de software há 15 anos, possui experiência com aplicações, relações exteriores, e atendimento ao público.</p>
                  <div class="d-flex align-items-center justify-content-between fs-2 w-100">
                     <a href="">
                        <i class="bi bi-facebook color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-instagram color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-pinterest color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-linkedin color-pink"></i>
                     </a>
                  </div>
               </div>
            </div>
         </div>
         <div class="flip-card">
            <div class="flip-card-inner"
            onclick="
               if(this.getAttribute('checked')==null){
                  this.style.transform='rotateY(180deg)';
                  this.setAttribute('checked','');
               }else{
                  this.style.transform='rotate(0deg)';
                  this.removeAttribute('checked');
               }
            ">
               <div class="flip-card-front d-flex flex-column align-items-center p-4">
                  <img class="border-pink mb-2" src="../img/persons/anderson.png" alt="">
                  <h4 class="dark-white mb-1">Anderson</h4>
                  <h4 class="dark-white mb-3">Baptista</h4>
                  <h5 class="color-pink mb-3">Desenvolvedor Chefe</h5>
                  <p class="d-flex align-items-center dark-white">
                     <i class="bi bi-arrow-clockwise me-2 fs-5 color-pink"></i> Clique para ver mais
                  </p>
               </div>
               <div class="flip-card-back d-flex align-items-center flex-column p-4">
                  <i class="bi bi-arrow-counterclockwise fs-5 color-pink"></i>
                  <h4 class="dark-white mb-1">Anderson</h4>
                  <h4 class="dark-white mb-3">Baptista</h4>
                  <p class="dark-white mb-4">Desenvolvedor de software com mais de 7 anos de experiência no mercado, tem habilidades com Java, Python, C#, PHP, Ruby e experiência de usuário</p>
                  <div class="d-flex align-items-center justify-content-between fs-2 w-100">
                     <a href="">
                        <i class="bi bi-facebook color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-instagram color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-twitter color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-linkedin color-pink"></i>
                     </a>
                  </div>
               </div>
            </div>
         </div>
         <div class="flip-card">
            <div class="flip-card-inner"
            onclick="
               if(this.getAttribute('checked')==null){
                  this.style.transform='rotateY(180deg)';
                  this.setAttribute('checked','');
               }else{
                  this.style.transform='rotate(0deg)';
                  this.removeAttribute('checked');
               }
            ">
               <div class="flip-card-front d-flex flex-column align-items-center p-4">
                  <img class="border-pink mb-2" src="../img/persons/herbert.png" alt="">
                  <h4 class="dark-white mb-1">Herbert</h4>
                  <h4 class="dark-white mb-3">Santos</h4>
                  <h5 class="color-pink mb-3">ADM de dados</h5>
                  <p class="d-flex align-items-center dark-white">
                     <i class="bi bi-arrow-clockwise me-2 fs-5 color-pink"></i> Clique para ver mais
                  </p>
               </div>
               <div class="flip-card-back d-flex align-items-center flex-column p-4">
                  <i class="bi bi-arrow-counterclockwise fs-5 color-pink"></i>
                  <h4 class="dark-white mb-1">Herbert</h4>
                  <h4 class="dark-white mb-3">Santos</h4>
                  <p class="dark-white mb-4">Profissional na área de dados, trás consigo 12 anos de experiência no tratamento e gerenciamento de dados empresariais</p>
                  <div class="d-flex align-items-center justify-content-between fs-2 w-100">
                     <a href="">
                        <i class="bi bi-facebook color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-instagram color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-twitter color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-linkedin color-pink"></i>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="second-line mb-3 d-flex align-items-center justify-content-center flex-wrap">
         <div class="flip-card">
            <div class="flip-card-inner"
            onclick="
               if(this.getAttribute('checked')==null){
                  this.style.transform='rotateY(180deg)';
                  this.setAttribute('checked','');
               }else{
                  this.style.transform='rotate(0deg)';
                  this.removeAttribute('checked');
               }
            ">
               <div class="flip-card-front d-flex flex-column align-items-center p-4">
                  <img class="border-pink mb-2" src="../img/persons/leandro.png" alt="">
                  <h4 class="dark-white mb-1">Leandro</h4>
                  <h4 class="dark-white mb-3">Nogueira</h4>
                  <h5 class="color-pink mb-3">Engenheiro de dados</h5>
                  <p class="d-flex align-items-center dark-white">
                     <i class="bi bi-arrow-clockwise me-2 fs-5 color-pink"></i> Clique para ver mais
                  </p>
               </div>
               <div class="flip-card-back d-flex align-items-center flex-column p-4">
                  <i class="bi bi-arrow-counterclockwise fs-5 color-pink"></i>
                  <h4 class="dark-white mb-1">Leandro</h4>
                  <h4 class="dark-white mb-3">Nogueira</h4>
                  <p class="dark-white mb-4">Competente em desenvolvimento de banco de dados em sua área de atuação, integra nossa equipe sendo um pilar estrutural dos projetos</p>
                  <div class="d-flex align-items-center justify-content-between fs-2 w-100">
                     <a href="">
                        <i class="bi bi-facebook color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-instagram color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-twitter color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-linkedin color-pink"></i>
                     </a>
                  </div>
               </div>
            </div>
         </div>
         <div class="flip-card">
            <div class="flip-card-inner"
            onclick="
               if(this.getAttribute('checked')==null){
                  this.style.transform='rotateY(180deg)';
                  this.setAttribute('checked','');
               }else{
                  this.style.transform='rotate(0deg)';
                  this.removeAttribute('checked');
               }
            ">
               <div class="flip-card-front d-flex flex-column align-items-center p-4">
                  <img class="border-pink mb-2" src="../img/persons/rodrigo.png" alt="">
                  <h4 class="dark-white mb-1">Rodrigo</h4>
                  <h4 class="dark-white mb-3">Barcelos</h4>
                  <h5 class="color-pink mb-3">Desenvolvedor web</h5>
                  <p class="d-flex align-items-center dark-white">
                     <i class="bi bi-arrow-clockwise me-2 fs-5 color-pink"></i> Clique para ver mais
                  </p>
               </div>
               <div class="flip-card-back d-flex align-items-center flex-column p-4">
                  <i class="bi bi-arrow-counterclockwise fs-5 color-pink"></i>
                  <h4 class="dark-white mb-1">Rodrigo</h4>
                  <h4 class="dark-white mb-3">Barcelos</h4>
                  <p class="dark-white mb-4">Desenvolvedor de software há mais de 6 anos, tem habilidades com HTML5, CSS3, JavaScript, experiência de usuário e atendimento ao público</p>
                  <div class="d-flex align-items-center justify-content-between fs-2 w-100">
                     <a href="">
                        <i class="bi bi-facebook color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-instagram color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-twitter color-pink"></i>
                     </a>
                     <a href="">
                        <i class="bi bi-linkedin color-pink"></i>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
<?php
    include("head-foot/footer.php");
?>
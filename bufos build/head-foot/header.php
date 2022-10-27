<?php
   if(!isset($session_start))
      session_start();

   if(file_exists("config.php"))
      require_once "config.php";
   else
      require_once "../config.php";

   $theme="";
   if (!empty($_COOKIE["theme"])&&$_COOKIE["theme"]=="light")
      $theme = "light-theme";
        
    if(!isset($not_destroy_last_product))
        unset($_SESSION["last_product"]);
        
    if(!isset($not_destroy_prices)){
        unset(
            $_SESSION["frete"],
            $_SESSION["total_produtos"],
            $_SESSION["subtotal"]
        );
    }
    
    if(!isset($not_destroy_payment_pedido))
        unset($_SESSION["payment_pedido"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Alberto Frigatto, Anderson Baptista, Herbert dos Santos, Leandro Nogueira, Rodrigo Barcelos">
   <meta name="description" content="Loja de hardware com sistema de montagem de pcs">
   <meta name="keywords" content="peça, pc, computador, bufos, sapo, bufos regulares, regulares, montar pc, loja">
   <meta http-equiv="cache-control" content="public">
   <title>Bufos <?php echo $titulo; ?></title>
   <!-- CSS only -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <!-- JavaScript Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <link rel="shortcut icon" href="<?php
                                       if(!file_exists("img/favicon.png"))
                                          echo "../";
                                    ?>img/favicon.png"
   type="image/x-icon">
   <link rel="stylesheet" href="<?php
                                 if(!file_exists("CSS/style.min.css"))
                                    echo "../";
                              ?>CSS/style.min.css?63">
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
   <script src="<?php
                  if(!file_exists("libs/jQuery-Mask-Plugin-master/src/jquery.mask.js"))
                     echo "../";
                  ?>libs/jQuery-Mask-Plugin-master/src/jquery.mask.js">
   </script>
</head>
<body class="<?php echo $theme; ?>">
   <!-- BOTÃO PARA MUDAR DE TEMA -->
   <button type="button" id="btn-toggle-theme">
      <i class="bi"></i>
   </button>

   <script>
      const btnTheme=document.getElementById("btn-toggle-theme");
      const iconTheme=document.querySelector("#btn-toggle-theme i.bi");

      function getCookie(cname) {
         let name = cname + "=";
         let decodedCookie = decodeURIComponent(document.cookie);
         let ca = decodedCookie.split(';');
         for(let i = 0; i <ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
               c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
               return c.substring(name.length, c.length);
            }
         }
         return "";
      }

      var statusTheme=getCookie("theme");

      if(statusTheme=="dark"||statusTheme==""){
         iconTheme.classList.add("bi-sun");
         iconTheme.classList.remove("bi-moon");
      }else{
         iconTheme.classList.add("bi-moon");
         iconTheme.classList.remove("bi-sun");
      }

      btnTheme.addEventListener("click", function() {

         document.body.classList.toggle("light-theme");
         
         let theme = "dark";
         
         if (document.body.classList.contains("light-theme"))
            theme = "light";
         
         document.cookie="theme="+theme+";expires=;path=/";

         var statusTheme=getCookie("theme");

         if(statusTheme=="dark"||statusTheme==""){
            iconTheme.classList.add("bi-sun");
            iconTheme.classList.remove("bi-moon");
         }else{
            iconTheme.classList.add("bi-moon");
            iconTheme.classList.remove("bi-sun");
         }
      });
   </script>

   <!-- PARTE EM CIMA DA BARRA DE NAVEGAÇÃO -->
   <div class="container-fluid site-top py-1">
      <div class="d-flex justify-content-between align-items-center container">
         <span class="tel dark-white">
            <i class="bi bi-telephone-fill color-pink"></i> (11)997430-4367
         </span>
         <div class="d-flex justify-content-between social">
            <a href="https://www.facebook.com/profile.php?id=100075004281849" target="_blank">
               <i class="bi bi-facebook"></i>
            </a>
            <a href="https://www.instagram.com/bufos_regulares/" target="_blank">
               <i class="bi bi-instagram"></i>
            </a>
            <a href="https://twitter.com/BufosRegulares2" target="_blank">
               <i class="bi bi-twitter"></i>
            </a>
            <a href="https://www.youtube.com/channel/UCJIJZ-ouLaacVihH3uMRCdw" target="_blank">
               <i class="bi bi-youtube"></i>
            </a>
         </div>
      </div>
   </div>

   <!-- BARRA DE NAVEGAÇÃO -->
   <nav class="navbar navbar-expand-lg mb-5">
      <div class="container">
         <a href="<?php
                     if(!file_exists("index.php"))
                        echo "../";
                     ?>index.php"
         class="navbar-brand">
            <img src="<?php
                        if(!file_exists("img/logo.png"))
                           echo "../";
                     ?>img/logo.png"
            alt="Bufos Regulares">
         </a>
         <form class="navbar-search" action="<?php if(!file_exists("search.php")) echo "../"; ?>search.php" method="get">
            <div class="input-group d-none d-md-flex">
               <input type="search" class="form-control" placeholder="O que você procura?" name="search" value="<?php if(isset($_GET["search"])) echo $_GET["search"]; ?>">
               <button type="submit" class="btn background-pink text-white">
                  <i class="bi bi-search"></i>
               </button>
            </div>
            <button type="button" onclick="toggleSearch(true)" class="d-inline d-md-none bg-transparent border-0 color-pink">
               <i class="bi bi-search fs-4"></i>
            </button>
         </form>
         <ul class="navbar-nav mx-2">
            <li class="nav-item">
               <a href="<?php
                           if(isset($_SESSION["loggedin"])){
                              if(file_exists("cliente/profile_cliente.php"))
                                 echo "cliente/profile_cliente.php";
                              else
                                 echo "../cliente/profile_cliente.php";
                           }else{
                              if(file_exists("login.php"))
                                 echo "login.php";
                              else
                                 echo "../login.php";
                           }
                        ?>" class="nav-link login">
                  <?php
                     if(isset($_SESSION["loggedin"])){
                        $sql="SELECT * FROM clientes WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";

                        $result=$mysqli->query($sql);
                        $cliente=$result->fetch_assoc();

                        $foto=$cliente["foto"];

                        if(!file_exists($foto))
                           $foto=str_replace("../","",$foto);

                        echo "
                           <img src='$foto' alt='".$cliente["nome"]."' class='thumb-perfil'>
                           <span>".$cliente["nome"]."</span>
                        ";
                     }else{
                        echo "
                           <i class='bi bi-person-fill'></i>
                           <span>Minha conta</span>
                        ";
                     }
                  ?>
               </a>
            </li>
            <li class="nav-item">
               <button type="button" class="nav-link border-0 bg-transparent" id="btn-open-cart-shop">
                   <i class="bi bi-cart-fill"></i>
                   <span id="qtde-cart">0</span>
               </button>
            </li>
         </ul>
      </div>
   </nav>

   <!-- TELA DE PESQUISA EM TELA PEQUENA -->
   <div class="search d-flex d-md-none justify-content-center align-items-center pb-5">
      <div class="d-flex flex-column align-items-center">
         <button type="button" onclick="toggleSearch(false)" class="bg-transparent border-0 align-self-end white-dark">
            <i class="bi bi-x-lg fs-1"></i>
         </button>
         <h1 class="mb-5 mt-4 white-dark">O que você procura?</h1>
         <form class="d-flex" action="<?php if(!file_exists("search.php")) echo "../"; ?>search.php" method="get">
            <input type="search" class="bg-transparent border-0 white-dark" placeholder="Buscar" name="search" value="<?php if(isset($_GET["search"])) echo $_GET["search"]; ?>">
            <button type="submit" class="bg-transparent border-0">
               <i class="bi bi-search color-pink fs-2"></i>
            </button>
         </form>
      </div>
   </div>
// TELA DE PESQUISA EM RESOLUÇÃO PEQUENA
const search=document.querySelector(".search");
function toggleSearch(state){
   if(state){
      search.style.transform="translateY(0)";
      document.body.style.overflow="hidden";
      document.querySelector(".search input").focus();
   }else{
      search.style.transform="translateY(-100%)";
      document.body.style.overflow="auto";
   }
}

// BOTÃO DE FAVORITO NOS CARDS
function addFavoriteCard(cliente,produto){
	const xhttp=new XMLHttpRequest();
	xhttp.open("GET","../ajax/add_favorite.php?cliente="+cliente+"&produto="+produto);
	xhttp.send();
}

function removeFavoriteCard(cliente,produto){
	const xhttp=new XMLHttpRequest();
	xhttp.open("GET","../ajax/remove_favorite.php?cliente="+cliente+"&produto="+produto);
	xhttp.send();
}

function favoriteOver(obj){
    if(!obj.hasAttribute("checked")){
        obj.classList.remove("bi-heart");
        obj.classList.add("bi-heart-fill");
    }
}

function favoriteOut(obj){
    if(!obj.hasAttribute("checked")){
        obj.classList.remove("bi-heart-fill");
        obj.classList.add("bi-heart");
    }
}

function favorite(obj,produto,cliente){
    if(cliente==-1){
        window.location.assign('https://bufosregulares.com/login.php');
    }else{
        if(!obj.hasAttribute("checked")){
            obj.setAttribute("checked","");
            obj.classList.remove("bi-heart");
            obj.classList.add("bi-heart-fill");
            addFavoriteCard(cliente,produto);
        }else{
            obj.removeAttribute("checked");
            obj.classList.remove("bi-heart-fill");
            obj.classList.add("bi-heart");
            removeFavoriteCard(cliente,produto);
        }
    }
}

var hearts=document.querySelectorAll(".heart");

function checkFavorite(){
    for(var i=0;i<hearts.length;i++){
        if(hearts[i].hasAttribute("checked")){
            hearts[i].classList.remove("bi-heart");
            hearts[i].classList.add("bi-heart-fill");
        }
    }
    checkFavorite();
}
checkFavorite();
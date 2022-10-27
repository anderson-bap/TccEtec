//MOSTRAR O RESULTADO DO CEP
const inputCep=document.getElementById("cep");
const inputEndereco=document.getElementById("endereco");

function sendCep(cep){
   const xhttp=new XMLHttpRequest();
   xhttp.onload=function(){
      inputEndereco.value=this.responseText;
   }
   xhttp.open("GET","../ajax/cep.php?cep="+cep);
   xhttp.send();
}

window.addEventListener("load",function(){
   sendCep(inputCep.value);
});
inputCep.addEventListener("keyup",function(){
   sendCep(this.value);
});
inputCep.addEventListener("paste",function(){
   sendCep(this.value);
});
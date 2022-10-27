// MODAL DE DELETE DA EMPRESA
const deleteErr=document.getElementById("delete-err");
const modalCargo=document.getElementById("delete");

if(deleteErr.innerHTML!=""){
   modalCargo.classList.add("show");
   modalCargo.style.display="block";
}
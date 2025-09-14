const element = document.querySelector(".toggle-btn");
const toggler = document.querySelector("#icon");
element.addEventListener("click", function(){
    document.querySelector("#sidebar").classList.toggle("expand");
    toggler.classList.toggle("bxs-chevrons-right");
    toggler.classList.toggle("bxs-chevrons-left");
});

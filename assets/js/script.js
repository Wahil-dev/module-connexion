const openMenu = document.querySelector("#openMenu");
const menu = document.querySelector("#menu");
var bol = true;
openMenu.addEventListener("click", ()=> {
    if(bol) {
        menu.style.height = 'auto';
        bol = false;
    } else {
        menu.style.height = '0';
        bol = true;
    }
})

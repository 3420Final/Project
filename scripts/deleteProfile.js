"use strict";
//confirmation dialog on delete
const deleteButton = document.querySelector("form div button");
deleteButton.addEventListener("click", (ev) =>{
    let confirmDelete = confirm ("Are you sure you want to delete this sheet?");
    if (confirmDelete == false){
        ev.preventDefault();
    }
});

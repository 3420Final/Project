"use strict";
//confirmation dialog on delete
const deleteButton = document.querySelector("#timeSlot div ul li");
deleteButton.addEventListener("click", (ev) =>{
    let confirmDelete = confirm ("Are you sure you want to delete this sheet?");
    if (confirmDelete == false){
        ev.preventDefault();
    }
});

deleteButton.addEventListener("keypress", (ev) =>{
    if (ev.keyCode === 13){
        let confirmDelete = confirm ("Are you sure you want to delete this sheet?");
        if (confirmDelete == false){
            ev.preventDefault();
        }
    }
});
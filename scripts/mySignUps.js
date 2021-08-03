"use strict";
//confirmation dialog on delete
const sheetListItems = document.querySelectorAll(".Sign-upSheets div ul li");
if (sheetListItems[3] != undefined){
    sheetListItems[3].addEventListener("click", (ev) =>{
        let confirmDelete = confirm ("Are you sure you want to delete this sheet?");
        if (confirmDelete == false){
            ev.preventDefault();
        }
    });
}

const slotListItems = document.querySelectorAll(".Slots div ul li");
if (slotListItems[1] != undefined){
    slotListItems[1].addEventListener("click", (ev) =>{
        let confirmDelete = confirm ("Are you sure you want to delete this time slot?");
        if (confirmDelete == false){
            ev.preventDefault();
        }
    });
}

if (sheetListItems[3] != undefined){
    sheetListItems[3].addEventListener("keypress", (ev) =>{
        if (ev.keyCode === 13){
            let confirmDelete = confirm ("Are you sure you want to delete this sheet?");
            if (confirmDelete == false){
                ev.preventDefault();
            }
        }
    });
}

if (slotListItems[1] != undefined){
    slotListItems[1].addEventListener("keypress", (ev) =>{
        if (ev.keyCode === 13){
            let confirmDelete = confirm ("Are you sure you want to delete this time slot?");
            if (confirmDelete == false){
                ev.preventDefault();
            }
        }
    });
}
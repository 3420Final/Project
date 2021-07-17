"use strict";
//confirmation dialog on delete
const sheetListItems = document.querySelectorAll(".Sign-upSheets div ul li");
sheetListItems[3].addEventListener("click", (ev) =>{
    let confirmDelete = confirm ("Are you sure you want to delete this sheet?");
    if (confirmDelete == false){
        ev.preventDefault();
    }
});
const slotListItems = document.querySelectorAll(".Slots div ul li");
slotListItems[1].addEventListener("click", (ev) =>{
    let confirmDelete = confirm ("Are you sure you want to delete this time slot?");
    if (confirmDelete == false){
        ev.preventDefault();
    }
});

//at least 2 plug-ins ( One could be password strength on create account)
//we could use flatpickr which is some kind of calender plug-in
//we can think about what plug-ins to use
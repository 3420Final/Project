"use strict";
//confirmation dialog on delete
const sheetListItems = document.querySelectorAll(".Sign-upSheets div ul li");
sheetListItems[3].addEventListener("click", (ev) =>{
    let confirmDelete = confirm ("There is not enough of that toy available. Would you like more to be made?");
    if (confirmDelete == false){
        ev.preventDefault();
    }
});
const slotListItems = document.querySelectorAll(".Slots div ul li");
sheetListItems[1].addEventListener("click", (ev) =>{
    let confirmDelete = confirm ("There is not enough of that toy available. Would you like more to be made?");
    if (confirmDelete == false){
        ev.preventDefault();
    }
});

//at least 2 plug-ins ( One could be password strength on create account)
//we could use flatpickr which is some kind of calender plug-in
//we can think about what plug-ins to use
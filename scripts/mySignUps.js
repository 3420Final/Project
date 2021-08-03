"use strict";
//confirmation dialog on delete
//Mouse delete sheet
const sheetListItems = document.querySelectorAll(".Sign-upSheets div ul");
for(let i = 0; i < sheetListItems.length; i ++){
    if (sheetListItems[i].lastElementChild != undefined){
        sheetListItems[i].lastElementChild.addEventListener("click", (ev) =>{
            let confirmSheetDelete = confirm ("Are you sure you want to delete this sheet?");
            if (confirmSheetDelete == false){
                ev.preventDefault();
            }
        });
    }
}

//Mouse delete time slot
const slotListItems = document.querySelectorAll(".Slots div ul");
for(let i = 0; i < slotListItems.length; i ++){
    if (slotListItems[i].lastElementChild != undefined){
        slotListItems[i].lastElementChild.addEventListener("click", (ev) =>{
            let confirmSheetDelete = confirm ("Are you sure you want to delete this sheet?");
            if (confirmSheetDelete == false){
                ev.preventDefault();
            }
        });
    }
}

//Keyboard delete sheet
for(let i = 0; i < sheetListItems.length; i ++){
    if (sheetListItems[i].lastElementChild != undefined){
        sheetListItems[i].lastElementChild.addEventListener("click", (ev) =>{
            if (ev.keyCode === 13){
                let confirmSheetDeleteKey = confirm ("Are you sure you want to delete this sheet?");
                if (confirmSheetDeleteKey == false){
                    ev.preventDefault();
                }
            }
        });
    }
}

//Keyboard delete time slot
for(let i = 0; i < slotListItems.length; i ++){
    if (slotListItems[i].lastElementChild != undefined){
        slotListItems[i].lastElementChild.addEventListener("click", (ev) =>{
            if (ev.keyCode === 13){
                let confirmSlotDeleteKey = confirm ("Are you sure you want to delete this time slot?");
                if (confirmSlotDeleteKey == false){
                    ev.preventDefault();
                }
            }
        });
    }

}
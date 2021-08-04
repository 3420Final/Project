"use strict";
//plug-in
const datePicker = document.querySelectorAll(".table");
for (let i = 0; i < 2; i ++){
    $(document).on('focus', '#basicDate',function(){
        $(this).flatpickr({
            appendTo: datePicker[i],
            enableTime: true,
            altInput: true,
            altFormat: "F, d Y H:i",
            dateFormat: "Y-m-d H:i"
        });
    });
}

//create sign-up add time slots
const button = document.getElementById("addSlot");
let numSlots = document.querySelector("#numSlots").value;
button.addEventListener("click", () => {
    console.log("addSlot");
    addRow("generateSlots");
    numSlots ++;
    document.querySelector("#numSlots").value = numSlots;
});

function addRow(id){ 
    console.log("addRow");
    let x=document.getElementById(id).tBodies[0];  //get the table
    let node=x.rows[0].cloneNode(true);    //clone the previous node or row
    console.log(node);
    x.appendChild(node);   //add the node or row to the table

    //select the new input that just got cloned and change its name for the POST to work proper
    let Inputs = document.getElementsByName('dateTime0');
    let newInput = Inputs[1];
    newInput.name = 'dateTime' + numSlots;
    newInput.disabled = false;

    //clear words from td, and add the delete checkbox
    let rows = document.getElementsByClassName('row');
    let newRow = rows[rows.length-1];
    let newRowEndItem = newRow.lastElementChild;
    newRowEndItem.innerHTML = "Delete Slot: <input type='checkbox' name='deleteNEW' value='Delete'>";
    let newDelete = document.getElementsByName('deleteNEW');
    newDelete = newDelete[0];
    newDelete.name = 'delete' + numSlots;
    
} 
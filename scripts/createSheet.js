"use strict";
const title = document.querySelector("#title");
title.addEventListener("change", () => {
    document.querySelector("table tbody tr > td").innerHTML = "" + title.value;
});
//create sign-up add time slots
const button = document.getElementById("addSlot");
var numSlots = 1;
button.addEventListener("click", () => {
    console.log("addSlot");
    addRow("generateSlots");
    numSlots ++;
    document.querySelector("#numSlots").value = numSlots;
    document.getElementById("chicken").setAttribute('name', 'date');
});

function addRow(id){ 
    console.log("addRow");
    var x=document.getElementById(id).tBodies[0];  //get the table
    var node=x.rows[0].cloneNode(true);    //clone the previous node or row
    x.appendChild(node);   //add the node or row to the table
} 

//Form validation
const requestForm = document.querySelector("#sheet");

//replace 'event name here' with the correct event
requestForm.addEventListener("submit", (ev) => {
    //declare a boolean flag valid set to false for determining if there were any errors found below
    let error = false;

    const titleInput = document.querySelector("#title");
    const titleError = titleInput.nextElementSibling;

    //validate user has entered a date
    titleError.classList.remove("hidden");
    if (titleInput.value != "") {
        titleError.classList.add("hidden");
    } else {
        console.log("title error");
        error = true;
    }

    const creatorInput = document.querySelector("#creator");
    const creatorError = creatorInput.nextElementSibling;

    //validate user has entered a date
    creatorError.classList.remove("hidden");
    if (creatorInput.value != "") {
        creatorError.classList.add("hidden");
    } else {
        error = true;
        console.log("creator error");
    }

    const descriptionInput = document.querySelector("#description");
    const descriptionError = descriptionInput.nextElementSibling;

    //validate user has entered a date
    descriptionError.classList.remove("hidden");
    if (descriptionInput.value != "") {
        descriptionError.classList.add("hidden");
    } else {
        error = true;
        console.log("description error");
    }

    const locationInput = document.querySelector("#location");
    const locationError = locationInput.nextElementSibling;

    //validate user has entered a date
    locationError.classList.remove("hidden");
    if (locationInput.value != "") {
        locationError.classList.add("hidden");
    } else {
        error = true;
        console.log("location error");
    }

    const privacy = document.querySelector("input[type='radio']:checked");
    const privacyError = document.querySelector("fieldset span");

    //validate that a radio button was selected. Remember that a radio button's checked attribute determines if it was selected
    privacyError.classList.remove("hidden");
    if (privacy) {
        privacyError.classList.add("hidden");
    } else {
        error = true;
        console.log("privacy error");
    }

    const numSlotsInput = document.querySelector("#numSlots");
    const numSlotsError = numSlotsInput.nextElementSibling;

    //validate user has entered a title
    numSlotsError.classList.remove("hidden");
    if (numSlotsInput.value >= 1) {
        numSlotsError.classList.add("hidden");
    } else {
        error = true;
        console.log("slot number error");
    }

    const dateInput = document.querySelector("#date");
    const dateError = dateInput.nextElementSibling;

    //validate user has entered a date
    dateError.classList.remove("hidden");
    if (dateInput.value != "") {
        dateError.classList.add("hidden");
    } else {
        error = true;
        console.log("date error");
    }

    const timeInput = document.querySelector("#time");
    const timeError = timeInput.nextElementSibling;

    //validate user has entered a time
    timeError.classList.remove("hidden");
    if (timeInput.value != "") {
        timeError.classList.add("hidden");
    } else {
        error = true;
        console.log("time error");
    }

    // Make this conditional on if there are errors.
    if (error) ev.preventDefault(); //STOP FORM SUBMISSION IF THERE ARE ERRORS
});


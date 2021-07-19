"use strict";
//Form validation
const requestForm = document.querySelector("#editForm");

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
    }

    const descriptionInput = document.querySelector("#description");
    const descriptionError = descriptionInput.nextElementSibling;

    //validate user has entered a date
    descriptionError.classList.remove("hidden");
    if (descriptionInput.value != "") {
        descriptionError.classList.add("hidden");
    } else {
        error = true;
    }

    const locationInput = document.querySelector("#location");
    const locationError = locationInput.nextElementSibling;

    //validate user has entered a date
    locationError.classList.remove("hidden");
    if (locationInput.value != "") {
        locationError.classList.add("hidden");
    } else {
        error = true;
    }

    const privacy = document.querySelector("input[type='radio']:checked");
    const privacyError = document.querySelector("fieldset span");

    //validate that a radio button was selected. Remember that a radio button's checked attribute determines if it was selected
    privacyError.classList.remove("hidden");
    if (privacy) {
        privacyError.classList.add("hidden");
    } else {
        error = true;
    }

    const numSlotsInput = document.querySelector("#numSlots");
    const numSlotsError = numSlotsInput.nextElementSibling;

    //validate user has entered a title
    numSlotsError.classList.remove("hidden");
    if (numSlotsInput.value == count) {
        numSlotsError.classList.add("hidden");
    } else {
        error = true;
    }

    const dateInput = document.querySelector("#date");
    const dateError = dateInput.nextElementSibling;

    //validate user has entered a date
    dateError.classList.remove("hidden");
    if (dateInput.value != "") {
        dateError.classList.add("hidden");
    } else {
        error = true;
    }

    const timeInput = document.querySelector("#time");
    const timeError = timeInput.nextElementSibling;

    //validate user has entered a time
    timeError.classList.remove("hidden");
    if (timeInput.value != "") {
        timeError.classList.add("hidden");
    } else {
        error = true;
    }

    // Make this conditional on if there are errors.
    if (error) ev.preventDefault(); //STOP FORM SUBMISSION IF THERE ARE ERRORS
});
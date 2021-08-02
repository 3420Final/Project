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
//Form validation

//confirmation dialog on delete
const operations = document.query
let makeMore = confirm ("There is not enough of that toy available. Would you like more to be made?");
if (makeMore == true){
    toyChoice.insertAdjacentHTML("afterend","<span id='qty'>More Requested</span>");
}
else{
    toyChoice.insertAdjacentHTML("afterend","<span id='qty'>Over Allocated</span>");
}
//unique username when text field looses focus

//create sign-up add time slots

//at least 2 plug-ins
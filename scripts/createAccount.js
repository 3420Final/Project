"use strict";
//Form validation
window.addEventListener("DOMContentLoaded", () => {

  //bool error flag
  let error = false;

  //select the form
  const uploadform = document.querySelector("#uploadform");

  //Password strength plug-in
  //code referenced from:
  //https://github.com/jaimeneeves/checkforce.js
  let render = document.querySelector('.strength');
  


  CheckForce('#password1').checkPassword(function(response){
    render.innerHTML = response.content;
  });

  
  //check that passwords match
  const password1 = document.querySelector("#password1");
  const password2 = document.querySelector("#password2");
  
  password2.addEventListener("change", (ev) => {
    
    //remove previous errors
    if(password2){
      password2.nextSibling.remove();
    }

    if(password2.value != password1.value){
      error = true;
      password2.insertAdjacentHTML("afterend", "<span class='error'>Passwords dont match</span>");
    }
    else{
      error = false;
    }
  });

  //This Section Below checks username uniqueness using AJAX
  const username = document.querySelector("#username");

  username.addEventListener("change", (ev) => {

    //remove previous errors
    if(username){
      username.nextSibling.remove();
    }
    
    //open request
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "checkUsername.php?username=" + username.value);

    //when xhr loads
    xhr.addEventListener("load", (ev) => {
      if(!status==200){ //if doesnt work
        console.log(xhr.response);
        console.log('something went wrong');
      }
      else{

        let response = xhr.responseText;

        if(response == 'true'){
          //username.insertAdjacentHTML("afterend", "<span>Username available</span>");
          error = false;
        }
        else if(response == 'false') {
          username.insertAdjacentHTML("afterend", "<span class='error'>Username already taken</span>");
          error = true;
        }
        else
        {
          username.insertAdjacentHTML("afterend", "<span class='error'>Unable to Check Username</span>");
          error = true;
        }
      }

    });
    xhr.send();

  });//end username uniqueness check


  //This Section Below checks email uniqueness using AJAX

  const email = document.querySelector("#email");

  email.addEventListener("change", (ev) => {

    //remove previous errors
    if(email){
      email.nextSibling.remove();
    }
    
    //open request
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "checkEmail.php?email=" + email.value);

    //when xhr loads
    xhr.addEventListener("load", (ev) => {
      if(!status==200){ //if doesnt work
        console.log(xhr.response);
        console.log('something went wrong');
      }
      else{

        let response = xhr.responseText;

        if(response == 'true'){
          //email.insertAdjacentHTML("afterend", "<span>email available</span>");
          error = false;
        }
        else if(response == 'false') {
          email.insertAdjacentHTML("afterend", "<span class='error'>email already taken</span>");
          error = true;
        }
        else
        {
          email.insertAdjacentHTML("afterend", "<span class='error'>Unable to Check email</span>");
          error = true;
        }
      }

    });
    xhr.send();

  });//end email uniqueness check



  uploadform.addEventListener("submit", (ev) => {
    if (error) ev.preventDefault(); //STOP FORM SUBMISSION IF THERE ARE ERRORS
  });

  
})
"use strict";

//preview image
//citation https://developer.mozilla.org/en-US/docs/Web/API/FileReader/readAsDataURL
function previewFile() {
    const preview = document.querySelector('#previewimage');
    const file = document.querySelector('input[type=file]').files[0];
    const reader = new FileReader();
    if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {
      reader.addEventListener("load", function () {
        // convert image file to base64 string
        preview.src = reader.result;
      }, false);
  
      if (file) {
        reader.readAsDataURL(file);
      }
    }
    else{
      preview.src =  "images/profileImage.png";
    }
  }

  //Form validation
window.addEventListener("DOMContentLoaded", () => {

    //bool error flag
    let error = false;
  
    //select the form
    const uploadform = document.querySelector("#uploadform");
  
  
    //chcck that image is valid
    let fileInput   = document.querySelector('input[type=file]');
    fileInput.addEventListener("change", (ev) => {
      
        let file1 = document.querySelector('input[type=file]').files[0];

      //remove previous errors
      if(fileInput.nextSibling){
        fileInput.nextSibling.remove();
      }

      if (/\.(jpe?g|png|gif)$/i.test(file1.name) ) {
        error=false;
      }
      else{
        error=true;
        fileInput.insertAdjacentHTML("afterend", "<span class='error'>Incorrect File Type</span>");
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
            //make sure error isnt set by something else
          if(!error){
            error = false;
          }
  
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
      if(email.nextSibling){
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
            //make sure error isnt set by something else
          if(!error){
            error = false;
          }
          }
          else if(response == 'false') {
            email.insertAdjacentHTML("afterend", "<span class='error'>email already taken</span>");
            error = true;
            console.log(4);
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
        console.log(error)
      if (error) ev.preventDefault(); //STOP FORM SUBMISSION IF THERE ARE ERRORS
    });
  
    
  });
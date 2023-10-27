



function getIPAddress(){

    let publicIP = '';

    fetch('https://api64.ipify.org?format=json')
        .then(response => response.json())
        .then(data => {
            publicIP = data.ip;
            document.getElementById("ip_address").value = publicIP;
        })
        .catch(error => {
            console.error('Error:', error);
        });
       
}

function createCredentialsObject(){ 
  let ip = document.getElementById("ip_address").value;
  let username = document.getElementById("username").value;
  let password = document.getElementById("password").value;

  const obj1 = {
    ip : ip,
    username : username,
    password : password
  };
  return obj1;
}



function showDashboard(){
  const xhr = new XMLHttpRequest();
        xhr.open("POST", "php/show_dashboard.php", true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                //alert(xhr.responseText);
                document.getElementsByTagName("body")[0].innerHTML = xhr.responseText;
                
            } else {
                alert("Error fetching data.");
            }
        };  
        xhr.send();
}


function sendCredentials(){
  // clear the existing cookie
  document.cookie = `user_id=`;
  document.cookie = `sessionStringHash=`;
  document.cookie = `ip=`;

  // send values to db and generate session key on server
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "php/submit_login_data.php", true);
  xhr.onload = function() {
      if (xhr.status === 200) {
        if(xhr.responseText == "wrong password" || xhr.responseText == "user does not exist"){
          alert("log in failed");
        }
        else{
          // show dashboard; this can be echoed too!
          

          const obj = JSON.parse(xhr.responseText);
          // Create a cookie string for the new session 
          // we can add values like device, ip address, screen dimension, color depth to heighten security
          const cookieValue = `user_id=${obj.user_id}`;
          const cookieValue2 = `sessionStringHash=${obj.sessionStringHash}`;
          const cookieValue3 = `ip=${obj.ip}`;
          // Set the new cookie value
          document.cookie = cookieValue;
          document.cookie = cookieValue2;
          document.cookie = cookieValue3;

          alert(document.cookie);
          //clear password
          showDashboard();
        }
      } else {
          alert("Server side error");
      }
  };  
  
  xhr.send(JSON.stringify(createCredentialsObject()));
}

window.addEventListener("load", getIPAddress);






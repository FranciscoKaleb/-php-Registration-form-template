



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



function sendCredentials(){
  // clear the existing cookie
  document.cookie = `user_id=`;
  document.cookie = `sessionStringHash=`;

  // send values to db and generate session key on server
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "php/submit_login_data.php", true);
  xhr.onload = function() {
      if (xhr.status === 200) {
        if(xhr.responseText == "wrong password" || xhr.responseText == "user does not exist"){
          alert("log in failed");
        }
        else{
          // show dashboard
          document.getElementById("log_in_signup_page_background").style.display = "none";
          document.getElementById("flex-dashboard").style.display = "flex";

          const obj = JSON.parse(xhr.responseText);
          // Create a cookie string for the new session
          const cookieValue = `user_id=${obj.user_id}`;
          const cookieValue2 = `sessionStringHash=${obj.sessionStringHash}`;
          // Set the new cookie value
          document.cookie = cookieValue;
          document.cookie = cookieValue2;
          alert(document.cookie);
          //clear password
          document.getElementById("password").value = "";
        }
      } else {
          alert("Server side error");
      }
  };  
  
  xhr.send(JSON.stringify(createCredentialsObject()));
}

window.addEventListener("load", getIPAddress);

function ssendCredentials(){
    document.getElementById('loginButton').addEventListener('click', function () {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
      
        // Perform input validation and send a request to the server
        // You should also handle errors and security measures here
      
        fetch('login.php', {
          method: 'POST',
          body: JSON.stringify({ username, password }),
          headers: {
            'Content-Type': 'application/json',
          },
        })
          .then(response => response.text())
          .then(data => {
            // Assuming the PHP script responds with JSON data
            const response = JSON.parse(data);
      
            if (response.success) {
              // Hide the login form and display the dashboard content
              document.getElementById('loginForm').style.display = 'none';
              document.getElementById('dashboardContainer').innerHTML = response.dashboardContent;
            } else {
              alert('Login failed. Please try again.');
            }
          })
          .catch(error => console.error('Error:', error));
      });
      
}




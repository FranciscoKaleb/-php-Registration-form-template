



function showLogIn(){
    const xhr = new XMLHttpRequest();
          xhr.open("POST", "php/show_login.php", true);
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

function logout(){
    // [1] set session as expired in db
    // [2] clear the cookies
    // [3] hide dashboard and show login


    // [1]
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/logout.php",true);
    xhr.onload = function() {
        if(xhr.status === 200){
            alert(xhr.responseText);
        }
        else{
            alert("server side error");
        }
    }
    const cookieString = document.cookie;
    const cookieArray = cookieString.split(';');
    const cookieObject = {};
    cookieArray.forEach(cookie => { // add ip address later
    const [name, value] = cookie.trim().split('=');
    cookieObject[name] = value;
    });
    xhr.send(JSON.stringify(cookieObject));

    // [2]
    document.cookie = `user_id=`;
    document.cookie = `sessionStringHash=`;
    document.cookie = `ip=`;
    // [3]
    showLogIn();

}










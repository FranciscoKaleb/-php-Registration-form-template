


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

    // [3]
    document.getElementById("log_in_signup_page_background").style.display = "flex";
    document.getElementById("flex-dashboard").style.display = "none";

}










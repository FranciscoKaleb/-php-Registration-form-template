

// this function is trigger on load to:
// [1] check users session validity on load (log in, reopen tab)
// [2] log out user who is inactive (set session expired for users without action for n span of time)
function readCookies(){ 
    setTimeout(function() {
        const cookieObject = createCookieObject();

        // [4] send the object to the server to check if user session is expired
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "php/validate_session.php", true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                if(xhr.responseText == "0"){
                    alert("session expired");
                    showLogIn();
                    document.cookie = `user_id=`;
                    document.cookie = `sessionStringHash=`;
                    document.cookie = `ip=`;
                }
                else{
                    document.getElementById("body").innerHTML = xhr.responseText;
                }
            } else {
                alert("Error fetching data.");
            }
        };  
        xhr.send(JSON.stringify(cookieObject));
    }, 0);
}




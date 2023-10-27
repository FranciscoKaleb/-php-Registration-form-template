




function readCookies(){
    setTimeout(function() {
        // [1] read cookie
        const cookieString = document.cookie;
        // [2] split cookie
        const cookieArray = cookieString.split(';');
        // [3] create an object
        const cookieObject = {};

        cookieArray.forEach(cookie => {// add ip address later
        const [name, value] = cookie.trim().split('=');
        cookieObject[name] = value;
        });
        //alert(cookieObject["user_id"]);
        //alert(cookieObject["sessionStringHash"]);

        // [4] send the object to the server to check if user session is expired
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "php/validate_session.php", true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                //alert(xhr.responseText);
                document.getElementById("body").innerHTML = xhr.responseText;
                //alert(xhr.responseText);
                
            } else {
                alert("Error fetching data.");
            }
        };  
        xhr.send(JSON.stringify(cookieObject));
    }, 0);
    

}
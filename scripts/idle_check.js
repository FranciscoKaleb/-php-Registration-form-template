
// look for cookies
//if cookie value is empty, no exec
//question is should it work with validate?

// run the timer when user clicks login
// if tab is not closed within the timers limit
    // send the cookies in the server to check if there is activity
    // if there is activity, extend the expiry
    // else(no activity) - end the session
// if tab is closed, run the check on tab reopen by putting the code onload()
    // send the cookies in the server to check if there is activity
    // if there is activity, extend the expiry
    // else(no activity) - end the session

// this can be done by looking the last action of the user in the logs
// and comparing the timestamp in the action to NOW() in mysql
// if greater than for example 6 hours then update session to expire

function createCookieObject(){
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
    return cookieObject;
}

let idleTimer;

window.addEventListener('mousemove', resetIdleTimer);
window.addEventListener('keydown', resetIdleTimer);

function resetIdleTimer() {
    clearTimeout(idleTimer);
    idleTimer = setTimeout(function () {
        readCookies();
    }, 600000); // 10 minutes of inactivity
}





// just a few notes on security:

// in real application, its good to put dummy names on  cookies/functions/variables
// its also good to divide the hashed session string in cookies
// its also good to hash the cookies like ip or user id

// its also good to log every page visit/reload(non-security)
// log every log in attempt

// its good to put loading screen after successful login

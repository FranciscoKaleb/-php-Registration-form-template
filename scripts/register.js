

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



function getRegion() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "php/get_region.php", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            
            const data = JSON.parse(xhr.responseText);
            populateRegion(data);
            
        } 
        else {
            alert("Error fetching data.");
        }
    };
    xhr.send();
}


function populateRegion(data){

    let element = document.getElementById("region");

    let dummy = document.createElement("option");
    dummy.innerHTML = "CHOOSE REGION";
    element.appendChild(dummy);

    for (let x in data) {
        let temp_element = document.createElement("option");
        temp_element.innerHTML = data[x];
        element.appendChild(temp_element);
    }
}

window.addEventListener("load",getIPAddress);
window.addEventListener("load",getRegion);





let prov_Code = '';
let obj1 = {};

function updateProvCode(){
    let selected = document.getElementById("province").value;
    
    for(let i = 0; i < obj1.length;i++){
        if(obj1[i]["provDesc"] == selected){
            prov_Code = obj1[i]["provCode"];
        }  
    }
    document.getElementById("test3").innerHTML = prov_Code;
}

function getProvince(){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/get_province.php", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            
            const data = JSON.parse(xhr.responseText);
            obj1 = data;

            populateProvince(obj1);
            updateProvCode();

            
        } else {
            alert("Error fetching data.");
        }
    };

    const val = document.getElementById("region").value;
    const obj = {regDesc : String(val)};
    xhr.send(JSON.stringify(obj));

    

}

function populateProvince(data){
    let element = document.getElementById("province");
    element.innerHTML = '';
    document.getElementById("municipality").innerHTML = "";
    document.getElementById("brgy").innerHTML = "";

    let dummy = document.createElement("option");
    dummy.innerHTML = "CHOOSE PROVINCE";
    element.appendChild(dummy);

    for (let x in data) {
        let temp_element = document.createElement("option");
        temp_element.innerHTML = data[x]["provDesc"];
        element.appendChild(temp_element);
    }

    prov_Code = '';
    Mun_Code = '';
    Brgy_Code = '';
    document.getElementById("test3").innerHTML = prov_Code;
    document.getElementById("test4").innerHTML = Mun_Code;
    document.getElementById("test5").innerHTML = Brgy_Code;
}

function updateProvCodeAndgetMunicipalities(){
    updateProvCode();
    getMunicipalities();
}





let Mun_Code = '';
let obj2 = {};

function updateMunCode(){
    let selected = document.getElementById("municipality").value;
    
    for(let i = 0; i < obj2.length;i++){
        if(obj2[i]["citymunDesc"] == selected){
            Mun_Code = obj2[i]["citymunCode"];
        }  
    }
    document.getElementById("test4").innerHTML = Mun_Code;
}

function getMunicipalities(){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/get_municipalities.php", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            
            const data = JSON.parse(xhr.responseText);
            obj2 = data;
            
            populateMunicipalities(obj2);
            updateMunCode();

            
        } else {
            alert("Error fetching data.");
        }
    };  
    const obj = {provCode : String(prov_Code)};
    xhr.send(JSON.stringify(obj));
}

function populateMunicipalities(data){
    let element = document.getElementById("municipality");
    element.innerHTML = '';
    document.getElementById("brgy").innerHTML = "";

    let dummy = document.createElement("option");
    dummy.innerHTML = "CHOOSE CITY/MUNICIPALITY";
    element.appendChild(dummy);
   

    for (let x in data) {
        let temp_element = document.createElement("option");
        temp_element.innerHTML = data[x]["citymunDesc"];
        element.appendChild(temp_element);
    }

    Mun_Code = '';
    document.getElementById("test4").innerHTML = Mun_Code;
}


function updateMunCodeAndGetBrgy(){
    updateMunCode();
    getBrgy();
}




let Brgy_Code = '';
let obj3 = {};

function updateBrgyCode(){
    let selected = document.getElementById("brgy").value;
            
    
    for(let i = 0; i < obj3.length;i++){
        if(obj3[i]["brgyDesc"] == selected){
            Brgy_Code = obj3[i]["brgyCode"];
        }  
    }
    document.getElementById("test5").innerHTML = Brgy_Code;
}

function getBrgy(){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/get_brgy.php", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            
            const data = JSON.parse(xhr.responseText);
            obj3 = data;
 
            
            populateBrgy(obj3);
            updateBrgyCode();
 
        } else {
            alert("Error fetching data.");
        }
    };  

    const obj = {citymunCode : String(Mun_Code)};
    xhr.send(JSON.stringify(obj));
}

function populateBrgy(data){
    let element = document.getElementById("brgy");
    element.innerHTML = '';

    let dummy = document.createElement("option");
    dummy.innerHTML = "CHOOSE BRGY";
    element.appendChild(dummy);

    for (let x in data) {
        let temp_element = document.createElement("option");
        temp_element.innerHTML = data[x]["brgyDesc"];
        element.appendChild(temp_element);
    }

    Brgy_Code = '';
    document.getElementById("test5").innerHTML = Brgy_Code;
}



function createObjectFromInputs(){

    let ip = document.getElementById("ip_address").value;
    let first_name = document.getElementById("first_name").value;
    let last_name = document.getElementById("last_name").value;
    let email = document.getElementById("email").value;
    let phone = document.getElementById("phone_number").value;
    let birth = document.getElementById("birthday").value;
    let gender = document.getElementById("gender").value;
    let address_code = Brgy_Code;
    let user_name = document.getElementById("user_name").value;
    let password = document.getElementById("password").value;

    const obj = {
        ip : ip,
        first_name : first_name,
        last_name : last_name,
        email : email,
        phone : phone,
        birth : birth,
        gender: gender,
        address_code : address_code,
        user_name : user_name,
        password : password
    };
    return obj;
}

function submit(){

    
}

function client_side_validation(){
    // check the empty inputs
    // check the minimum length
}

function submitData(){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/submit_data.php", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText == "111"){
                setTimeout(function () {
                    alert("You have successfully registered!");
                    window.location.href = "../index.html";
                    
                }, 500);
            }
            else{
                alert(xhr.responseText);
            }
        } else {
            alert("Server side error");
        }
    };  
    
    xhr.send(JSON.stringify(createObjectFromInputs()));
}


function sayHi(){
    alert("hi");
}

function showValues(data, data){
    alert(data + " " + data);
}
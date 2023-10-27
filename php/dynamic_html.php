<?php

$loginhtml = "<div id = 'log_in_signup_page_background'>
                        <div id = 'log_in_signup'>
                            <div  id = 'form' style = 'padding-top: 750;'>
                                <div class='form-group' style='margin-bottom:20px'>
                                    <h3>Sign in</h3>
                                </div>
                                <div class='form-group'>
                                    <label for='ip_address' style = 'display:none;'>ip_address</label >
                                    <input type='text' id='ip_address' name='ip_address' style = 'display:none;' required>
                                </div>
                                <div class='form-group'>
                                    <label for='first_name'>username</label>
                                    <input type='text' id='username' name='username' required>
                                </div>
                                <div class='form-group'>
                                    <label for='last_name'>password</label>
                                    <input type='password' id='password' name='password' required>
                                </div>
                                <div class = 'form-group'>
                                    <button onclick = 'sendCredentials()'>
                                        Log in
                                    </button>
                                </div> 
                                <div class = 'form-group'>
                                    <a href = 'register.html'>Register</a>
                                </div>    
                            </div>
                        </div>   
                    </div>";

$dashboardhtml = "<div id = 'flex-dashboard'>
                    <button style = 'width:150px; height:50px' onclick = 'logout()'>
                        logout
                    </button>
                  </div>";

?>
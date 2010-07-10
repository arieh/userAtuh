/*
    Permission is hereby granted, free of charge, to any person obtaining
    a copy of this software and associated documentation files (the
    "Software"), to deal in the Software without restriction, including
    without limitation the rights to use, copy, modify, merge, publish,
    distribute, sublicense, and/or sell copies of the Software, and to
    permit persons to whom the Software is furnished to do so, subject to
    the following conditions:
    
    The above copyright notice and this permission notice shall be included
    in all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
    MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
    IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
    CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
    TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
    SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

var UserAtuh = {
	'form_id' : 'login-form' //login form id
    , 'pass_id' : 'pass' //password field id
	, 'name_id' : 'user-name' // user name field id
	, 'key_id'  : 'temp-key' // temp-key field id
	, 'verify_name' : 'verify' //verification field name
	, 'enc_name' : 'enc' // encrypted hash field name
    , encrypt : function(str){return hex_sha1(str);} // a function to use for hashing the password - should be the same one used to store the passwords on the database. 
};

function setEncryption(){
	
	var   form = document.getElementById(UserAtuh.form_id)
		, pass_f = document.getElementById(UserAtuh.pass_id)
		, user_f = document.getElementById(UserAtuh.name_id)
		, key_f  = document.getElementById(UserAtuh.key_id)
		, enc_f  = document.createElement('input')
		, ver_f  = document.createElement('input')
		, name   = user_f.value //getting the user name
		, pass   = pass_f.value //getting the original password
		, key    = key_f.value //geting the temporary key		
		, ename  = encodeURI(name) 
		, encPass = UserAtuh.encrypt(pass)   // encrypting the password
		, string  = encPass+ename+key // joining the key, the password and the user name
		, sha     = hex_sha1(string)  // hashing them
		, enc     = hex_sha1(sha);     // re-hashing the password
	
	enc_f.type='hidden';
	enc_f.name = UserAtuh.enc_name;
	
	ver_f.type='hidden';
	ver_f.name = UserAtuh.verify_name;
	
	pass_f.value=encPass.substr(0,pass.length); // so the password and the temp key won't be sent with the page
	
	enc_f.value=enc;
	ver_f.value='true';
	
	form.appendChild(enc_f);
	form.appendChild(ver_f);
	
	key_f.value='1234';
}



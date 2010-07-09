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

/*
 * This file handles the hashing of the login form
 * 
 * this file does a 3 step encryption to the user-password to prevent data-fishing:
 * 	1. encrypting the password using the sha1 algorithm
 *  2. hashing the password with a temporary key suplied by the server and the user name
 *  3. re hashing the result
 *  
 * @require sha1.js
 */


/* 
 * encrypts the login form before it is sent over the internet
 * ------
 * flow:
 * ------
 * - getting form information
 * - encryption of the password
 * ------
 * 
 * the script takes the folowing paramaters from the login form:
 *     1. pass    : a password for the user
 *     2. tempKey : a temporary key sent by the server
 *     3. encrypt : this field will hold the hashed password 	
 */

function setEncryption(){
/* =============
 * INTIALIZATION
 * ============= */
	var name = document.getElementById('userName').value;//getting the user name
	var pass = document.getElementById('pass').value;//getting the original password
	var key  = document.getElementById('tempKey').value;//geting the temporary key
	
	
/* =============
 * ENCRYPTION 
 * ============= */
	
	ename = encodeURI(name);
	//4 step encryption
	/*1*/ var encPass = hex_sha1(pass);    // encrypting the password
	/*2*/ var string  = encPass+ename+key; // joining the key, the password and the user name
	/*3*/ var sha     = hex_sha1(string);  // hashing them
	/*4*/ var enc     = hex_sha1(sha);     // re-hashing the password
	

    //this part is so the password and the temp key won't be sent with the page. the
    //substr is so the text field won`t apear to be change (not to confuse the end user)
	var length  = pass.length;
	document.getElementById('pass').value=encPass.substr(0,length);
	document.getElementById('encrypt').value=enc;
	document.getElementById('tempKey').value='1234'
}



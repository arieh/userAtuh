<?php
require_once("classes/keyHandler.class.php");
require_once("dbconncet.php");

$link = connect();
$gen = new keyHandler($link,'/configs/userAtuh.ini');
$gen->generateKey();

?>

<script type='text/javascript' src='js/sha1.js'></script>
<script type='text/javascript' src='js/loginForm.js'></script>

<form id='loginForm' 
		action='login.php' 
		method="post" 
		onsubmit="javascript:setEncryption();" >
<fieldset>
<ul id='menu'>
    <li>
    	<label for="userName">user name
            <input type='text' id="userName" name="userName" />
        </label>
    </li>
    <li>
    	<label for="pass">password
            <input type="password" id="pass" name="pass" />
        </label>
    </li>
    <li>
        <input type='submit' name="submit" id="submit" value="enter" />
        <input type="hidden" 
        		value="<?php echo $gen->getKey(); ?>" 
        		id='tempKey' name='tempKey' />
        <input type="hidden" value="" id='encrypt' name='encrypt' />
    </li>
</ul>
</fieldset>
</form>
<?php
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
require_once('dbaInterface.class.php');
require_once('config.php');

class userAuthDba implements dbaInterface {
	
	/**
	 * @var mysql_link a link to the database
	 * @access private
	 */
	private $_link = null;
	
	/**
	 * a constructor for the object
	 * 
	 * @param mysql_link $link a link to the database
	 */
    public function __construct(&$link){
    	$this->_link = $link;
    }
    
    /**
     * insertes the key that was generated for this session into the database
     * 
     * @param string $key a random key
     * 
     * @access public
     */
    public function insertKey($key){
    	$ip = getenv('REMOTE_ADDR');
    	
    	$sql = "SELECT COUNT(*) as `c` FROM `tempKeys` WHERE `ip`='$ip'";
    	
    	$query = mysql_query($sql,$this->_link) or die(mysql_error());
    	$result = mysql_fetch_assoc($query);
    	
    	if ((int)$result['c']==0){
    		$sql = "INSERT INTO `tempKeys`(`ip`,`key`) VALUES ('$ip','$key')";
    	}else{
    		$sql = "UPDATE `tempKeys` SET `key`='$key' WHERE `ip`='$ip'";
    	}
    	
    	mysql_query($sql) or die(mysql_error());
    }    
    /**
     * retrives the last key that was stored for a specific ip
     * 
     * @param string $ip an ip address
     * @access public
     * @return string the key that was generated for this ip 
     */
    public function getKeyFromDB($ip){
    	$sql    = "SELECT `key` FROM `tempKeys` WHERE `ip`='$ip'";
    	
    	$query  = mysql_query($sql,$this->_link) or die(mysql_error());
    	$result = mysql_fetch_assoc($query);
    	return $result['key'];
    }
    
    /**
     * checks if a specific user exists
     * 
     * @param string $name a user name
     * 
     * @uses $GLOBALS['_suCnfgs_'] a global variable that holds database structure information
     * @access public
     * @return bool whether a user with this name exists
     */
    public function userExists($name){
    	global $_auCnfgs_;
    	global $_escapeInput_;
    	global $_escapeFunction_;
    	
    	if ($_escapeInput_) $name = $_escapeFunction_($name);
    	
    	$sql = "SELECT COUNT(*) as `c`
					FROM `".$_auCnfgs_['usersTable']."`
					WHERE `".$_auCnfgs_['uName']."` = '$name'";
    	
    	$query = mysql_query($sql) or die(mysql_error());
    	$result = mysql_fetch_assoc($query);
    	return ((int)$result['c']>0);
    }
    
    /**
     * retrives a password from the database for a specific user
     *
     * @param string $name a user name
     * 
     * @uses $GLOBALS['_suCnfgs_'] a global variable that holds database structure information
     * @access public
     * @return string the password for that user
     */
    public function getPass($name){
    	global $_auCnfgs_;
    	global $_escapeInput_;
    	global $_escapeFunction_;
    	
    	if ($_escapeInput_) $name = $_escapeFunction_($name);
    	
    	$sql = "SELECT `".$_auCnfgs_['pass']."` as `pass`
					FROM `".$_auCnfgs_['usersTable']."`
					WHERE `".$_auCnfgs_['uName']."` = '$name'";
    	
    	$query = mysql_query($sql,$this->_link) or die(mysql_error());
    	$result = mysql_fetch_assoc($query);
    	return $result['pass'];
    }
}
?>
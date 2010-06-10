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
require_once('DbaInterface.class.php');

class UserAtuhDba implements DbaInterface {
	/**
	 * @var mysql_link a link to the database
	 * @access private
	 */
	private $_link = null;
	
	/**
	 * @var array configurations for the database
	 * @access private
	 */
	 
	private $_configs = array();
	
	
	/**
	 * a constructor for the object
	 *
	 * @param mysql_link $link a link to the database
	 * @param mixed $conf configuration data. can be a string - a path to a vaid ini file, or an array with the requierd associative data
	 */
    public function __construct(&$link,$conf){
    	$this->_link = $link;
    	if (is_string($conf) && @file_exists($cong)) $this->_configs = parse_ini_file($conf);
    	elseif (is_array($conf)){
    	    $wanted_keys = array('tableName','nameField','passField');
    	    $not_available = array();
    	    foreach($wanted_keys as $key){
    	        if (!array_key_exists($key,$conf)) $not_available[]=$key;
    	    }
    	    
    	    if ($not_available){
    	        throw new InvalidArgumentException("Config array missing the following settings:".implode(',',$not_available));
    	    }
    	    
    	    if (array_key_exists('escapeInput',$conf)){
    	        if (!array_key_exists('escapeFunction',$conf)){
    	            throw new InvalidArgumentException("Escaping requested without suppllying and excaping method");
    	        }
    	    }else{
    	        $conf['escapeInput'] = false;
    	    }
    	    
    	    $this->_configs = $conf;
    	}else{
    	    throw new InvalidArgumentException("No Configuratsion was set");
    	}
    }
    
    /**
     * insertes the key that was generated for this session into the database
     *
     * @param string $key a random key
     *
     * @access public
     */
    public function insertKey($key){
    	$ip = $_SERVER['REMOTE_ADDR'];
    	
    	$sql = "SELECT COUNT(*) as `c` FROM `temp-keys` WHERE `ip`='$ip'";
    	
    	$query = mysql_query($sql,$this->_link) or die(mysql_error());
    	$result = mysql_fetch_assoc($query);
    	
    	if ((int)$result['c']==0){
    		$sql = "INSERT INTO `temp-keys`(`ip`,`key`) VALUES ('$ip','$key')";
    	}else{
    		$sql = "UPDATE `temp-keys` SET `key`='$key' WHERE `ip`='$ip'";
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
    public function getKey(){
    	$ip =  $_SERVER['REMOTE_ADDR'];
    	
    	if ($this->keyExists($ip)==false) return false;
    	
    	$sql    = "SELECT `key` FROM `temp-keys` WHERE `ip`='$ip'";
    	
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
    	if ($this->_configs['escapeInput']>0) $name = call_user_func_array($this->_configs['escapeFunction'],array($name));
    	$sql = "SELECT COUNT(*) as `c`
					FROM `".$this->_configs['tableName']."`
					WHERE `".$this->_configs['nameField']."` = '$name'";
    	
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
    	if ($this->_configs['escapeInput']) $name = call_user_func_array($this->_configs['escapeFunction'],array($name));
    	
    	$sql = "SELECT `".$this->_configs['passField']."` as `pass`
					FROM `".$this->_configs['tableName']."`
					WHERE `".$this->_configs['nameField']."` = '$name'";
    	$query = mysql_query($sql,$this->_link) or die(mysql_error());
    	$result = mysql_fetch_assoc($query);
    	return $result['pass'];
    }
    
    public function keyExists($ip){
    	$sql = "SELECT COUNT(*) as `c` FROM `temp-keys` WHERE `ip`='$ip'";
    	$query = mysql_query($sql,$this->_link) or die(mysql_error());
    	$result = mysql_fetch_assoc($query);
    	return ((int)$result['c']>0);
    }
}
?>
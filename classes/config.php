<?php
/**
 * @global array $GLOBALS['_auCnfgs_']
 */
$GLOBALS['_auCnfgs_'] = array();

global $_auCnfgs_;

/**
 * @global string the user's table name
 */
$_auCnfgs_['usersTable'] = 'users';

/**
 * @global string the user`s name field in the db
 */
$_auCnfgs_['uName'] = 'name';


/**
 * @global string the user`s password field in the db
 */
$_auCnfgs_['pass']  = 'password';

/**
 * @global bool whether to use an escaping function on input sent to the DB
 */
$GLOBALS['_escapeInput_'] =true;  
$GLOBALS['_escapeFunction_'] = 'mysql_real_escape_string';
?>

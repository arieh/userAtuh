<?php

$_SESSION = array();

abstract class UserAtuhTestCase extends PHPUnit_Framework_Testcase{
    
    protected function getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = false, $callOriginalClone = TRUE, $callAutoload = TRUE){
        return parent::getMock($originalClassName,$methods,$arguments,$mockClassName,$callOriginalConstructor,$callOriginalClone,$callAutoload);
    }
    
    protected $db = null;
    
    public function setUpDB(){
        $this->db = mysql_connect('localhost','root','1234');
        mysql_select_db('user_atuh',$this->db);
        $sql = file_get_contents(dirname(__FILE__).'/../sql/user_atuh.sql');
        $sql = explode (';',$sql);
        foreach ($sql as $stmt){
            try{
                mysql_query($stmt);
            }catch (Exception $e){}
        }
    }
}
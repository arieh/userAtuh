<?php
require_once dirname(__FILE__) . '/classes/UserAtuhTestCase.class.php';
require_once dirname(__FILE__) . '/../classes/UserAtuhDba.class.php';

class UserAtuhDbaTest extends UserAtuhTestCase{
    public function setUp(){
        $_SESSION['user_atuh'] = '';
        $_SERVER['REMOTE_ADDR'] = '';
    }
    
    public function getHandler($use_ini = false){
        if ($use_ini){
            return new UserAtuhDba($this->db,'configs/userAtuh.ini');
        }
        
        return new UserAtuhDba($this->db,array(
            'tableName' => 'users'
            , 'nameField' => 'name'
            , 'passField' => 'pass'
        ));
    }
    
    /**
     * @dataProvider provideIPs
     */
    public function testKeyExists($ip){
        $this->setUpDB();
        $target =$this->getHandler();
        $this->assertTrue($target->keyExists($ip));
    }
    
    static public function provideIPs(){
        return array(
            array('127.0.0.1')
            ,array('127.0.0.2')
            ,array('127.0.0.3')
            ,array('127.0.0.4')
        );
    }
    
    /**
     * @dataProvider provideBadIPs
     */
    public function testKeyExistsReturnsFalse($ip){
        $this->setUpDB();
        $target =$this->getHandler();
        $this->assertFalse($target->keyExists($ip));
    }
    
    static public function provideBadIPs(){
        return array(
            array('127.0.0.5')
            ,array('127.0.0.6')
            ,array('127.0.0.7')
            ,array('127.0.0.8')
        );
    }
    
    /**
     * @depends testKeyExists
     * @dataProvider provideKeys
     */
    public function testGetKey($ip,$key){
        $this->setUpDB();
        $db = $this->db;
        
        $_SERVER['REMOTE_ADDR'] = $ip;
        
        $target = $this->getHandler();
        
        $this->assertEquals($key,$target->getKey());
    }
    
    /**
     * @depends testGetKey
     * @dataProvider provideNewKeys
     */
    public function testInsertKey($ip,$key){
        $this->setUpDB();
        
        $_SERVER['REMOTE_ADDR'] = $ip;
        
        $target =$this->getHandler();
        
        $target->insertKey($key);
        
        $this->assertEquals($key,$target->getKey());
    }
    
    static public function provideKeys(){
        return array(
            array('127.0.0.1' , '1234')
            , array('127.0.0.2' , '12345')
            , array('127.0.0.3' , '123456')
            , array('127.0.0.4' , '1234a')
        );
    }
    
    static public function provideNewKeys(){
        return array(
            array('127.0.0.1' , '12345')
            , array('127.0.0.2' , '1245')
            , array('127.0.0.3' , '1234a56')
            , array('127.0.0.4' , '1234abc')
        );
    }
    /**
     * @dataProvider provideOKNames
     */
    public function testUserExistsOK($name){
        $this->setUpDB();
        
        $target =$this->getHandler();
        
        $this->assertTrue($target->userExists($name));
    }
    
    /**
     * @dataProvider provideBadNames
     */
    public function testUserExistsBad($name){
        $this->setUpDB();
        
        $target =$this->getHandler();
        
        $this->assertFalse($target->userExists($name));
    }
    
    static public function provideOKNames(){
        return array(
            array('arieh')
            ,array('yosi')
            ,array('bar')
            ,array('bob')
        );
    }
    
    static public function provideBadNames(){
        return array(
            array('arieh1')
            ,array('yosi2')
            ,array('bara')
            ,array('bob1')
        );
    }
    
    /**
     * @dataProvider providePasswords
     */
    public function testGetPass($name,$pass){
        $this->setUpDB();
        
        $target =$this->getHandler();
        
        $this->assertEquals($target->getPass($name),$pass);
    }
    
    static public function providePasswords(){
        return array(
            array('arieh','7110eda4d09e062aa5e4a390b0a572ac0d2c0220')
            ,array('yosi','8cb2237d0679ca88db6464eac60da96345513964')
            ,array('bar','7c4a8d09ca3762af61e59520943dc26494f8941b')
            ,array('bob','d5f12e53a182c062b6bf30c1445153faff12269a')
        );
    }
    
    /**
     *  @dataProvider provideOKNames
     *  @expectException InvaidArgumentException
     */
    public function testPassBadUser($name){
        $this->setUpDB();
        
        $target = $this->getHandler();
        
        $target->getPass($name);
    }
}
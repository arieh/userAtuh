<?php
require_once dirname(__FILE__) . '/classes/UserAtuhTestCase.class.php';
require_once dirname(__FILE__) . '/../classes/UserAtuhDbaSession.class.php';

class UserAtuhDbaSessionSessionTest extends UserAtuhTestCase{
    public function setUp(){
        $_SESSION[UserAtuhDbaSession::SESSION_NAME] = '';
    }
    
    protected function getHandler(){
        return new UserAtuhDbaSession( $this->db,array(
            'tableName' => 'users'
            , 'nameField' => 'name'
            , 'passField' => 'pass'
        ));
    }
    
    /**
     * @dataProvider provideKeys
     */
    public function testGetKey($key){
        $_SESSION[UserAtuhDbaSession::SESSION_NAME] = $key;
        
        $target = $this->getHandler();
        
        $this->assertEquals($key,$target->getKey());
    }
    
    /**
     * @dataProvider provideKeys
     */
    public function testInsertKey($key){
        $this->setUpDB();
        
        $target = $this->getHandler();
        
        $target->insertKey($key);
        
        $this->assertEquals($key,$_SESSION[UserAtuhDbaSession::SESSION_NAME]);
    }
    
    static public function provideKeys(){
        return array(
            array('1234')
            , array('12345')
            , array('123456')
            , array('1234567')
            , array('12345678')
            , array('abcde')
            , array('1a2b')
        );
    }
    
    /**
     * @dataProvider provideOKNames
     */
    public function testUserExistsOK($name){
        $this->setUpDB();
        
        $target = $this->getHandler();
        
        $this->assertTrue($target->userExists($name));
    }
    
    /**
     * @dataProvider provideBadNames
     */
    public function testUserExistsBad($name){
        $this->setUpDB();
        
        $target = $this->getHandler();
        
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
        
        $target = $this->getHandler();
        
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
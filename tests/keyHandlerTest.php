<?php
require_once dirname(__FILE__) . '/classes/UserAtuhTestCase.class.php';
require_once dirname(__FILE__) . '/../classes/KeyHandler.class.php';

class KeyHandlerTest extends UserAtuhTestCase{
    public function setUp(){
        parent::setUp();
        $_SERVER['REMOTE_ADDRESS'] = '127.0.0.1';
    }
    public function testGetKey(){
        $db = $this->getMock('DbaInterface');
        $handler = new KeyHandler($db);
        $this->assertEquals(strlen($handler->getKey()),40);
    }
    
    public function testGenerateKey(){
        $db = $this->getMock('DbaInterface',array('insertKey'));
        
        
        $handler = new KeyHandler($db);
        $key = $handler->getKey();
        
        $db->expects($this->once())->method('insertKey')->with($this->equalTo($key));
        $handler->generateKey();
    }
    /**
     * @dataProvider provideAuthOK
     */
    public function testAuthenticte($user,$pass,$key){
        $_SESSION['user_atuh'] = $key;
        $db = $this->getMock('DbaInterface',array('userExists','getPass','getKey','insertKey'));
        
        $db->expects($this->once())->method('userExists')->with($this->equalTo($user))->will($this->returnValue(true));
        
        $db->expects($this->once())->method('getPass')->with($this->equalTo($user))->will($this->returnValue($pass));
        
        $db->expects($this->once())->method('getKey')->will($this->returnValue($key));
        
        $handler = new KeyHandler($db);
        
        $enc = sha1(sha1($pass.$user.$key));
        
        $this->assertTrue($handler->authenticate($user,$enc));
    }
    
    static public function provideAuthOK(){
        return array(
            array('arieh','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','1v234')
            ,array('yosi','8cb2237d0679ca88db6464eac60da96345513964','12s34')
            ,array('bar','7c4a8d09ca3762af61e59520943dc26494f8941b','1a234')
            ,array('bob','d5f12e53a182c062b6bf30c1445153faff12269a','123c4')
        );
    }
    
    /**
     * @dataProvider provideAuthOK
     */
    public function testAuthenticteBad($user,$pass,$key){
        $_SESSION['user_atuh'] = $key;
        $db = $this->getMock('DbaInterface',array('userExists','getPass','getKey','insertKey'));
        
        $db->expects($this->once())->method('userExists')->with($this->equalTo($user))->will($this->returnValue(true));
        
        $db->expects($this->once())->method('getPass')->with($this->equalTo($user))->will($this->returnValue($pass));
        
        $db->expects($this->once())->method('getKey')->will($this->returnValue($key));
        
        $handler = new KeyHandler($db);
        
        $enc = sha1(sha1($pass.$user.$key.'a'));
        
        $this->assertFalse($handler->authenticate($user,$enc));
    }
}
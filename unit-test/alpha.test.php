<?php

class AlphaTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->rules = array(
            'test' => array('alpha')
        );
    }

    public function tearDown() {
        
    }

    public function testAlphaInput() {
        $inputs = array('test' => 'ABCDE');
        $validation_result = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validation_result->isSuccess(), true);
    }

    public function testAlphaNumericInput() {
        $inputs = array('test' => 'ABCDE123');
        $validation_result = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validation_result->isSuccess(), false);
    }

    public function testNonAlphaInput() {
        $inputs = array('test' => 'ABCDE123?!@');
        $validation_result = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validation_result->isSuccess(), false);
    }

}

?>

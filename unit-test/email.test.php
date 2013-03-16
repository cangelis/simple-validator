<?php

class Test extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once '../src/simple-validator.class.php';
        $this->rules = array(
            'test' => array('email')
        );
    }

    public function tearDown() {
        
    }

    public function testValidEmail() {
        $inputs = array('test' => 'geliscan@gmail.com');
        $validation_result = SimpleValidator::validate($inputs, $this->rules);
        $this->assertEquals($validation_result->isSuccess(), true);
    }

    public function testInvalidEmail() {
        $inputs = array('test' => 'SimpleValidator');
        $validation_result = SimpleValidator::validate($inputs, $this->rules);
        $this->assertEquals($validation_result->isSuccess(), false);
    }

    public function testEmptyInput() {
        $inputs = array(
            'test' => ''
        );
        $validator = SimpleValidator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

    public function testNullInput() {
        $inputs = array(
            'test' => null
        );
        $validator = SimpleValidator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

}

?>

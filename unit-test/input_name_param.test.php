<?php

class InputNameParamTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->rules = array(
            'test1' => array('equals(:test2)')
        );
    }

    public function testValidInputs() {
        $inputs = array(
            'test1' => 'foo',
            'test2' => 'foo'
        );
        $validation_result = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertTrue($validation_result->isSuccess());
    }

    public function testInvalidInputs() {
        $inputs = array(
            'test1' => 'foo',
            'test2' => 'foo2'
        );
        $validation_result = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertFalse($validation_result->isSuccess());
    }

    public function testNullParameterNameInputs() {
        $inputs = array(
            'test1' => 'foo'
        );
        $validation_result = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertFalse($validation_result->isSuccess());
    }

    public function testEmptyParameterNameInputs() {
        $inputs = array(
            'test1' => 'foo',
            'test2' => ''
        );
        $validation_result = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertFalse($validation_result->isSuccess());
    }

}

?>

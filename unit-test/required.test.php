<?php

class RequiredTest extends PHPUnit_Framework_TestCase {

    public $rules;

    public function setUp() {
        require_once '../simple-validator.class.php';
        $this->rules = array(
            'test' => array('required')
        );
    }

    public function tearDown() {
        
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

    public function testOnlyWhiteSpaceInput() {
        $inputs = array(
            'test' => ' '
        );
        $validator = SimpleValidator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

    public function testUnassignedInput() {
        $inputs = array();
        $validator = SimpleValidator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

    public function testNullInputArray() {
        $inputs = null;
        $validator = SimpleValidator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

}

?>

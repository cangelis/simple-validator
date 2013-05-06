<?php

class IntegerTest extends PHPUnit_Framework_TestCase {

    public $rules;

    public function setUp() {
        $this->rules = array(
            'test' => array('integer')
        );
    }

    public function testIntegerInput() {
        $inputs = array(
            'test' => 15
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testIntegerStringInput() {
        $inputs = array(
            'test' => "15"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testFloatInput() {
        $inputs = array(
            'test' => 15.5
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

    public function testStringInput() {
        $inputs = array(
            'test' => "test12"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

    public function testHexadecimalIntegerInput() {
        $inputs = array(
            'test' => 0x1A
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testNegativeIntegerInput() {
        $inputs = array(
            'test' => -15
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testOctalNumberInput() {
        $inputs = array(
            'test' => 0123
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testVeryBigInput() {
        $inputs = array(
            'test' => 9E19
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

    public function testVerySmallInput() {
        $inputs = array(
            'test' => -9E19
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

    public function testEmptyInput() {
        $inputs = array(
            'test' => ''
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

    public function testNullInput() {
        $inputs = array(
            'test' => null
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

}

?>

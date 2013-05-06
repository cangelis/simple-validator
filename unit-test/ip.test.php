<?php

class IPTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->rules = array(
            'test' => array('ip')
        );
    }

    public function tearDown() {
        
    }

    public function testValidIPv4Input() {
        $inputs = array(
            'test' => "89.250.130.65"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testValidFormatInvalidIPInput() {
        $inputs = array(
            'test' => "89.300.130.65"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

    public function testValidIPv6Input() {
        $inputs = array(
            'test' => "2a03:2880:10:1f02:face:b00c::25"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testInvalidFormatInput() {
        $inputs = array(
            'test' => "Simple Validator"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

}

?>

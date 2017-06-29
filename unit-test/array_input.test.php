<?php

class ArrayInputTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->rules = array(
            'foo[bar]' => array('equals(:bar[baz])')
        );
    }

    public function testArrayInputValid() {
        $inputs = array(
            'foo[bar]' => 'test1',
            'bar[baz]' => 'test1'
        );
        $validation_result = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertTrue($validation_result->isSuccess());
    }

    public function testArrayInputNotValid() {
        $inputs = array(
            'foo[bar]' => 'test1',
            'bar[baz]' => 'test2'
        );
        $validation_result = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertFalse($validation_result->isSuccess());
    }

}

?>

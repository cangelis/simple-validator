<?php

class MultipleParameterTest extends PHPUnit_Framework_TestCase {

    public $test_data;

    public function setUp() {
        $this->test_data = array(
            'test1' => 'test data 1',
            'test2' => 'test data 2',
            'test3' => 'test data 3'
        );
    }

    public function testValidValidation() {
        $rules = array(
            'test1' => array(
                'rule1(:test2,3,:test3)' => function($input, $test2, $value, $test3) {
                    if (($input == "test data 1") && ($value == 3) && ($test2 == "test data 2") && ($test3 == "test data 3"))
                        return true;
                    return false;
                }
            )
        );
        $validation = \SimpleValidator\Validator::validate($this->test_data, $rules);
        $this->assertTrue($validation->isSuccess());
    }

    public function testInvalidValidation() {
        $rules = array(
            'test1' => array(
                'rule1(:test2,3,:test3)' => function($input, $test2, $value, $test3) {
                    if (($input == "test data 1") && ($value == 3) && ($test2 == "test data 1") && ($test3 == "test data 1"))
                        return true;
                    return false;
                }
            )
        );
        $naming = array(
            'test2' => 'Test 2'
        );
        $validation = \SimpleValidator\Validator::validate($this->test_data, $rules, $naming);
        $this->assertFalse($validation->isSuccess());
        $validation->customErrors(array(
            'rule1' => "Foo :params(0) bar :params(1) baz :params(2)"
        ));
        $errors = $validation->getErrors();
        $this->assertEquals("Foo Test 2 bar 3 baz test3", $errors[0]);
    }

}

?>

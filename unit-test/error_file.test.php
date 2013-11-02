<?php

class ErrorFileTest extends PHPUnit_Framework_TestCase {

    public function testDefaultErrorFileInCurrentDirectory() {
        $validator = SimpleValidator\Validator::validate(array(), array('name' => array('required')));
        $this->assertEquals($validator->getErrors(), array('name field is required'));
    }

    public function testDefaultErrorFileInALevelAboveDirectory() {
        chdir("..");
        $validator = SimpleValidator\Validator::validate(array(), array('name' => array('required')));
        $this->assertEquals($validator->getErrors(), array('name field is required'));
    }

}

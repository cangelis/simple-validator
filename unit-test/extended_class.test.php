<?php

class CustomValidator extends SimpleValidator\Validator {

    public static function trueStaticRule($input) {
        return true;
    }

    public static function falseStaticRule($input) {
        return false;
    }

    public function trueRule($input) {
        return true;
    }

    public static function testParamStaticRule($input, $param) {
        return true;
    }

    public function testParamRule($input, $param) {
        return true;
    }

    public static function multipleParams($input, $param1, $param2, $param3) {
        if (($param1 == 1) && ($param2 == 2) && ($param3 == 3))
            return true;
        return false;
    }

}

class ExtendedClassTest extends PHPUnit_Framework_TestCase {

    public function testValidButNonStaticMethodRule() {
        $rules = array(
            'name' => array(
                'trueRule'
            )
        );
        try {
            CustomValidator::validate(null, $rules);
        } catch (SimpleValidator\SimpleValidatorException $e) {
            if ($e->getCode() == SimpleValidator\SimpleValidatorException::STATIC_METHOD)
                return;
            else
                $this->fail("Wrong Exception Code: " . $e->getCode());
        }
        $this->fail("Could not catched Exception");
    }

    public function testValidAndStaticMethodRule() {
        $rules = array(
            'name' => array(
                'trueStaticRule'
            )
        );
        try {
            $validation_result = CustomValidator::validate(null, $rules);
            $this->assertTrue($validation_result->isSuccess());
        } catch (SimpleValidator\SimpleValidatorException $e) {
            $this->fail("Exception occured: " . $e->getMessage());
        }
        return;
    }

    public function testInValidAndStaticMethodRule() {
        $rules = array(
            'name' => array(
                'falseStaticRule'
            )
        );
        try {
            $validation_result = CustomValidator::validate(null, $rules);
            $this->assertFalse($validation_result->isSuccess());
        } catch (SimpleValidator\SimpleValidatorException $e) {
            $this->fail("Exception occured: " . $e->getMessage());
        }
        return;
    }

    public function testValidAndParamStaticMethodRule() {
        $rules = array(
            'name' => array(
                'testParamStaticRule(test)'
            )
        );
        try {
            $validation_result = CustomValidator::validate(null, $rules);
            $this->assertTrue($validation_result->isSuccess());
        } catch (SimpleValidator\SimpleValidatorException $e) {
            $this->fail("Exception occured: " . $e->getMessage());
        }
        return;
    }

    public function testValidParamButNonStaticMethodRule() {
        $rules = array(
            'name' => array(
                'testParamRule(test)'
            )
        );
        try {
            CustomValidator::validate(null, $rules);
        } catch (SimpleValidator\SimpleValidatorException $e) {
            if ($e->getCode() == SimpleValidator\SimpleValidatorException::STATIC_METHOD)
                return;
            else
                $this->fail("Wrong Exception Code: " . $e->getCode());
        }
        $this->fail("Could not catched Exception");
    }

    public function testValidMultipleParams() {
        $rules = array(
            'name' => array(
                'multipleParams(1,2,3)'
            )
        );
        $validation = CustomValidator::validate(null, $rules);
        $this->assertTrue($validation->isSuccess());
    }

    public function testInvalidMultipleParams() {
        $rules = array(
            'name' => array(
                'multipleParams(3,2,1)'
            )
        );
        $validation = CustomValidator::validate(null, $rules);
        $this->assertFalse($validation->isSuccess());
    }

}

?>

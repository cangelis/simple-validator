<?php

namespace SimpleValidator;

class SimpleValidatorException extends \Exception {

    const NO_ERROR_TEXT = 1;
    const STATIC_METHOD = 2;
    const UNKNOWN_RULE = 3;
    const ARRAY_EXPECTED = 4;

    private static $error_messages;

    public function __construct($code, $param) {
        static::$error_messages = array(
            static::NO_ERROR_TEXT => "Error text could not found for ':param', or Error file could not found",
            static::STATIC_METHOD => "The method :param should be static",
            static::UNKNOWN_RULE => "Unknown Rule: :param",
            static::ARRAY_EXPECTED => "Rules are expected to Array. Input Name: :param"
        );
        parent::__construct(str_replace(":param", $param, static::$error_messages[$code]), $code);
    }

}

?>

<?php
require_once 'simple-validator-exception.class.php';
/**
 * Simple Validator Class for php
 * @author Can Geliş <geliscan@gmail.com>
 * @copyright (c) 2013, Can Geliş
 * @license https://github.com/cangelis/simple-validator/blob/master/licence.txt MIT Licence
 * @link https://github.com/cangelis/simple-validator
 */

/**
 * TODO: Exception handling for rules with parameters
 * TODO: unit tests for numeric, float, alpha, alpha_numeric, max_length, min_length, exact_length
 * TODO: add protection filters for several input vulnerabilities.
 */
class SimpleValidator {

    private $errors, $namings, $customErrorsWithInputName, $customErrors;

    /**
     * Constructor is not allowed because SimpleValidator uses its own
     * static method to instantiate the validaton
     */
    private function __construct($errors, $namings) {
        $this->errors = $errors;
        $this->namings = $namings;
    }

    /**
     * 
     * @return boolean
     */
    public function isSuccess() {
        return (empty($this->errors) == true);
    }

    /**
     * 
     * @param Array $errors_array
     */
    public function customErrors($errors_array) {
        foreach ($errors_array as $key => $value) {
            // handle input.rule eg (name.required)
            if (preg_match("#^(.+?)\.(.+?)$#", $key, $matches)) {
                // $this->customErrorsWithInputName[name][required] = error message
                $this->customErrorsWithInputName[(string) $matches[1]][(string) $matches[2]] = $value;
            } else {
                $this->customErrors[(string) $key] = $value;
            }
        }
    }

    /**
     * 
     * @param string $error_file
     * @return array
     * @throws SimpleValidatorException
     */
    public function getErrors($error_file = 'errors/en.php') {
        if (file_exists($error_file)) {
            $error_texts = include($error_file);
        } else {
            $error_texts = null;
        }
        foreach ($this->errors as $input_name => $results) {
            foreach ($results as $rule => $result) {
                // handle namings
                if (isset($this->namings[(string) $input_name])) {
                    $named_input = $this->namings[(string) $input_name];
                } else {
                    $named_input = $input_name;
                }
                // if there is a custom message with input name, apply it
                if (isset($this->customErrorsWithInputName[(string) $input_name][(string) $rule])) {
                    $error_message = $this->customErrorsWithInputName[(string) $input_name][(string) $rule];
                }
                // if there is a custom message for the rule, apply it
                else if (isset($this->customErrors[(string) $rule])) {
                    $error_message = $this->customErrors[(string) $rule];
                }
                // if no custom messages, then fetch from file
                else if (isset($error_texts[(string) $rule])) {
                    $error_message = $error_texts[(string) $rule];
                } else {
                    throw new SimpleValidatorException(SimpleValidatorException::NO_ERROR_TEXT, $rule);
                }
                $find = array(':attribute', ':input_param');
                $replace = array($named_input, $result['param']);
                $error_results[] = str_replace($find, $replace, $error_message);
            }
        }
        return $error_results;
    }

    public function getResults() {
        return $this->errors;
    }

    /**
     * 
     * @param Array $inputs
     * @param Array $rules
     * @param Array $naming
     * @return \SimpleValidator
     * @throws SimpleValidatorException
     */
    public static function validate($inputs, $rules, $naming = null) {
        $errors = null;
        foreach ($rules as $input => $input_rules) {
            if (is_array($input_rules)) {
                foreach ($input_rules as $rule => $closure) {
                    $param = null;
                    /**
                     * if the key of the $input_rules is numeric that means
                     * it's neither a lambda nor user function.
                     */
                    if (is_numeric($rule)) {
                        $rule = $closure;
                    }
                    /**
                     * if the method exists in here call it
                     */
                    if (@method_exists(get_called_class(), $rule)) {
                        $refl = new ReflectionMethod(get_called_class(), $rule);
                        if ($refl->isStatic()) {
                            $validation = static::$rule(@$inputs[(string) $input]);
                        } else {
                            throw new SimpleValidatorException(SimpleValidatorException::STATIC_METHOD, $rule);
                        }
                    }

                    /**
                     * method with a parameter
                     * rule(parameter)
                     * eg: max_length(9)
                     */ else if (preg_match("#^([a-zA-Z_]+)\(([a-zA-Z0-9]+)\)$#", $rule, $matches)) {
                        $refl = new ReflectionMethod(get_called_class(), $matches[1]);
                        if ($refl->isStatic()) {
                            $validation = $validation = static::$matches[1](@$inputs[(string) $input], $matches[2]);
                            $param = $matches[2];
                            $rule = $matches[1];
                        } else {
                            throw new SimpleValidatorException(SimpleValidatorException::STATIC_METHOD, $matches[1]);
                        }
                    }
                    /**
                     * if $closure is an anonymous function
                     * call it
                     */ else if (@get_class($closure) == 'Closure') {
                        $validation = $closure(@$inputs[(string) $input]);
                    } else {
                        throw new SimpleValidatorException(SimpleValidatorException::UNKNOWN_RULE, $rule);
                    }
                    if ($validation == false) {
                        $errors[(string) $input][(string) $rule]['result'] = false;
                        $errors[(string) $input][(string) $rule]['param'] = $param;
                    }
                }
            } else {
                throw new SimpleValidatorException(SimpleValidatorException::ARRAY_EXPECTED, $input);
            }
        }
        return new SimpleValidator($errors, $naming);
    }

    private static function required($input) {
        return (!empty($input) && (isset($input)) && (trim($input) != ''));
    }

    private static function numeric($input) {
        return is_numeric($input);
    }

    private static function email($input) {
        return filter_var($input, FILTER_VALIDATE_EMAIL);
    }

    private static function integer($input) {
        return is_int($input) || ($input == (string) (int) $input);
    }

    private static function float($input) {
        return is_float($input) || ($input == (string) (float) $input);
    }

    private static function alpha($input) {
        return (preg_match("#^[a-zA-Z]+$#", $input) == 1);
    }

    private static function alpha_numeric($input) {
        return (preg_match("#^[a-zA-Z0-9]+$#", $input) == 1);
    }

    private static function ip($input) {
        return filter_var($input, FILTER_VALIDATE_IP);
    }

    /*
     * TODO: need improvements for tel and urn urls. 
     * check out url.test.php for the test result
     * urn syntax: http://www.faqs.org/rfcs/rfc2141.html
     * 
     */

    private static function url($input) {
        return filter_var($input, FILTER_VALIDATE_URL);
    }

    private static function max_length($input, $length) {
        return (strlen($input) < $length);
    }

    private static function min_length($input, $length) {
        return (strlen($input) > $length);
    }

    private static function exact_length($input, $length) {
        return (strlen($input) == $length);
    }

}

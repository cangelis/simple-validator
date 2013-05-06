<?php

namespace SimpleValidator;

/**
 * Simple Validator Class for php
 * @author Can Geliş <geliscan@gmail.com>
 * @copyright (c) 2013, Can Geliş
 * @license https://github.com/cangelis/simple-validator/blob/master/licence.txt MIT Licence
 * @link https://github.com/cangelis/simple-validator
 */

/**
 * TODO: Exception handling for rules with parameters
 * TODO: unit tests for numeric, float, alpha_numeric, max_length, min_length, exact_length
 * TODO: add protection filters for several input vulnerabilities.
 */
class Validator {

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
    public function getErrors($lang = 'en') {
        if (file_exists(__DIR__ . "/errors/" . $lang . ".php")) {
            $error_texts = include(__DIR__ . "/errors/" . $lang . ".php");
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
                /**
                 * if parameters are input name they should be named as well
                 */
                foreach ($result['params'] as $key => $param) {
                    if (preg_match("#^:([a-zA-Z0-9_]+)$#", $param, $param_type)) {
                        if (isset($this->namings[(string) $param_type[1]]))
                            $result['params'][$key] = $this->namings[(string) $param_type[1]];
                        else
                            $result['params'][$key] = $param_type[1];
                    }
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
                /**
                 * handle :params(..)
                 */
                if (preg_match_all("#:params\((.+?)\)#", $error_message, $param_indexes))
                    foreach ($param_indexes[1] as $param_index) {
                        $error_message = str_replace(":params(" . $param_index . ")", $result['params'][$param_index], $error_message);
                    }
                $error_results[] = str_replace(":attribute", $named_input, $error_message);
            }
        }
        return $error_results;
    }

    /**
     * 
     * @return boolean
     */
    public function has($input_name, $rule_name = null) {
        if ($rule_name != null)
            return isset($this->errors[$input_name][$rule_name]);
        return isset($this->errors[$input_name]);
    }

    public function getResults() {
        return $this->errors;
    }

    /**
     * Gets the parameter names of a rule
     * @param type $rule
     * @return mixed
     */
    private static function getParams($rule) {
        if (preg_match("#^([a-zA-Z0-9_]+)\((.+?)\)$#", $rule, $matches)) {
            return array(
                'rule' => $matches[1],
                'params' => explode(",", $matches[2])
            );
        }
        return array(
            'rule' => $rule,
            'params' => array()
        );
    }

    /**
     * Handle parameter with input name
     * eg: equals(:name)
     * @param mixed $params
     * @return mixed
     */
    private static function getParamValues($params, $inputs) {
        foreach ($params as $key => $param) {
            if (preg_match("#^:([a-zA-Z0-9_]+)$#", $param, $param_type)) {
                $params[$key] = @$inputs[(string) $param_type[1]];
            }
        }
        return $params;
    }

    /**
     * 
     * @param Array $inputs
     * @param Array $rules
     * @param Array $naming
     * @return Validator
     * @throws SimpleValidatorException
     */
    public static function validate($inputs, $rules, $naming = null) {
        $errors = null;
        foreach ($rules as $input => $input_rules) {
            if (is_array($input_rules)) {
                foreach ($input_rules as $rule => $closure) {
                    /**
                     * if the key of the $input_rules is numeric that means
                     * it's neither an anonymous nor an user function.
                     */
                    if (is_numeric($rule)) {
                        $rule = $closure;
                    }
                    $rule_and_params = static::getParams($rule);
                    $params = $real_params = $rule_and_params['params'];
                    $rule = $rule_and_params['rule'];
                    $params = static::getParamValues($params, $inputs);
                    array_unshift($params, $inputs[(string) $input]);
                    /**
                     * Handle anonymous functions
                     */
                    if (@get_class($closure) == 'Closure') {
                        $refl_func = new \ReflectionFunction($closure);
                        $validation = $refl_func->invokeArgs($params);
                    }/**
                     * handle class methods 
                     */ else if (@method_exists(get_called_class(), $rule)) {
                        $refl = new \ReflectionMethod(get_called_class(), $rule);
                        if ($refl->isStatic()) {
                            $refl->setAccessible(true);
                            $validation = $refl->invokeArgs(null, $params);
                        } else {
                            throw new SimpleValidatorException(SimpleValidatorException::STATIC_METHOD, $rule);
                        }
                    } else {
                        throw new SimpleValidatorException(SimpleValidatorException::UNKNOWN_RULE, $rule);
                    }
                    if ($validation == false) {
                        $errors[(string) $input][(string) $rule]['result'] = false;
                        $errors[(string) $input][(string) $rule]['params'] = $real_params;
                    }
                }
            } else {
                throw new SimpleValidatorException(SimpleValidatorException::ARRAY_EXPECTED, $input);
            }
        }
        return new static($errors, $naming);
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

    private static function equals($input, $param) {
        return ($input == $param);
    }

}


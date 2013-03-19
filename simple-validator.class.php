<?php

/**
 * Simple Validator Class for php
 * @author Can Geliş <geliscan@gmail.com>
 * @copyright (c) 2013, Can Geliş
 * @license https://github.com/cangelis/simple-validator/blob/master/licence.txt MIT Licence
 * @link https://github.com/cangelis/simple-validator
 */

/**
 * TODO: user defined functions
 * TODO: unit tests for numeric, float, alpha, alpha_numeric, max_length, min_length, exact_length
 * TODO: add protection filters for several input vulnerabilities.
 */
class SimpleValidator {

    private $errors;

    /**
     * Constructor is not allowed because SValidator uses its own
     * static method to instantiate the validaton
     */
    private function __construct($errors) {
        $this->errors = $errors;
    }

    public function isSuccess() {
        return (empty($this->errors) == true);
    }

    public function getErrors($error_file = 'errors/en.php') {
        if (file_exists($error_file)) {
            $error_texts = include($error_file);
            foreach ($this->errors as $input_name => $results) {
                foreach ($results as $rule => $result) {
                    if (isset($error_texts[$rule])) {
                        $find = array(':attribute', ':input_param');
                        $replace = array($input_name, $result['param']);
                        $error_results[] = str_replace($find, $replace, $error_texts[$rule]);
                    } else {
                        throw new Exception("Error text could not found for '" . $rule . "'");
                    }
                }
            }
            return $error_results;
        }
        else
            throw new Exception('Error file could not found');
    }

    public function getResults() {
        return $this->errors;
    }

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
                    if (@method_exists('SimpleValidator', $rule)) {
                        $validation = static::$rule(@$inputs[$input]);
                    }

                    /**
                     * method with a parameter
                     * rule(parameter)
                     * eg: max_length(9)
                     */ else if (preg_match("#^([a-zA-Z_]+)\(([a-zA-Z0-9]+)\)$#", $rule, $matches)) {
                        $validation = $validation = static::$matches[1](@$inputs[$input], $matches[2]);
                        $param = $matches[2];
                        $rule = $matches[1];
                    }
                    /**
                     * if $closure is an anonymous function
                     * call it
                     */ else if (get_class($closure) == 'Closure') {
                        $validation = $closure(@$inputs[$input]);
                    } else {
                        throw new Exception('Unknown Rule: "' . $rule . '"');
                    }
                    if ($validation == false) {
                        if (isset($naming[$input]))
                            $input = $naming[$input];
                        $errors[(string) $input][(string) $rule]['result'] = false;
                        $errors[(string) $input][(string) $rule]['param'] = $param;
                    }
                }
            } else {
                throw new Exception("Rules are expected as an Array. Input Name: " . $input);
            }
        }
        return new SimpleValidator($errors);
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

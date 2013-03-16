<?php

function errorList() {
    /**
     * :attribute => input name
     * :input_param => input parameter ( eg: 10 of max_length(10) )
     */
    return array(
        'required' => ':attribute field is required',
        'integer' => ':attribute field must be an integer',
        'float' => ':attribute field must be a float',
        'numeric' => ':attribute field must be numeric',
        'email' => ':input is not a valid email',
        'alpha' => ':attribute field must be an alpha value',
        'alpha_numeric' => ':attribute field must be alphanumeric',
        'ip' => ':attribute must contain a valid IP',
        'url' => ':attribute must contain a valid URL',
        'max_length' => ':attribute can be maximum :input_param character long',
        'min_length' => ':attribute must be minimum :input_param character long',
        'exact_length' => ':attribute field must :input_param character long',
        'my_func' => 'naber panps'
    );
}
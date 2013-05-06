<?php
/**
 * :attribute => input name
 * :params => rule parameters ( eg: :params(0) = 10 of max_length(10) )
 */
return array(
    'required' => ':attribute field is required',
    'integer' => ':attribute field must be an integer',
    'float' => ':attribute field must be a float',
    'numeric' => ':attribute field must be numeric',
    'email' => ':attribute is not a valid email',
    'alpha' => ':attribute field must be an alpha value',
    'alpha_numeric' => ':attribute field must be alphanumeric',
    'ip' => ':attribute must contain a valid IP',
    'url' => ':attribute must contain a valid URL',
    'max_length' => ':attribute can be maximum :params(0) character long',
    'min_length' => ':attribute must be minimum :params(0) character long',
    'exact_length' => ':attribute field must :params(0) character long',
    'equals' => ':attribute field should be same as :params(0)'
);

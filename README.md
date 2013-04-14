# Simple Validator Documentation

Simple validator is an awesome and easy to use validator for php

## Install

Download all the files and `composer.phar` and run this command:

```
php composer.phar install
```

## A few examples:

```php
<?php
$rules = array(
    'name' => array(
        'required',
        'alpha',
        'max_length(50)'
    ),
    'age' => array(
        'required',
        'integer',
    ),
    'email' => array(
        'required',
        'email'
    ),
    'password' => array(
        'required',
        'equals(:password_verify)'
    ),
    'password_verify' => array(
        'required'
    )
);
$validation_result = SimpleValidator\Validator::validate($_POST, $rules);
if ($validation_result->isSuccess() == true) {
    echo "validation ok";
} else {
    echo "validation not ok";
    var_dump($validation_result->getErrors());
}
```

## Custom Rules with anonymous functions

Lambda functions make the custom validations easier to be implemented.

### Example

```php
$rules = array(
    'id' => array(
        'required',
        'integer',
        'post_exists' => function($input) {
            $query = mysqli_query("SELECT * FROM post WHERE id = " . $input);
            if (mysqli_num_rows($query) == 0)
                return false;
            return true;
        },
        'special_id(5)' => function($input, $param) {
            if ($input == $param)
                return false;
            return true;
        }
    )
);
```
    
and you need to add an error text for your rule to the error file (default: errors/en.php).

```php
'post_exists' => "Post does not exist"
```
    
or add a custom error text for that rule

```php
$validation_result->customErrors(array(
    'post_exists' => 'Post does not exist'
));
```
    
### Another example to understand scoping issue

```php
// my local variable
$var_to_compare = "1234";
$rules = array(
    'password' => array(
        'required',
        'integer',
        // pass my local variable to anonymous function
        'is_match' => function($input) use (&$var_to_compare) {
            if ($var_to_compare == $input)
                return true;
            return false;
        }
    )
);
```

## Custom Rules that extend SimpleValidator

```php

namespace SimpleValidator;

require_once 'simple-validator.class.php';

class MyValidator extends Validator {

    // methods have to be static !!!
    public static function is_awesome($input) {
        if ($input == "awesome")
            return true;
        return false;
    }

    //validation rule with a parameter
    public static function is_equal($input, $param) {
        if ($input == $param)
            return true;
        return false;
    }

}

```

And then, call the `validate` method.
   
```php
$rules = array(
    'name' => array(
        'is_awesome',
        'is_equal(Michael)'
    )
)
$validation_result = SimpleValidator\MyValidator::validate($_POST, $rules);
```

**Note:** Error texts for the rules should be defined as well as the anonymous functions. 

## Custom Error messages

### Using Error file
Create a new file to somewhere example: ```errors/es.php```
and call ```getErrors()``` method using:

```php
$validation_result->getErrors('es');
```
### Using customErrors method
You can add custom errors using customErrors method.
#### Examples:
```php
$validation_result->customErrors(array(
    // input_name.rule => error text
    'website.required' => 'We need to know your web site',
    // rule => error text
    'required' => ':attribute field is required',
    'name.alpha' => 'Name field must contain alphabetical characters',
    'email_addr.email' => 'Email should be valid',
    'email_addr.min_length' => 'Hey! Email is shorter than :input_param',
    'min_length' => ':attribute must be longer than :input_param'
));
```
## Naming Inputs

```php
$naming => array(
    'name' => 'Name',
    'url' => 'Web Site',
    'password' => 'Password',
    'password_verify' => 'Password Verification'
);
$validation_result = SimpleValidator\Validator::validate($_POST, $rules, $naming);
```
#### Output sample:

* Name field is required <i>-instead of "name field is required"-</i>
* Web Site field is required <i>-instead of "url field is required"-</i>
* Password field should be same as Password Verification <i>-equals(:password_verify) rule-</i>

## More

You can explicitly check out the validations using `has` method
    

```php
// All return boolean
$validation_result->has('email');
$validation_result->has('email','required');
$validation_result->has('password','equals');
```

## Default validations

<table>
    <tr>
        <th>Rule</th>
        <th>Parameter</th>
        <th>Description</th>
        <th>Example</th>
    </tr>
    <tr>
        <td>required</td>
        <td>No</td>
        <td>Returns FALSE if the input is empty</td>
        <td></td>
    </tr>
    <tr>
        <td>numeric</td>
        <td>No</td>
        <td>Returns FALSE if the input is not numeric</td>
        <td></td>
    </tr>
    <tr>
        <td>email</td>
        <td>No</td>
        <td>Returns FALSE if the input is not a valid email address</td>
        <td></td>
    </tr>
    <tr>
        <td>integer</td>
        <td>No</td>
        <td>Returns FALSE if the input is not an integer value</td>
        <td></td>
    </tr>
    <tr>
        <td>float</td>
        <td>No</td>
        <td>Returns FALSE if the input is not a float value</td>
        <td></td>
    </tr>
    <tr>
        <td>alpha</td>
        <td>No</td>
        <td>Returns FALSE if the input contains non-alphabetical characters</td>
        <td></td>
    </tr>
   <tr>
        <td>alpha_numeric</td>
        <td>No</td>
        <td>Returns FALSE if the input contains non-alphabetical and numeric characters</td>
        <td></td>
    </tr>
    <tr>
        <td>ip</td>
        <td>No</td>
        <td>Returns FALSE if the input is not a valid IP (IPv6 supported)</td>
        <td></td>
    </tr>
    <tr>
        <td>url</td>
        <td>No</td>
        <td>Returns FALSE if the input is not a valid URL</td>
        <td></td>
    </tr>
    <tr>
        <td>max_length</td>
        <td>Yes</td>
        <td>Returns FALSE if the input is longer than the parameter</td>
        <td>max_length(10)</td>
    </tr>
    <tr>
        <td>min_length</td>
        <td>Yes</td>
        <td>Returns FALSE if the input is shorter than the parameter</td>
        <td>min_length(10)</td>
    </tr>
    <tr>
        <td>exact_length</td>
        <td>Yes</td>
        <td>Returns FALSE if the input is not exactly parameter value long</td>
        <td>exact_length(10)</td>
    </tr>
    <tr>
        <td>equals</td>
        <td>Yes</td>
        <td>Returns FALSE if the input is not same as the parameter</td>
        <td>equals(:password_verify) or equals(foo)</td>
    </tr>
</table>

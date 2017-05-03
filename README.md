# Simple Validator Documentation

[![Build Status](https://travis-ci.org/cangelis/simple-validator.png?branch=master)](https://travis-ci.org/cangelis/simple-validator)

Validation is the process that checks for correctness, meaningfulness and security of the input data. 
SimpleValidator is a library that handles validation process easily.

## Install

### Including to your current composer.json

Add this line into `require` in your ***composer.json***:

```
"simple-validator/simple-validator": "1.0.*"
```

and call 

```
php composer.phar update
```

### Installing directly

Download  `composer.phar` and call

```
php composer.phar install
```

and use autoload.php to include the classes

```php
require 'vendor/autoload.php'
```

## A few examples:

```php
<?php
$rules = [
    'name' => [
        'required',
        'alpha',
        'max_length(50)'
    ],
    'age' => [
        'required',
        'integer',
    ],
    'email' => [
        'required',
        'email'
    ],
    'password' => [
        'required',
        'equals(:password_verify)'
    ],
    'password_verify' => [
        'required'
    ]
];
$validation_result = SimpleValidator\Validator::validate($_POST, $rules);
if ($validation_result->isSuccess() == true) {
    echo "validation ok";
} else {
    echo "validation not ok";
    var_dump($validation_result->getErrors());
}
```

## Custom Rules with anonymous functions

Anonymous functions make the custom validations easier to be implemented. 

### Example

```php
$rules = [
    'id' => [
        'required',
        'integer',
        'post_exists' => function($input) {
            $query = mysqli_query("SELECT * FROM post WHERE id = " . $input);
            if (mysqli_num_rows($query) == 0)
                return false;
            return true;
        },
        'between(5,15)' => function($input, $param1, $param2) {
            if (($input > $param1) && ($input < $param2))
                return true;
            return false;
        }
    ]
];
```

and you need to add an error text for your rule to the error file (default: errors/en.php).

```php
'post_exists' => "Post does not exist"
```
    
or add a custom error text for that rule

```php
$validation_result->customErrors([
    'post_exists' => 'Post does not exist'
]);
```
    
### Another example to understand scoping issue

```php
// my local variable
$var_to_compare = "1234";
$rules = [
    'password' => [
        'required',
        'integer',
        // pass my local variable to anonymous function
        'is_match' => function($input) use (&$var_to_compare) {
            if ($var_to_compare == $input)
                return true;
            return false;
        }
    ]
];
```

## Custom Validators

You can assume SimpleValidator as a tool or an interface to create a validator for yourself.

Custom validators can have their own rules, own error files or default language definitions. In addition, you can override default rules in your custom validator.

```php

class MyValidator extends \SimpleValidator\Validator {

    // methods have to be static !!!
    protected static function is_awesome($input) {
        if ($input == "awesome")
            return true;
        return false;
    }

    // overriding a default rule (url)
    protected static function url($input) {
        return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $input);
    }

    // set default language for your validator
    // if you don't override this method, the default language is "en"
    protected function getDefaultLang() {
        return getMyApplicationsDefaultLanguage();
    }

    // defining error files for your validator
    // in this example your files should live in "{class_path}/errors/{language}/validator.php
    protected function getErrorFilePath($lang) {
        return __DIR__ . "/errors/" . $lang . "/validator.php";
    }

}

```

**Create an error file:**

```php
return [
    'is_awesome' => 'the :attribute is not awesome'
    // error text for url is already defined in default error text file you don't have to define it here, but optionally you can
];
```

And then, call the `validate` method.
   
```php
$rules = [
    'website' => [
        'is_awesome',
        'url'
    ]
];
$validation_result = MyValidator::validate($_POST, $rules);
```

## Custom Rule parameters

A rule can have multiple parameters. An example:

```php
$rule = [
    'id' => [
        'rule1(:input1,:input2,2,5,:input3)' => function($input, $input1, $input2, $value1, $value2, $input3) {
            // validation here
        }
    ],
    // and so on..
];


```

## Custom Error messages

### Using Error file
Custom rules provides localization for the error messages.
Create a new file under **errors** folder, example: ```errors/es.php```
and call ```getErrors()``` method using:

```php
$validation_result->getErrors('es');
```
### Using customErrors method
You can add custom errors using customErrors method.
#### Examples:
```php
$validation_result->customErrors([
    // input_name.rule => error text
    'website.required' => 'We need to know your web site',
    // rule => error text
    'required' => ':attribute field is required',
    'name.alpha' => 'Name field must contain alphabetical characters',
    'email_addr.email' => 'Email should be valid',
    'email_addr.min_length' => 'Hey! Email is shorter than :params(0)',
    'min_length' => ':attribute must be longer than :params(0)'
]);
```
## Naming Inputs

```php
$naming => [
    'name' => 'Name',
    'url' => 'Web Site',
    'password' => 'Password',
    'password_verify' => 'Password Verification'
];
$validation_result = SimpleValidator\Validator::validate($_POST, $rules, $naming);
```
#### Output sample:

* Name field is required <i>-instead of "name field is required"-</i>
* Web Site field is required <i>-instead of "url field is required"-</i>
* Password field should be same as Password Verification <i>-equals(:password_verify) rule-</i>

## More

You can explicitly check out the validations using `has` method that might be useful for Unit Testing purposes.
    

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

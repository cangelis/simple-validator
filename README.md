# Simple Validator Documentation

Simple validator is an awesome and easy to validator for php

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
        'required'
        'email'
    )
);
$validation_result = SimpleValidator::validate($_POST, $rules);
if ($validation_result->isSuccess() == true) {
    echo "validation ok";
    var_dump($validation_result->getErrors());
} else {
    echo "validation not ok";
}
```

## Custom Rules

Lambda functions make the custom validations easier to be implemented.

### Example

```php
$rules = array(
    'my_rule' => function($input) {
        if ($input == "SimpleValidator")
            return true;
        return false;
    }
);
```

and you need to add a error text for your rule to the error file (default: errors/en.php).

```php
    'my_rule' => ":attribute field must be SimpleValidator"
```

## Custom Error messages

Create a new file to somewhere example: ```errors/my_errors.php```
and call ```getErrors()``` method using:

```$validation_result->getErrors('errors/my_errors.php');```


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
        'required',
        'email'
    )
);
$validation_result = SimpleValidator::validate($_POST, $rules);
if ($validation_result->isSuccess() == true) {
    echo "validation ok";
} else {
    echo "validation not ok";
    var_dump($validation_result->getErrors());
}
```

## Custom Rules

Lambda functions make the custom validations easier to be implemented.

### Example

```php
$rules = array(
    'id' => array(
        'required',
        'integer',
        'post_exists' => function($input) {
            $query = mysqli_query("SELECT * FROM post WHERE id = ".$input);
            if (mysqli_num_rows($query) == 0)
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

## Custom Error messages

Create a new file to somewhere example: ```errors/my_errors.php```
and call ```getErrors()``` method using:

```php
$validation_result->getErrors('errors/my_errors.php');
```
## Naming Inputs

```php
$naming => array(
    'name' => 'Name',
    'url' => 'Web Site'
);
$validation_result = SimpleValidator::validate($_POST, $rules, $naming);
```
Output sample:

* Name field is required <i>instead of "name field is required"</i>
* Web Site field is required <i>instead of "url field is required"</i>

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
</table>

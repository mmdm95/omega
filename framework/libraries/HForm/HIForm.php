<?php

namespace HForm;

interface HIForm
{
    /**
     * Set name of method[get/post] parameters that wants to check
     * For remove all empty values, enter the 'all' value for $skipEmptyValues
     *
     * @param array $names
     * @param array|string $skipEmptyValues - names of fields that are array and want to skip(delete/remove) empty values
     * @return mixed
     */
    public function setFieldsName(array $names, $skipEmptyValues = []);

    /**
     * Set form method -> get/post
     * $exceptions if for specify field(s) name that are not get/post
     * Like: 'fileInput' => 'files' that translates to $_FILES['fileInput']
     *
     * @param string $method
     * @param mixed $exceptions - e.g. ['fieldName' => 'methodName'] like ['name' => 'get']
     * @param array $mustNotCheck - e.g. ['checkbox', ...]
     * @return mixed
     */
    public function setMethod($method, $exceptions = [], $mustNotCheck = []);

    /**
     * Return all fields name
     *
     * @return mixed
     *
     */
    public function getFieldsName();

    /**
     * Get values of fields name from the method
     *
     * @return mixed
     *
     */
    public function getValues();

    /**
     * Get value of a specific field name from the method
     *
     * @param $name
     * @return mixed
     *
     */
    public function getValue($name);

    /**
     * Get csrf token field to prevent csrf bug
     * <p>Return somthing like this: <b><input type="hidden" value="..." name="$inputName"></b></p>
     *
     * @param string$csfrName
     * @param string $inputName
     * @return string
     *
     */
    public function csrfToken($csfrName, $inputName = 'csrftoken');

    /**
     * Validate $name field with error message $msg with type of $type
     *
     * @param string $type
     * @param string $name
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return mixed
     */
    public function validate($type, $name, $msg, $callback = null);

    /**
     * Create your own field validation with passing validation as a function to this method
     *
     * @param string $name
     * @param callable $callback
     * @return mixed
     *
     */
    public function customValidate($name, $callback);

    /**
     * Check if field $name is between $min and $max with error message $msg
     *
     * @param string $name
     * @param int $min
     * @param int $max
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return mixed
     *
     */
    public function isInRange($name, $min, $max, $msg, $callback = null);

    /**
     * Check if field $name is in array $values with error message $msg
     *
     * @param string $name
     * @param array $values
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return mixed
     *
     */
    public function isIn($name, array $values, $msg, $callback = null);

    /**
     * Check if field $name is <b>not</b> in array $values with error message $msg
     *
     * @param string $name
     * @param array $values
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return mixed
     *
     */
    public function isNotIn($name, array $values, $msg, $callback = null);

    /**
     * Check if field $name is <b>not</b> empty with error message $msg
     *
     * @param array|string $name
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return mixed
     *
     */
    public function isRequired($name, $msg, $callback = null);

    /**
     * Check if field $name <b>is</b> empty with error message $msg
     *
     * @param array|string $name
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return mixed
     *
     */
    public function isNotRequired($name, $msg, $callback = null);

    /**
     * Check if field $name is equals to $length
     *
     * @param string $name
     * @param int $length
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return mixed
     */
    public function isLengthEquals($name, $length, $msg, $callback = null);

    /**
     * Check if field $name length is between $minLength and $maxLength
     *
     * @param string $name
     * @param int $minLength
     * @param int $maxLength
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return mixed
     */
    public function isLengthInRange($name, $minLength, $maxLength, $msg, $callback = null);

    /**
     * Check if field $name is <b>not</b> equals to $length
     *
     * @param string $name
     * @param int $length
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return mixed
     */
    public function isLengthNotEquals($name, $length, $msg, $callback = null);

    /**
     * Remove all error to form error(s) array
     *
     * @return mixed
     *
     */
    public function removeErrors();

    /**
     * Set error to form error(s) array
     *
     * @param string $msg
     * @return mixed
     *
     */
    public function setError($msg);

    /**
     * Get all errors of form
     *
     * @return mixed
     *
     */
    public function getError();

    /**
     * Distinct errors messages or not
     *
     * @param bool $bool
     * @return mixed
     */
    public function distinctErrors($bool = true);

    /**
     * Remove empty error message(s) or not
     *
     * @param bool $bool
     * @return mixed
     *
     */
    public function removeEmptyMessages($bool = true);

    /**
     * Check that if form is submitted or not
     *
     * Handle form with returns
     * Return 0 => Form variables not set, 1 => Succeed and 2 => Form token error
     *
     * @return mixed
     *
     */
    public function checkForm();

    /**
     * If there is error or not
     * Return true if there is no error and false otherwise
     * Translate of returns: 0 => not submitted, 1 => succeed, 2 => have error
     *
     * @return mixed
     *
     */
    public function isSuccess();

    /**
     * Set to clear values of fields after all operations done or not
     *
     * @param bool $bool
     * @return mixed
     *
     */
    public function clearVariablesOnSuccess($bool = true);

    /**
     * Callback after the form has no error
     *
     * @param callable $callback
     * @return mixed
     *
     */
    public function afterCheckCallback($callback);

    /**
     * Callback before check that if form has error
     *
     * @param callable $callback
     * @return mixed
     *
     */
    public function beforeCheckCallback($callback);
}

<?php

namespace HForm;

defined('BASE_PATH') OR exit('No direct script access allowed');

use Maer\Security\Csrf\Csrf;
use voku\helper\AntiXSS;

include_once LIB_PATH . 'Maer-CSRF/vendor/autoload.php';
include_once LIB_PATH . 'XSS/vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    $fileToTest = str_replace('\\', DS, LIB_PATH . $class_name . '.class.php');
    $file = str_replace('\\', DS, LIB_PATH . $class_name . '.php');
    if (file_exists($fileToTest)) {
        include $fileToTest;
    } else if (file_exists($file)) {
        include $file;
    }
});

// TODO: replace array_filter with some of codes and reduce code lines
class Form implements HIForm
{
    /**
     * The fields name
     *
     * @var array
     *
     */
    protected $fieldsName = [];

    /**
     * Default value for inputs
     *
     * @var array
     *
     */
    protected $defaults = [];

    /**
     * Name of skipping fields that want to remove empty values
     *
     * @var array
     *
     */
    protected $skippedValuesName = [];

    /**
     * The fields that must check if they are submitted or not
     *
     * @var array
     *
     */
    protected $checkableFieldsName = [];

    /**
     * Values of names. e.g. 'name' => 'value'
     *
     * @var array
     *
     */
    protected $fieldsValues = [];

    /**
     * Mapped name of names to its method. e.g. 'name' => 'method'
     *
     * @var array
     *
     */
    protected $mappedNamesToMethod = [];

    /**
     * Method that is used for submitting form
     *
     * @var string|null
     *
     */
    protected $method = null;

    /**
     * The csrf object
     *
     * @var Csrf|null
     *
     */
    protected $csrf = null;

    /**
     * Csrf field names
     *
     * @var array|null
     *
     */
    protected $csrfFieldNames = null;

    /**
     * Regenerate csrf token or not
     *
     * @var bool
     *
     */
    protected $csrfRegenerate = false;

    /**
     * Allow to use csrf or not
     *
     * @var bool
     *
     */
    protected $useCsrf = true;

    /**
     * The xss object
     *
     * @var \XSS\AntiXSS|null
     *
     */
    protected $xss = null;

    /**
     * Store option for xss
     *
     * @var array
     *
     */
    protected $xssOptions = [];

    /**
     * Allow to use xss or not
     *
     * @var bool
     *
     */
    protected $useXss = true;

    /**
     * Error array
     *
     * @var array
     *
     */
    protected $errors = [];

    /**
     * Clear values of fields that we store or not
     *
     * @var bool
     *
     */
    protected $clearVariables = true;

    /**
     * Make error distinct or not
     *
     * @var bool
     *
     */
    protected $distinctErrors = true;

    /**
     * Remove empty string message(s) or not
     *
     * @var bool
     *
     */
    protected $removeEmptyErrorMsg = true;

    /**
     * The function that must run before check for any error(s)
     *
     * @var callable|null
     *
     */
    protected $beforeFunction = null;

    /**
     * The function that must run after validation complete and have no error(s)
     *
     * @var callable|null
     *
     */
    protected $afterFunction = null;

    /**
     * Validate input if we have supplied to
     *
     * @var bool
     *
     */
    protected $continueSubmit = false;

    /**
     * Status of from
     *
     * @var int
     *
     */
    protected $formStatus = -1;

    /**
     * Validation types to the validation function
     *
     * @var array
     *
     */
    protected $types = [
        'int' => 'is_numeric',
        'integer' => 'is_numeric',
        'numeric' => 'is_numeric',
        'string' => 'is_string',
        'object' => 'is_object',
        'array' => 'is_array',
        'dir' => 'is_dir',
        'float' => 'is_float',
        'double' => 'is_float',
        'file' => 'is_file',
        'exists' => 'file_exists',
        'exist' => 'file_exists',
        'nan' => 'is_nan',
        'null' => 'is_null',
        'readable' => 'is_readable',
        'writable' => 'is_writable',
        'writeable' => 'is_writable',
        'checked' => [__CLASS__, '_is_checked'],
    ];

    /**
     * Form constructor.
     * @param bool $useCsrf
     * @param bool $useXss
     */
    public function __construct($useCsrf = true, $useXss = true)
    {
        $this->xssOptions['evil'] = [];
        $this->xssOptions['naughty'] = [];

        $this->useCsrf = $useCsrf;
        if ($useCsrf) {
            $this->csrf = new Csrf();
        }
        $this->useXss = $useXss;
        if ($useXss) {
            $this->xss = new AntiXSS();
        }
    }

    /**
     * Set name of method[get/post] parameters that wants to check
     * For remove all empty values, enter the 'all' value for $skipEmptyValues
     *
     * @param array $names
     * @param array|string $skipEmptyValues - names of fields that are array and want to skip(delete/remove) empty values
     * @return Form
     */
    public function setFieldsName(array $names, $skipEmptyValues = [])
    {
        $this->fieldsName = $names;
        if (is_array($skipEmptyValues)) {
            $this->skippedValuesName = $skipEmptyValues;
        } else if (is_string($skipEmptyValues) && mb_strtolower($skipEmptyValues) == 'all') {
            $this->skippedValuesName = $names;
        }
        return $this;
    }

    /**
     * Set form method -> get/post
     * $exceptions if for specify field(s) name that are not get/post
     * Like: 'fileInput' => 'files' that translates to $_FILES['fileInput']
     *
     * @param string $method
     * @param array $exceptions - e.g. ['fieldName' => 'methodName'] like ['name' => 'get']
     * @param array $mustNotCheck - e.g. ['checkbox', ...]
     * @return Form
     */
    public function setMethod($method, $exceptions = [], $mustNotCheck = [])
    {
        $this->method = $method;
        $this->_mapFieldsToMethod($exceptions, $mustNotCheck);
        $this->_getFieldsValue();
        return $this;
    }

    /**
     * Set default value if $name is not set or null
     * NOTICE: Use this method before using [SetMethod] method!
     *
     * @param array|string $name
     * @param array|string $default
     * @return $this
     *
     */
    public function setDefaults($name, $default)
    {
        if (is_array($name)) {
            foreach ($name as $item) {
                $this->defaults[$item] = $default;
            }
        } else if (is_string($name)) {
            $this->defaults[$name] = $default;
        }

        return $this;
    }

    /**
     * Return all fields name
     *
     * @return array
     *
     */
    public function getFieldsName()
    {
        return $this->fieldsName;
    }

    /**
     * Get values of fields name from the method
     *
     * @return array
     *
     */
    public function getValues()
    {
        return $this->fieldsValues;
    }

    /**
     * Get value of a specific field name from the method
     *
     * @param $name
     * @return mixed
     *
     */
    public function getValue($name)
    {
        return $this->fieldsValues[$name];
    }

    /**
     * Set field name xss option to allow some evil attributes and don't catch xss script
     *
     * @param string $name
     * @param array $removeEvilAttributes
     *
     * @param array $removeEvilHtmlTags
     * @return Form
     * @see https://packagist.org/packages/voku/anti-xss for more information
     */
    public function xssOption($name, $removeEvilAttributes = [], $removeEvilHtmlTags = [])
    {
        if ($this->useXss) {
            $evilAttr = [];
            $htmlTag = [];

            // For evil attributes
            if (is_string($removeEvilAttributes)) {
                $evilAttr[] = $removeEvilAttributes;
            } else if (is_array($removeEvilAttributes)) {
                $evilAttr = $removeEvilAttributes;
            }
            if (count($evilAttr) != 0) {
                $this->xssOptions['evil'][$name] = $removeEvilAttributes;
            }

            // For evil html tags
            if (is_string($removeEvilHtmlTags)) {
                $htmlTag[] = $removeEvilHtmlTags;
            } else if (is_array($removeEvilHtmlTags)) {
                $htmlTag = $removeEvilHtmlTags;
            }
            if (count($htmlTag) != 0) {
                $this->xssOptions['html'][$name] = $removeEvilHtmlTags;
            }
        }
        return $this;
    }

    /**
     * Get csrf token field to prevent csrf bug
     * <p>Return somthing like this: <b><input type="hidden" value="..." name="$inputName"></b></p>
     *
     * @param string $csfrName
     * @param string $inputName
     *
     * @uses https://packagist.org/packages/maer/csrf for prevent csrf
     *
     * @return string
     *
     */
    public function csrfToken($csfrName, $inputName = 'csrftoken')
    {
        if ($this->useCsrf) {
            $this->csrfFieldNames['csrfName'] = $csfrName;
            $this->csrfFieldNames['inputName'] = $inputName;
            if ($this->csrfRegenerate) {
                $this->csrfRegenerate = false;
                return "<input type='hidden' value='" . $this->csrf->regenerateToken($csfrName) . "' name='" . $inputName . "'>";
            } else {
                return "<input type='hidden' value='" . $this->csrf->getToken($csfrName) . "' name='" . $inputName . "'>";
            }
        }
        return '';
    }

    /**
     * Validate $name field with error message $msg with type of $type
     *
     * @param string $type
     * @param string $name
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     */
    public function validate($type, $name, $msg, $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);
            $types = explode('|', $type);
            foreach ($types as $type) {
                // Extract parameters
                $startBracketPos = mb_strpos($type, '[', 0);
                $endBracketPos = mb_strpos($type, ']', -1);
                $parameters = [];
                if ($startBracketPos) {
                    $parameters = mb_substr($type, $startBracketPos + 1, $endBracketPos - $startBracketPos - 1);
                    $parameters = array_map('trim', explode(',', $parameters));
                    $type = mb_substr($type, 0, $startBracketPos);
                }

                if (is_array($value)) {
                    $c = 0;
                    foreach ($value as $k => $v) {
                        if (strpos($type, '!') === 0) {
                            $result = $this->validateOne($v, $name, $type, $parameters, true);
                        } else {
                            $result = $this->validateOne($v, $name, $type, $parameters);
                        }
                        if ($result === false) {
                            $this->errors[] = $msg;
                        } else {
                            $c++;
                        }
                    }

                    if ($c != count($value) && is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                } else {
                    if (strpos($type, '!') === 0) {
                        $result = $this->validateOne($value, $name, $type, $parameters, true);
                    } else {
                        $result = $this->validateOne($value, $name, $type, $parameters);
                    }
                    if ($result === false) {
                        if (is_callable($callback)) {
                            call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                        }
                        $this->errors[] = $msg;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Validate a value
     *
     * @param $value
     * @param string $name
     * @param string $type
     * @param array $parameters
     * @param bool $reverse
     * @return bool
     */
    protected function validateOne($value, $name, $type, $parameters = [], $reverse = false)
    {
        try {
            // Remove ! mark
            if ($reverse) {
                $type = mb_substr($type, 1);
            }

            $this->_validationHasError($value, $name, $type);

            // Call $type function
            $result = call_user_func_array($this->types[$type], array_merge([$value], $parameters));

            // Return reverse answer
            if ($reverse) {
                return !$result;
            }
            // Return true answer
            return $result;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Create your own field validation with passing validation as a function to this method
     *
     * @param string $name
     * @param $callback
     * @return Form
     *
     */
    public function customValidate($name, $callback)
    {
        if ($this->continueSubmit) {
            try {
                $value = $this->fieldsValues[$name];
                $this->_validationHasError($value, $name);

                call_user_func_array($callback, [&$this->fieldsValues[$name]]);
            } catch (HFException $e) {
                die($e->getMessage());
            }
        }

        return $this;
    }

    /**
     * Check if a email is valid or not
     *
     * @param string $name
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     *
     */
    public function validateEmail($name, $msg = 'ایمیل نامعتبر است.', $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->validateEmailOne($value, $name);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->validateEmailOne($value, $name);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate an email value
     *
     * @param $value
     * @param string $name
     * @return bool
     *
     */
    protected function validateEmailOne($value, $name)
    {
        try {
            $this->_validationHasError($value, $name);
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return true;
            }
            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if a username is valid or not
     *
     * @param string $name
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     *
     */
    public function validateUsername($name, $msg = 'نام کاربری نامعتبر است.', $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->validateUsernameOne($value, $name);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->validateUsernameOne($value, $name);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate an username value
     *
     * @param $value
     * @param string $name
     * @return bool
     *
     */
    protected function validateUsernameOne($value, $name)
    {
        try {
            $this->_validationHasError($value, $name);
            if (ctype_alnum($value) && !is_numeric($value)) {
                return true;
            }
            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if a password is valid or not
     *
     * @param string $name
     * @param int $strength | 1 => lowercase, 2 => lowercase, number, 3 => lowercase, number, uppercase, 4 => lowercase, number, uppercase, special chars
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     */
    public function validatePassword($name, $strength = 2, $msg = 'رمز عبور نامعتبر است.', $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->validatePasswordOne($value, $name, $strength);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->validatePasswordOne($value, $name, $strength);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate an password value
     *
     * @param $value
     * @param string $name
     * @param int $strength | 1 => lowercase, 2 => lowercase, number, 3 => lowercase, number, uppercase, 4 => lowercase, number, uppercase, special chars
     * @return bool
     *
     */
    protected function validatePasswordOne($value, $name, $strength)
    {
        try {
            $this->_validationHasError($value, $name);

            // Validate password strength
            $uppercase = preg_match('@[A-Z]@', $value);
            $lowercase = preg_match('@[a-z]@', $value);
            $number = preg_match('@[0-9]@', $value);
            $specialChars = preg_match('@[^\w]@', $value);

            if (1 == $strength && $lowercase) {
                return true;
            } else if (2 == $strength && $lowercase && $number) {
                return true;
            } else if (3 == $strength && $lowercase && $number && $uppercase) {
                return true;
            } else if (4 == $strength && $lowercase && $number && $uppercase && $specialChars) {
                return true;
            }
            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if phone/mobile is valid as length and is numeric or not
     *
     * @param string $name
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     *
     */
    public function validatePersianMobile($name, $msg = 'شماره موبایل نامعتبر است.', $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->validatePersianMobileOne($value, $name);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->validatePersianMobileOne($value, $name);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate a phone number
     *
     * @param $value
     * @param string $name
     * @return bool
     *
     */
    protected function validatePersianMobileOne($value, $name)
    {
        try {
            $this->_validationHasError($value, $name);

            if (preg_match("/^09[0-9]{9}$/", $value)) {
                return true;
            }

            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Validate Persian national code with a simple algorithm
     *
     * @param string $name
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     *
     */
    public function validateNationalCode($name, $msg = 'کد ملی نامعتبر است.', $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->validateNationalCodeOne($value, $name);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->validateNationalCodeOne($value, $name);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate a Persian national code
     *
     * @param $value
     * @param string $name
     * @return bool
     *
     */
    protected function validateNationalCodeOne($value, $name)
    {
        try {
            $this->_validationHasError($value, $name);

            if (mb_strlen($value) > 10 || !is_numeric($value) || !$this->_customCheckNationalCode($value)) {
                return false;
            }
            return true;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Validate date with a builtIn DateTime class
     *
     * @param string $name
     * @param $date - date string to validate in english format
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param string $format - Default is 'Y-m-d H:i:s'
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     */
    public function validateDate($name, $date, $msg = 'تاریخ نامعتبر است.', $format = 'Y-m-d H:i:s', $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->validateDateOne($value, $name, $date, $format);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->validateDateOne($value, $name, $date, $format);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate a Date
     *
     * @param $value
     * @param string $name
     * @param $date
     * @param $format
     *
     * @see https://www.php.net/manual/en/function.checkdate.php#113205
     *
     * @return bool
     */
    protected function validateDateOne($value, $name, $date, $format)
    {
        try {
            $this->_validationHasError($value, $name);

            $d = \DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if field $name is between $min and $max with error message $msg
     *
     * @param string $name
     * @param int $min
     * @param int $max
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     *
     */
    public function isInRange($name, $min = PHP_INT_MIN, $max = PHP_INT_MAX, $msg = 'خارج از محدوده مورد نظر است.', $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->isInRangeOne($value, $name, $min, $max);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->isInRangeOne($value, $name, $min, $max);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate a range
     *
     * @param $value
     * @param string $name
     * @param int $min
     * @param int $max
     * @return bool
     *
     */
    protected function isInRangeOne($value, $name, $min, $max)
    {
        try {
            $this->_validationHasError($value, $name);

            if ($value >= $min && $value <= $max) {
                return true;
            }
            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if field $name is in array $values with error message $msg
     *
     * @param string $name
     * @param array $values
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     *
     */
    public function isIn($name, array $values, $msg, $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->isInOne($value, $name, $values);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->isInOne($value, $name, $values);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate a in array
     *
     * @param $value
     * @param string $name
     * @param array $values
     * @return bool
     *
     */
    protected function isInOne($value, $name, array $values)
    {
        try {
            $this->_validationHasError($value, $name);

            if (in_array($value, $values)) {
                return true;
            }
            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if field $name is <b>not</b> in array $values with error message $msg
     *
     * @param string $name
     * @param array $values
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     *
     */
    public function isNotIn($name, array $values, $msg, $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->isNotInOne($value, $name, $values);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->isNotInOne($value, $name, $values);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate a not in array
     *
     * @param $value
     * @param string $name
     * @param array $values
     * @return bool
     *
     */
    protected function isNotInOne($value, $name, array $values)
    {
        try {
            $this->_validationHasError($value, $name);

            if (!in_array($value, $values)) {
                return true;
            }
            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if field $name is <b>not</b> empty with error message $msg
     *
     * @param array|string $name
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     *
     */
    public function isRequired($name, $msg = 'فیلد نباید خالی باشد.', $callback = null)
    {
        if ($this->continueSubmit) {
            if (is_array($name)) {
                $c1 = 0;
                $c2 = 0;
                foreach ($name as $item) {
                    $value = convertNumbersToPersian($this->fieldsValues[$item], true);
                    if (is_array($value)) {
                        $c2 += count($value);
                        foreach ($value as $k => $v) {
                            $result = $this->isRequiredOne($value, $item);
                            if ($result === false) {
                                $this->errors[] = $msg;
                            } else {
                                $c1++;
                            }
                        }
                    } else {
                        $c1++;
                        $c2++;
                        $result = $this->isRequiredOne($value, $item);
                        if ($result === false) {
                            if (is_callable($callback)) {
                                call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                            }
                            $this->errors[] = $msg;
                        }
                    }
                }
                if ($c1 != $c2 && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues]);
                }
            } else {
                $value = convertNumbersToPersian($this->fieldsValues[$name], true);

                if (is_array($value)) {
                    $c = 0;
                    foreach ($value as $k => $v) {
                        $result = $this->isRequiredOne($value, $name);
                        if ($result === false) {
                            $this->errors[] = $msg;
                        } else {
                            $c++;
                        }
                    }

                    if ($c != count($value) && is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                } else {
                    $result = $this->isRequiredOne($value, $name);
                    if ($result === false) {
                        if (is_callable($callback)) {
                            call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                        }
                        $this->errors[] = $msg;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Validate a required field
     *
     * @param $value
     * @param string $name
     * @return bool
     *
     */
    protected function isRequiredOne($value, $name)
    {
        try {
            $this->_validationHasError($value, $name);

            $method = mb_strtoupper($this->mappedNamesToMethod[$name]);
            if ($method == 'FILE' || $method == 'FILES') {
                if ($value['tmp_name'] != '') {
                    return true;
                }
            } else {
                if ($value != '') {
                    return true;
                }
            }
            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if field $name <b>is</b> empty with error message $msg
     *
     * @param array|string $name
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     *
     */
    public function isNotRequired($name, $msg = 'فیلد باید خالی گذاشته شود.', $callback = null)
    {
        if ($this->continueSubmit) {
            if (is_array($name)) {
                $c1 = 0;
                $c2 = 0;
                foreach ($name as $item) {
                    $value = convertNumbersToPersian($this->fieldsValues[$item], true);
                    if (is_array($value)) {
                        $c2 += count($value);
                        foreach ($value as $k => $v) {
                            $result = $this->isNotRequiredOne($value, $name);
                            if ($result === false) {
                                $this->errors[] = $msg;
                            } else {
                                $c1++;
                            }
                        }
                    } else {
                        $c1++;
                        $c2++;
                        $result = $this->isNotRequiredOne($value, $name);
                        if ($result === false) {
                            if (is_callable($callback)) {
                                call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                            }
                            $this->errors[] = $msg;
                        }
                    }
                }
                if ($c1 != $c2 && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues]);
                }
            } else {
                $value = convertNumbersToPersian($this->fieldsValues[$name], true);

                if (is_array($value)) {
                    $c = 0;
                    foreach ($value as $k => $v) {
                        $result = $this->isNotRequiredOne($value, $name);
                        if ($result === false) {
                            $this->errors[] = $msg;
                        } else {
                            $c++;
                        }
                    }

                    if ($c != count($value) && is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                } else {
                    $result = $this->isNotRequiredOne($value, $name);
                    if ($result === false) {
                        if (is_callable($callback)) {
                            call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                        }
                        $this->errors[] = $msg;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Validate a not required field
     *
     * @param $value
     * @param string $name
     * @return bool
     *
     */
    protected function isNotRequiredOne($value, $name)
    {
        try {
            $this->_validationHasError($value, $name);

            $method = mb_strtoupper($this->mappedNamesToMethod[$name]);
            if ($method == 'FILE' || $method == 'FILES') {
                if ($value['tmp_name'] == '') {
                    return true;
                }
            } else {
                if ($value == '') {
                    return true;
                }
            }
            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if field $name is equals to $length
     *
     * @param string $name
     * @param int $length
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     */
    public function isLengthEquals($name, $length, $msg, $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->isLengthEqualsOne($value, $name, $length);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->isLengthEqualsOne($value, $name, $length);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate a length equals
     *
     * @param $value
     * @param string $name
     * @param int $length
     * @return bool
     *
     */
    protected function isLengthEqualsOne($value, $name, $length)
    {
        try {
            $this->_validationHasError($value, $name);

            if (mb_strlen($value) == $length) {
                return true;
            }
            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if field $name length is between $minLength and $maxLength
     *
     * @param string $name
     * @param $minLength
     * @param $maxLength
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     */
    public function isLengthInRange($name, $minLength = PHP_INT_MIN, $maxLength = PHP_INT_MAX, $msg = '', $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->isLengthInRangeOne($value, $name, $minLength, $maxLength);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->isLengthInRangeOne($value, $name, $minLength, $maxLength);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate a length in range
     *
     * @param $value
     * @param string $name
     * @param int $minLength
     * @param int $maxLength
     * @return bool
     */
    protected function isLengthInRangeOne($value, $name, $minLength, $maxLength)
    {
        try {
            $this->_validationHasError($value, $name);

            if (mb_strlen($value) >= $minLength && mb_strlen($value) <= $maxLength) {
                return true;
            }
            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if field $name is <b>not</b> equals to $length
     *
     * @param string $name
     * @param int $length
     * @param string $msg - to not show the error put empty string in this and be sure remove empty messages is set to true
     * @param callable|null $callback - use for doing something if validate is failed
     * @return Form
     */
    public function isLengthNotEquals($name, $length, $msg, $callback = null)
    {
        if ($this->continueSubmit) {
            $value = convertNumbersToPersian($this->fieldsValues[$name], true);

            if (is_array($value)) {
                $c = 0;
                foreach ($value as $k => $v) {
                    $result = $this->isLengthNotEqualsOne($value, $name, $length);
                    if ($result === false) {
                        $this->errors[] = $msg;
                    } else {
                        $c++;
                    }
                }

                if ($c != count($value) && is_callable($callback)) {
                    call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                }
            } else {
                $result = $this->isLengthNotEqualsOne($value, $name, $length);
                if ($result === false) {
                    if (is_callable($callback)) {
                        call_user_func_array($callback, [&$this->fieldsValues[$name]]);
                    }
                    $this->errors[] = $msg;
                }
            }
        }

        return $this;
    }

    /**
     * Validate a length not equals
     *
     * @param $value
     * @param string $name
     * @param int $length
     * @return bool
     *
     */
    protected function isLengthNotEqualsOne($value, $name, $length)
    {
        try {
            $this->_validationHasError($value, $name);

            if (mb_strlen($value) != $length) {
                return true;
            }
            return false;
        } catch (HFException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Check if a checkbox is checked or not
     *
     * @param string $name
     * @param $checkedValue
     * @return bool
     */
    public function isChecked($name, $checkedValue = 'on')
    {
        $value = convertNumbersToPersian($this->fieldsValues[$name], true);
        return $this->_is_checked($value, $checkedValue);
    }

    /**
     * Set error to form error(s) array
     *
     * @param string $msg
     * @return Form
     *
     */
    public function setError($msg)
    {
        $this->errors[] = $msg;
        return $this;
    }

    /**
     * Get all (distinct) errors of form
     *
     * @return array
     *
     */
    public function getError()
    {
        if ($this->distinctErrors) {
            $this->errors = array_unique($this->errors);
        }
        if ($this->removeEmptyErrorMsg) {
            $this->errors = array_filter($this->errors, function ($val) {
                return $val !== '';
            });
        }
        return $this->errors;
    }

    /**
     * Distinct errors messages or not
     *
     * @param bool $bool
     * @return Form
     */
    public function distinctErrors($bool = true)
    {
        $this->distinctErrors = $bool;
        return $this;
    }

    /**
     * Remove empty error message(s) or not
     *
     * @param bool $bool
     * @return Form
     *
     */
    public function removeEmptyMessages($bool = true)
    {
        $this->removeEmptyErrorMsg = $bool;
        return $this;
    }

    /**
     * Check that if form is submitted or not
     *
     * Handle form with returns
     * Return 0 => Form variables not set, 1 => Succeed and 2 => Form token error
     *
     * @return Form
     *
     */
    public function checkForm()
    {
        $counter = 0;
        foreach ($this->checkableFieldsName as $name => $method) {
            $value = $this->fieldsValues[$name];
            if (isset($value) && !is_null($value)) {
                $counter++;
            }
        }

        if ($counter == count($this->checkableFieldsName)) {
            $this->continueSubmit = true;

            if ($this->useCsrf) {
                try {
                    $csrfToken = $this->_getFieldValue($this->csrfFieldNames['inputName'], $this->method, false);
                } catch (HFException $e) {
                    die($e->getMessage());
                }
                if ($this->csrf->validateToken($csrfToken, $this->csrfFieldNames['csrfName'])) {
                    if (isset($this->beforeFunction)) {
                        call_user_func_array($this->beforeFunction, [&$this->fieldsValues]);
                    }

                    $this->formStatus = 1;
//                return 1; // If it succeed
                } else {
                    $this->csrfRegenerate = true;
                    $this->csrfToken($this->csrfFieldNames['csrfName'], $this->csrfFieldNames['inputName']);
                    $this->errors[] = 'فرم نامعتبر است. لطفا صفحه را مجددا بارگذاری کنید.';

                    $this->formStatus = 2;
//                return 2; // If has error
                }
            } else {
                if (isset($this->beforeFunction)) {
                    call_user_func_array($this->beforeFunction, [&$this->fieldsValues]);
                }

                $this->formStatus = 1;
            }
            return $this;
        }

        $this->formStatus = 0;
//        return 0; // If variable(s) not set
        return $this;
    }

    /**
     * If there is error or not
     * Return true if there is no error and false otherwise
     * Translate of returns: 1 => succeed, 0 => have error
     *
     * @return int
     *
     */
    public function isSuccess()
    {
        $result = count($this->errors) == 0;

        if ($result && $this->formStatus != 0) {
            if (isset($this->afterFunction)) {
                call_user_func_array($this->afterFunction, [&$this->fieldsValues]);
            }

            $result = count($this->errors) == 0;
            if ($result) {
                if ($this->clearVariables) {
                    $this->_clearFieldsValues();
                }
                return 1; // If it succeed
            }
        }

        return 0; // If has error
    }

    /**
     * Check if all variables set and that means the form is submitted.
     * Use this method after <b>checkForm</b> method
     *
     * @see checkForm
     * @return bool
     *
     */
    public function isSubmit()
    {
        // If variable(s) is(are) set
        if (($this->formStatus != 0) && $this->continueSubmit) {
            return true; // If variable(s) not set
        }
        return false;
    }

    /**
     * Set to clear values of fields after all operations done or not
     *
     * @param bool $bool
     * @return Form
     *
     */
    public function clearVariablesOnSuccess($bool = true)
    {
        $this->clearVariables = $bool;
        return $this;
    }

    /**
     * Callback after the form has no error
     *
     * @param $callback
     * @return Form
     *
     * @throws HFException
     */
    public function afterCheckCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new HFException('ورودی ' . 'afterCheckCallback' . ' باید از نوع تابع باشد.');
        }
        $this->afterFunction = $callback;
        return $this;
    }

    /**
     * Callback before check that if form has error
     *
     * @param $callback
     * @return Form
     *
     * @throws HFException
     */
    public function beforeCheckCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new HFException('ورودی ' . 'beforeCheckCallback' . ' باید از نوع تابع باشد.');
        }
        $this->beforeFunction = $callback;
        return $this;
    }

    /**
     * @return Form
     */
    public function debug()
    {
        foreach ($this->fieldsValues as $k => $v) {
            echo "<pre style='direction: ltr; text-align: left;'>";
            echo $k . ' | ';
            var_dump($v);
            echo "</pre>";
            echo "<br>";
        }
        return $this;
    }

    /**
     * Check if a checkbox/radio is checked or not
     *
     * @param string $value
     * @param $checkedValues
     * @return bool
     */
    protected function _is_checked($value, $checkedValues)
    {
        return $value == $checkedValues;
    }

    /**
     * Store fields values to an associative array
     */
    protected function _getFieldsValue()
    {
        foreach ($this->mappedNamesToMethod as $fieldName => $method) {
            try {
                $this->fieldsValues[$fieldName] = $this->_getFieldValue($fieldName, $method);
            } catch (HFException $e) {
                die($e->getMessage());
            }
        }
    }

    /**
     * Return the values of a specific method like get/post/file/files
     *
     * @param string $name
     * @param string $method
     * @param bool $xss
     * @return int|string|object|array|null
     *
     * @throws HFException
     */
    protected function _getFieldValue($name, $method, $xss = true)
    {
        $method = strtoupper($method);
        $value = null;
        switch ($method) {
            case 'GET':
                $value = isset($_GET[$name]) && !is_null($_GET[$name]) ? $_GET[$name] : null;
                break;
            case 'POST':
                $value = isset($_POST[$name]) && !is_null($_POST[$name]) ? $_POST[$name] : null;
                break;
            case 'REQUEST':
                $value = isset($_REQUEST[$name]) && !is_null($_REQUEST[$name]) ? $_REQUEST[$name] : null;
                break;
            case 'FILES':
            case 'FILE':
                $value = isset($_FILES[$name]) && !is_null($_FILES[$name]) ? $_FILES[$name] : null;
                break;
            default:
                throw new HFException('متد ' . $method . ' که برای فیلد با نام: ' . $name . ' قرار داده شده، نامعتبر است.');
                break;
        }

        // Check $value xss script(s) and remove it(them)
        if ($this->useXss && $xss && isset($value)) {
            $evil = false;
            $evilTag = false;

            // Check for evil attributes if we don't have evil tags
            if ((isset($this->xssOptions['evil']) && array_key_exists($name, $this->xssOptions['evil'])) &&
                (isset($this->xssOptions['html']) && !array_key_exists($name, $this->xssOptions['html']))) {
                $evil = true;
                $this->xss->removeEvilAttributes($this->xssOptions['evil'][$name]);
                $value = $this->xss->xss_clean($value);

            }
            // Check for evil tags if we don't have evil attributes
            if ((isset($this->xssOptions['evil']) && !array_key_exists($name, $this->xssOptions['evil'])) &&
                (isset($this->xssOptions['html']) && array_key_exists($name, $this->xssOptions['html']))) {
                $evilTag = true;
                $this->xss->removeEvilHtmlTags($this->xssOptions['html'][$name]);
                $value = $this->xss->xss_clean($value);
            }
            // Check for evil attributes and evil tags if we have both
            if ((isset($this->xssOptions['evil']) && array_key_exists($name, $this->xssOptions['evil'])) &&
                (isset($this->xssOptions['html']) && array_key_exists($name, $this->xssOptions['html']))) {
                $evil = true;
                $evilTag = true;
                $this->xss->removeEvilAttributes($this->xssOptions['evil'][$name]);
                $this->xss->removeEvilHtmlTags($this->xssOptions['html'][$name]);
                $value = $this->xss->xss_clean($value);
            }

            // Add removed evil attributes and tags after xss clean
            if ($evil) {
                $this->xss->addEvilAttributes($this->xssOptions['evil'][$name]);
            }
            if ($evilTag) {
                $this->xss->addEvilHtmlTags($this->xssOptions['html'][$name]);
            }

            // Otherwise do simple xss_clean
            if (!$evil && !$evilTag) {
                $value = $this->xss->xss_clean($value);
            }
        }

        if (is_array($value) && in_array($name, $this->skippedValuesName)) {
            $value = array_filter($value, function ($val) {
                return $val !== '';
            });
        }

        if (empty($value) && in_array($name, array_keys($this->defaults))) {
            $value = $this->defaults[$name];
        }

        return $value;
    }

    /**
     * Map all fields to associated array like: ['fieldName' => 'method'] e.g. ['name' => 'post']
     *
     * @param array $exceptions
     * @param array $mustNotCheck
     */
    protected function _mapFieldsToMethod($exceptions = [], $mustNotCheck = [])
    {
        foreach ($this->fieldsName as $fieldName) {
            $this->mappedNamesToMethod[$fieldName] = $this->method;
            if (!in_array($fieldName, $mustNotCheck)) {
                $this->checkableFieldsName[$fieldName] = $this->method;
            }
        }
        $this->mappedNamesToMethod = array_merge($this->mappedNamesToMethod, $exceptions);
    }

    /**
     * Clear fields values (replace value with empty string/array)
     */
    protected function _clearFieldsValues()
    {
        $newArr = [];
        foreach ($this->fieldsValues as $name => $value) {
            if (is_array($this->fieldsValues[$name])) {
                if (isset($this->defaults[$name])) {
                    $newArr[$name] = $this->defaults[$name];
                } else {
                    $newArr[$name] = [];
                }
            } else {
                if (isset($this->defaults[$name])) {
                    $newArr[$name] = $this->defaults[$name];
                } else {
                    $newArr[$name] = '';
                }
            }
        }
        $this->fieldsValues = $newArr;
    }

    /**
     * Check error of validation
     * It's just for validate/customValidate function(s)
     *
     * @param $value
     * @param string $name
     * @param $type
     * @throws HFException
     *
     */
    protected function _validationHasError($value, $name, $type = null)
    {
        if (!isset($value)) {
            throw new HFException('فیلد(های) ' . $name . ' مقداردهی نشده است!');
        }
        if (!isset($this->method)) {
            throw new HFException('متد فرم مشخص نشده است!');
        }
        if (!is_null($type) && !key_exists($type, $this->types)) {
            throw new HFException('نوع درخواستی ' . $type . ' وجود ندارد!');
        }
    }

    /**
     * Check persian national code $code
     *
     * @param string|int $code
     * @return bool
     *
     */
    protected function _customCheckNationalCode($code)
    {
        if (!preg_match('/^[0-9]{10}$/', $code))
            return false;
        for ($i = 0; $i < 10; $i++)
            if (preg_match('/^' . $i . '{10}$/', $code))
                return false;
        for ($i = 0, $sum = 0; $i < 9; $i++)
            $sum += ((10 - $i) * intval(substr($code, $i, 1)));
        $ret = $sum % 11;
        $parity = intval(substr($code, 9, 1));
        if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity))
            return true;
        return false;
    }
}

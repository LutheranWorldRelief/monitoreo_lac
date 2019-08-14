<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\helpers\StringHelper;

class UPasswords extends Component {

    const ITERATIONS = 30000;

    /**
     * Verifies a password against a hash.
     * @param string $password The password to verify.
     * @param string $hash The hash to verify the password against.
     * @return boolean whether the password is correct.
     * @throws InvalidParamException on bad password/hash parameters or if crypt() with Blowfish hash is not available.
     * @see generatePasswordHash()
     */
    public static function validatePassword($password, $hash) {
        if (!is_string($password) || $password === '')
            throw new InvalidParamException(Yii::t('app', 'Password must be a string and cannot be empty.'));

//        if (!preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $hash, $matches) || $matches[1] < 4 || $matches[1] > 30)
//            throw new InvalidParamException('Hash is invalid.');

        $pieces = explode('$', $hash);
        $iterations = $pieces[1];
        $salt = $pieces[2];
        $hash = $pieces[3];
        $hashRecibida = self::encriptar($password, $salt, $iterations);
        return self::compareString($hashRecibida, $hash);
    }

    public static function encriptar($password, $salt, $iterations = self::ITERATIONS, $lenght = 32, $raw_output = true) {
        return base64_encode(hash_pbkdf2("sha256", $password, $salt, $iterations, $lenght, $raw_output));
    }

    /**
     * Generates a salt that can be used to generate a password hash.
     *
     * The PHP [crypt()](http://php.net/manual/en/function.crypt.php) built-in function
     * requires, for the Blowfish hash algorithm, a salt string in a specific format:
     * "$2a$", "$2x$" or "$2y$", a two digit cost parameter, "$", and 22 characters
     * from the alphabet "./0-9A-Za-z".
     *
     * @param integer $cost the cost parameter
     * @return string the random salt value.
     * @throws InvalidParamException if the cost parameter is out of the range of 4 to 31.
     */
    public static function generateSalt($length = 8, $cost = 12) {
        $cost = (int) $cost;
        if ($cost < 4 || $cost > 31) {
            throw new InvalidParamException(Yii::t('app', 'Cost must be between 4 and 31.'));
        }

        // Get a 20-byte random string
        $rand = Yii::$app->security->generateRandomKey(20);
        // Form the prefix that specifies Blowfish (bcrypt) algorithm and cost parameter.
        $salt = sprintf("2y%02d", $cost);
        // Append the random salt data in the required base64 format.
        $salt .= str_replace('+', '.', substr(base64_encode($rand), 1, $length));

        return $salt;
    }

    /**
     * Performs string comparison using timing attack resistant approach.
     * @see http://codereview.stackexchange.com/questions/13512
     * @param string $expected string to compare.
     * @param string $actual user-supplied string.
     * @return boolean whether strings are equal.
     */
    public static function compareString($expected, $actual) {
        $expected .= "\0";
        $actual .= "\0";
        $expectedLength = StringHelper::byteLength($expected);
        $actualLength = StringHelper::byteLength($actual);
        $diff = $expectedLength - $actualLength;
        for ($i = 0; $i < $actualLength; $i++)
            $diff |= (ord($actual[$i]) ^ ord($expected[$i % $expectedLength]));
        return $diff === 0;
    }

    public static function passwordString($hash, $salt, $iterations = self::ITERATIONS) {
        return 'pbkdf2_sha256$' . (string) $iterations . '$' . $salt . '$' . $hash;
    }

}

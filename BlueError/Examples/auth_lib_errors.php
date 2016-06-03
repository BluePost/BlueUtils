<?php

//This file defines a set of errors that could be returned by an authentication API
//All error messages are defined as top level fields for ease of use
//A better one would be to have other classes, ie a TokenError class to spearate them further

global $authInstance;
$authInstance = null;

class AuthError {

    public $AUTH;
        public $TOKEN;
            public $TOKEN_INVALID;
            public $TOKEN_NOT_EXIST;
            public $TOKEN_NOTSET;
            public $TOKEN_OUTDATED;
            public $TOKEN_WRONG_IP;
            public $TOKEN_INSERT_FAIL;

            public $INSTALL_TOKEN;
                public $INSTALL_TOKEN_NOTSET;
                public $INSTALL_TOKEN_NOT_EXIST;
                public $INSTALL_TOKEN_NOT_VALID;
                public $INSTALL_TOKEN_INSERT_ERROR;

        public $AUTH_NOTSET;


    public $LOGIN;
        public $LOGIN_NOTSET;
        public $LOGIN_TOO_FREQ;
        public $LOGIN_INCORRECT;
            public $LOGIN_INCORRECT_PASSWORD;
        public $LOGIN_SUSPENDED;

    public $SIGNUP;
        public $SIGNUP_NOTSET;
        public $SIGNUP_INVALID;
        public $SIGNUP_PASSWORD;
            public $SIGNUP_PASSWORD_TOOSHORT;
        public $SIGNUP_ALREADY_TAKEN;

    public function __construct()
    {
        $this->AUTH = new BluePost\ErrorScheme("AU", "An auth error occurred");
        {
            $this->TOKEN = new \BluePost\ErrorScheme("TO", "A token error occurred", $this->AUTH);
            {
                $this->TOKEN_INVALID = new \BluePost\ErrorScheme("INVL", "The passed token is invalid", $this->TOKEN);
                $this->TOKEN_NOTSET = new \BluePost\ErrorScheme("NS", "An element of the token was not set", $this->TOKEN);
                $this->TOKEN_NOT_EXIST = new \BluePost\ErrorScheme("NE", "Token does not exist", $this->TOKEN);
                $this->TOKEN_OUTDATED = new \BluePost\ErrorScheme("OUTD", "Token is oudated", $this->TOKEN);
                $this->TOKEN_WRONG_IP = new \BluePost\ErrorScheme("WIP", "Token is for the wrong IP", $this->TOKEN);
                $this->TOKEN_INSERT_FAIL = new \BluePost\ErrorScheme("INSF", "Token could not be inserted into the database", $this->TOKEN);

                $this->INSTALL_TOKEN = new \BluePost\ErrorScheme("IN", "There was an error with the install token", $this->TOKEN);
                {
                    $this->INSTALL_TOKEN_NOTSET = new \BluePost\ErrorScheme("NS", "The install token was not set when required", $this->INSTALL_TOKEN);
                    $this->INSTALL_TOKEN_NOT_EXIST = new \BluePost\ErrorScheme("NE", "Install token does not exist", $this->INSTALL_TOKEN);
                    $this->INSTALL_TOKEN_NOT_VALID = new \BluePost\ErrorScheme("NV", "Install token is not valid for the token and the date",$this->INSTALL_TOKEN);
                    $this->INSTALL_TOKEN_INSERT_ERROR = new \BluePost\ErrorScheme("INSF", "Install token could not be inserted into the database",$this->INSTALL_TOKEN);
                }
            }
            $this->AUTH_NOTSET = new \BluePost\ErrorScheme("NS", "Something was not set", $this->AUTH);
        }

                



        $this->LOGIN = new \BluePost\ErrorScheme("LI", "An auth error occurred");
        {
            $this->LOGIN_NOTSET = new \BluePost\ErrorScheme("NS", "Something was not set", $this->LOGIN);
            $this->LOGIN_TOO_FREQ = new \BluePost\ErrorScheme("TFREQ", "Too many login attempts recently, please try again later",$this->LOGIN);
            $this->LOGIN_INCORRECT = new \BluePost\ErrorScheme("INC", "Part of your information was incorrect",$this->LOGIN);
            {
                $this->LOGIN_INCORRECT_PASSWORD = new \BluePost\ErrorScheme("PW", "Incorrect password", $this->LOGIN_INCORRECT);
            }
            $this->LOGIN_SUSPENDED = new \BluePost\ErrorScheme("SUSP", "User is suspended",$this->LOGIN);
            
        }

        $this->SIGNUP = new \BluePost\ErrorScheme("SU", "A signup error occurred");
        {
            $this->SIGNUP_NOTSET = new \BluePost\ErrorScheme("NS", "Something was not set for signup", $this->SIGNUP);
            $this->SIGNUP_INVALID = new \BluePost\ErrorScheme("INV", "Signup info was invalid", $this->SIGNUP);
            $this->SIGNUP_PASSWORD = new \BluePost\ErrorScheme("PW", "An error occurred with the set password");
            {
                $this->SIGNUP_PASSWORD_TOOSHORT = new \BluePost\ErrorScheme("TS", "Password is too short", $this->SIGNUP_PASSWORD);
            }
            $this->SIGNUP_ALREADY_TAKEN = new \BluePost\ErrorScheme("AT", "Unique information is already taken");
            $this->SIGNUP_INSERT_ERROR = new \BluePost\ErrorScheme("INSF", "Failed to insert user into the database");
        }
    }

}

//This is the global access point of access for the AuthError class
//It has a caching mechanism so that the class is only instantiated
//when needed and once only.
function authError() {
    global $authInstance;
    if ($authInstance == null)
         $authInstance = new AuthError();
    return $authInstance;
}
?>
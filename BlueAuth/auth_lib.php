<?php
    //Authlibrary by Freddie Poser
    namespace BluePost;
    require_once "AuthLibErrors.php";

    class AuthLib {

        private $ERRORS;

        private $TOKEN_STRING = null;
        private $TOKEN_USERID = null;
        private $TOKEN_INSTALLHASH = null;
        private $TOKEN_INSTALLHASH_NONCE = null;

        private $CURRENT_IP = null;

        private $TOKEN_OBJECT = null;

        private $db;

        //60 Mins
        private $TOKEN_TIME_SHORT = 60 * 60;
        //30 days
        private $TOKEN_TIME_LONG  = 30 * 24 * 60 * 60;

        //TODO: Register app tokens

        function __construct(
            $DATABASE_CONN,
            $TOKEN,
            $TOKEN_USERID,
            $TOKEN_INSTALLHASH,
            $TOKEN_INSTALLHASH_NONCE,
            $CURRENT_IP
        )
        {
            global $AUTH_ERRORS;

            if (!isset($DATABASE_CONN) || $DATABASE_CONN == null) throw new \Error("Database not set!");
            $this->db = $DATABASE_CONN;
            $this->TOKEN_STRING = $TOKEN;
            $this->TOKEN_USERID = $TOKEN_USERID;
            $this->TOKEN_INSTALLHASH = $TOKEN_INSTALLHASH;
            $this->TOKEN_INSTALLHASH_NONCE = $TOKEN_INSTALLHASH_NONCE;
            $this->CURRENT_IP = $CURRENT_IP;
            $this->ERRORS = $AUTH_ERRORS;
        }

        function checkLogin() {

            if($this->TOKEN_STRING == null) return authError()->TOKEN_NOTSET->custom("STR", "Token string not set");
            if($this->TOKEN_USERID == null) return authError()->TOKEN_NOTSET->custom("UID", "Token userid not set");
            if ($this->TOKEN_INSTALLHASH != null) {
                if($this->TOKEN_INSTALLHASH_NONCE == null) return authError()->TOKEN_NOTSET->custom("NON", "The install token nonce was not set");
            }
            return $this->currentTokenValid();


        }

        function isLoggedIn() {
            $loggedInResult = $this->checkLogin();
            if (isset($loggedInResult["valid"]) && $loggedInResult["valid"]) return TRUE;
            return FALSE;
        }

        function currentTokenValid () {
            $token = $this->getCurrentToken();
            if (!$token) return authError()->TOKEN_NOTSET->build();

            if ($token["auth_token_apptoken"] == 1) {

                if ($this->TOKEN_INSTALLHASH == null) return authError()->INSTALL_TOKEN_NOTSET->build();
                $isValid = $this->installTokenValid($token["auth_token_app_install"], $this->TOKEN_INSTALLHASH, $this->TOKEN_INSTALLHASH_NONCE);
                if (!$isValid) return authError()->INSTALL_TOKEN_NOT_VALID->build();

            }

            return $this->tokenTimeValid($token);
        }

        function installTokenValid ($actualInstallToken, $hash, $nonce) {

            $expectedHash = hash("sha256",$actualInstallToken . $nonce);
            return $expectedHash == strtolower($hash);

        }

        function tokenTimeValid ($token) {

            if (
                $token["auth_token_apptoken"] == 0 &&
                (strtotime($token["auth_token_created"]) + $this->TOKEN_TIME_SHORT) < time()
            ) return authError()->TOKEN_OUTDATED->build();
            else if (
                (strtotime($token["auth_token_created"]) + $this->TOKEN_TIME_SHORT) < time()
            ) return authError()->TOKEN_OUTDATED->build();
            else if ($token["auth_token_ip"] != $this->CURRENT_IP) return authError()->TOKEN_WRONG_IP->build();
            return Array("valid" => true);

        }

        function getCurrentToken () {
            $token =  $this->getToken($this->TOKEN_STRING, $this->TOKEN_USERID);
            $this->TOKEN_OBJECT = $token;
            return $token;
        }

        function getToken ($token_string, $token_userid) {

            $this->db->where("auth_token_string", $token_string);
            $this->db->where("auth_token_userid", $token_userid);
            $token = $this->db->getOne("auth_tokens");

            return $token;

        }

        function registerInstall($userid) {

            $installString = randomstring(50);
            $data = Array (
                "auth_app_install_userid" => $userid,
                "auth_app_install_tokenstring" => $installString
            );
            $id = $this->db->insert("auth_app_installs", $data);
            if (!$id) return authError()->INSTALL_TOKEN_INSERT_ERROR->build();
            return Array("success" => true, "install_string" => $installString);

        }

        function loginUser ($username, $password, $isApp = FALSE) {

            //Check that all of the data is present
            if (!isset($username) || $username == "" || $username == null)
                return authError()->LOGIN_NOTSET->custom("UN", "Username was not set");
            if (!isset($password) || $password == "" || $password == null)
                return authError()->LOGIN_NOTSET->custom("PW", "Password was not set");

            //Get the user
            $this->db->where("auth_username", sanitizestring($username));
            $user = $this->db->getOne("auth_users");
            if ($this->db->count < 1) return authError()->LOGIN->custom("NOUSR", "No user with the username "  . $username . " exists");

            //TODO: Log if app or web
            //Get all login attempts in the last 5 mins
            $this->db->where("auth_login_attempt_userid", $user["auth_userid"]);
            $this->db->where("auth_login_attempt_successful", 0);
            $this->db->where("auth_login_attempt_time > NOW() - INTERVAL 2 MINUTE");
            $this->db->get("auth_login_attempts");

            //Check that there haven't been more than 5 login attempts in the last 5 mins
            if ($this->db->count > 4) {
                return authError()->LOGIN_TOO_FREQ->build();
            }

            //Hash the password
            $passwordToHash = $user["auth_password_salt_1"] . $password . $user["auth_password_salt_2"];
            $passwordHash = hash("sha512", $passwordToHash);

            //Check the password
            if ($passwordHash != $user["auth_password_hash"]) {
                $this->createLoginAttempt($user["auth_userid"], $this->CURRENT_IP);
                return authError()->LOGIN_INCORRECT_PASSWORD->build();
            }

            //Check if the user is suspended
            if ($user["auth_suspended"] == 1) {
                //Here the attempt was successful, but the user has been suspended
                $this->createLoginAttempt($user["auth_userid"], $this->CURRENT_IP, 1, 1);
                return authError()->LOGIN_SUSPENDED->build();
            }

            //TODO: Email verification
            //Login attempt has been successful so record that
            $this->createLoginAttempt($user["auth_userid"], $this->CURRENT_IP, 1);
            $token = $this->generateToken($user["auth_userid"], $isApp);

            $token["user"] = $user;
            return $token;

        }

        function generateToken ($userid, $isApp = TRUE, $invalidateAll = FALSE, $getOld = TRUE) {

            //Invalidate all out of date tokens for this user
            $this->invalidateTokens($userid, $this->CURRENT_IP, $invalidateAll);

            //TODO: Allow reuse of old app tokens?
            if ($isApp) $getOld = FALSE;

            if ($getOld) {
                $indateTokens = $this->getIndateTokens($userid, $this->CURRENT_IP);
                if (count($indateTokens) > 0) {
                    return Array("success" => true, "token_string" => $indateTokens[0]["auth_token_string"], "userid" => $indateTokens[0]["auth_token_userid"], "type" => "reused");
                }
            }

            //Generate a new token
            $tokenString = randomstring(100);
            $tokenToHash = $userid . $tokenString . time();
            $tokenHash = md5($tokenToHash);
            $tokenIsApp = 0;
            $tokenInstall = "";

            if ($isApp) {

                $tokenInstall = md5(randomstring(100));
                $tokenIsApp = 1;

            }

            //Create the token to insert
            $data = Array (
                "auth_token_string" => $tokenHash,
                "auth_token_userid" => $userid,
                "auth_token_created" => date('Y-m-d G:i:s'),
                "auth_token_ip" => $this->CURRENT_IP,
                "auth_token_apptoken" => $tokenIsApp,
                "auth_token_app_install" => $tokenInstall
            );
            $id = $this->db->insert("auth_tokens", $data);

            if ($id) {
                $success =  Array("success" => true, "token_string" => $tokenHash, "userid"=>$userid, "type" => "new");

                if ($isApp) {
                    $success["install_token"] = $tokenInstall;
                }

                return $success;
            }
            else {
                return authError()->TOKEN_INSERT_FAIL->build();
            }

        }

        function createLoginAttempt($userid, $IP, $status = 0, $blocked = 0) {
            $data = Array (
                "auth_login_attempt_ip" => $IP,
                "auth_login_attempt_successful" => $status,
                "auth_login_attempt_userid" => $userid,
                "auth_login_attempt_blocked" => $blocked
            );
            $id = $this->db->insert("auth_login_attempts", $data);
            return $id;
        }

        function getOutdatedTokens ($userid, $IP) {

            $validTokens = $this->getAllTokens($userid, $IP);

            if ($this->db->count > 0) {
                return array_filter(
                    $validTokens,
                    function ($token) {
                        if ($token["auth_token_apptoken"] == 0) return (strtotime($token["auth_token_created"]) + $this->TOKEN_TIME_SHORT) < time();
                        else return (strtotime($token["auth_token_created"]) + $this->TOKEN_TIME_LONG) < time();
                    }
                );
            }

            return Array();

        }

        function getIndateTokens ($userid, $IP) {

            $validTokens = $this->getAllTokens($userid, $IP);

            if ($this->db->count > 0) {
                return array_filter(
                    $validTokens,
                    function ($token) {
                        if ($token["auth_token_apptoken"] == 0) return (strtotime($token["auth_token_created"]) + $this->TOKEN_TIME_SHORT) > time();
                        else return (strtotime($token["auth_token_created"]) + $this->TOKEN_TIME_LONG) > time();
                    }
                );
            }

            return Array();

        }

        function getAllTokens ($userid, $IP) {
            $this->db->where("auth_token_valid", 1);
            $this->db->where("auth_token_ip", $IP);
            $this->db->where("auth_token_userid", $userid);
            return $this->db->get("auth_tokens");
        }

        function invalidateTokens($userid, $IP, $invalidateAll = FALSE)
        {
            if ($invalidateAll) {
                $tokensToInvalidate = $this->getAllTokens($userid, $IP);
            } else {
                $tokensToInvalidate = $this->getOutdatedTokens($userid, $IP);
            }
            //FIXME: Optimise this to one query!!!
            foreach ($tokensToInvalidate as $ot) {
                $this->db->where("auth_tokenid", $ot["auth_tokenid"]);
                $this->db->update("auth_tokens", Array("auth_token_valid" => 0));
            }
        }

        function user() {

            if (!$this->isLoggedIn()) return FALSE;

            //Get the token, cached or anew
            $token = ($this->TOKEN_OBJECT != null ? $this->TOKEN_OBJECT : $this->getCurrentToken());
            $this->db->where("auth_userid", $token["auth_token_userid"]);
            return $this->db->getOne("auth_users");

        }

    }
?>
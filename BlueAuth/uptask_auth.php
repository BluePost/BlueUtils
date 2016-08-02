<?php
    //TODO: Maybe make this a generic function for any data
    function signupUser (
        $username,
        $email,
        $password,
        $forename,
        $surname,
        $autoVerify = TRUE
    ) {

        global $GLOBALS;
        $db = $GLOBALS["DBLIB"];
        if (!isset($username) || $username == "" || $username == null)  return authError()->SIGNUP_NOTSET->custom("UN", "Username not set");
        if (!isset($email) || $email == "" || $email == null)           return authError()->SIGNUP_NOTSET->custom("EM", "Email not set");
        if (!isset($password) || $password == "" || $password == null)  return authError()->SIGNUP_NOTSET->custom("PW", "Password not set");
        if (!isset($forename) || $forename == "" || $forename == null)  return authError()->SIGNUP_NOTSET->custom("FN", "Forename not set");
        if (!isset($surname) || $surname == "" || $surname == null)     return authError()->SIGNUP_NOTSET->custom("SN", "Surname not set");

        if (strlen ($password) < 5)
            return authError()->SIGNUP_PASSWORD_TOOSHORT->build();

        //Hash the password
        $SALT_1 = randomstring(10);
        $SALT_2 = randomstring(10);
        $PASSWORD_HASHED = hash("sha512", $SALT_1 . $password . $SALT_2);

        //Check that the username is unique
        $db->where("auth_username", sanitizestring($username));
        $usrtmp = $db->getOne("auth_users");
        if ($db->count > 0) return authError()->SIGNUP_ALREADY_TAKEN->custom("UN", "Username already taken");

        //Check that the email is unique
        $db->where("auth_email", sanitizestring($email));
        $usrtmp = $db->getOne("auth_users");
        if ($db->count > 0) return authError()->SIGNUP_ALREADY_TAKEN->custom("EM", "Email already taken");

        //Collect the data about the user for insertion
        $USER = Array (
            "auth_username"         => sanitizestring($username),
            "auth_email"            => sanitizestring($email),
            "auth_email_verified"   => ($autoVerify ? 1 : 0),
            "auth_password_hash"    => $PASSWORD_HASHED,
            "auth_password_salt_1"  => $SALT_1,
            "auth_password_salt_2"  => $SALT_2,
            "auth_forename"         => sanitizestring($forename),
            "auth_surname"          => sanitizestring($surname)
        );

        $id = $db->insert("auth_users", $USER);

        if ($id) {
            return Array("success" => true);
        }
        else {
            return authError()->SIGNUP_INSERT_ERROR->build();
        }
    }
?>
<?php
class User extends DBObject {

    public $username;
    public $passwordMd5;
    public $name;
    public $email;
    public $active = false;
    public $lastLogin = false;

    function __construct($id) {
        $this -> ID = $id;

        $query = "select login, password, email, name, active, lastlogin from user where id = " . $id;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                $this -> username = $data["login"];
                $this -> passwordMd5 = $data["password"];
                $this -> name = $data["name"];
                $this -> email = $data["email"];
                $this -> active = ($data["active"] == 1);
                $this -> lastLogin = ($data["lastlogin"] == "") ? false : DateTime::createFromFormat(MYSQL_DATE_TIME_FORMAT, $data["lastlogin"]);
            }
        }
    }

    function getPermissions() {
        $list = array();

        $query = "select id from permission where userid = " . $this -> ID;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                array_push($list, new Permission($data["id"]));
            }
        }

        return $list;
    }

    static function getCurrentUser() {
        if (array_key_exists("authtoken", $_SESSION)) {
            $authToken = $_SESSION["authtoken"];
            $query = "select id, active from user where authtoken = '" . $authToken . "'";
            if ($result = mysql_query($query)) {
                while ($data = mysql_fetch_array($result)) {
                    if ($data["active"] == 1) {
                        return new User($data["id"]);
                    }
                }
            }
        }
        
        return false;
    }

    static function isLoggedIn() {
        if (array_key_exists("authtoken", $_SESSION)) {
            $authToken = $_SESSION["authtoken"];
            $query = "select id, active from user where authtoken = '" . $authToken . "'";
            if ($result = mysql_query($query)) {
                while ($data = mysql_fetch_array($result)) {
                    if ($data["active"] == 1) {
                        // auth token found - return true
                        $GLOBALS[CURRENTUSER] = new User($data["id"]);
                        return true;
                    }
                }
            }
        }
        // auth token not found -> return false
        return false;
    }
    
    static function login($username, $password) {
        $query = "select id, active from user where login = '" . $username . "' and password = '" . md5($password) . "'";
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                if ($data["active"] == 1) {
                    // generate auth token
                    $authToken = md5(date(time()) . '-' . $data["id"]);
                    // update user account with new authtoken
                    $update = "update user set authtoken = '" . $authToken . "' where id = " . $data["id"];
                    mysql_query($update);
                    // store auth token in session
                    $_SESSION["authtoken"] = $authToken;
                    
                    // return the logged in user
                    return new User($data["id"]);
                }
                else {
                    return "0";
                }
            }
        }
        return false;
    }
    
    static function logout() {
        unset($_SESSION["authtoken"]);
    }
    
    static function checkPermission($name, $minValue) {
        $user = User::getCurrentUser();
        if ($user === false) {
            return false;
        }
        
        $query = "select value from permission where userid = " . $user->ID . " and name = '" . $name . "'";
        
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                if ($data["value"] >= $minValue) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }
        
        return false;
    }

}

class Permission extends DBObject {

    public $name;
    public $value;

    function __construct($id) {
        $this -> ID = $id;

        $query = "select name, value from permission where id = " . $id;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                $this -> name = $data["name"];
                $this -> value = $data["value"];
            }
        }
    }

}
?>

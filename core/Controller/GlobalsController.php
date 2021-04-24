<?php

namespace Pam\Controller;

use Exception;

/**
 * Class GlobalsController
 * @package Pam\Controller
 */
abstract class GlobalsController
{
    /**
     * @var array
     */
    private $alert = [];

    /**
     * @var array
     */
    private $cookie = [];

    /**
     * @var array
     */
    private $env = [];

    /**
     * @var array
     */
    private $file = [];

    /**
     * @var array
     */
    private $files = [];

    /**
     * @var array
     */
    private $get = [];

    /**
     * @var array
     */
    private $post = [];

    /**
     * @var array
     */
    private $request = [];

    /**
     * @var array
     */
    private $server = [];

    /**
     * @var array
     */
    private $session = [];

    /**
     * @var array
     */
    private $user = [];

    /**
     * GlobalsController constructor
     */
    public function __construct()
    {
        $this->cookie   = filter_input_array(INPUT_COOKIE) ?? [];
        $this->env      = filter_input_array(INPUT_ENV) ?? [];
        $this->get      = filter_input_array(INPUT_GET) ?? [];
        $this->post     = filter_input_array(INPUT_POST) ?? [];
        $this->server   = filter_input_array(INPUT_SERVER) ?? [];

        $this->files    = filter_var_array($_FILES) ?? [];
        $this->request  = filter_var_array($_REQUEST) ?? [];

        if (isset($this->files["file"])) {
            $this->file = $this->files["file"];
        }

        if (array_key_exists("alert", $_SESSION) === false) {
            $_SESSION["alert"] = [];
        }

        $this->session  = filter_var_array($_SESSION) ?? [];
        $this->alert    = $this->session["alert"];

        if (isset($this->session["user"])) {
            $this->user = $this->session["user"];
        }
    }

    // ******************** COOKIE ******************** \\

    /**
     * @param string $var
     * @return bool
     */
    protected function checkCookie(string $var = null)
    {
        if (!empty($this->cookie)) {

            if ($var === null) {

                return true;
            }

            if (in_array($var, $this->cookie) && !empty($this->cookie[$var])) {

                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     * @param string $value
     * @param int $expire
     */
    protected function createCookie(string $name, string $value = "", int $expire = 0) {

        if ($expire === 0) {
            $expire = time() + 3600;
        }

        setcookie($name, $value, $expire, "/");
    }

    /**
     * @param string $name
     */
    protected function destroyCookie(string $name)
    {
        if ($this->cookie[$name] !== null) {

            $this->createCookie($name, "", time() - 3600);
        }
    }

    /**
     * @return array|mixed
     */
    protected function getCookie(string $var = null)
    {
        if ($var === null) {

            return $this->cookie;
        }
        
        return $this->cookie[$var] ?? "";
    }

    // ******************** ENV ******************** \\

    /**
     * @param string $var
     * @return bool
     */
    protected function checkEnv(string $var = null)
    {
        if (!empty($this->env)) {

            if ($var === null) {

                return true;
            }

            if (in_array($var, $this->env) && !empty($this->env[$var])) {

                return true;
            }
        }

        return false;
    }

    /**
     * @return array|mixed
     */
    protected function getEnv(string $var = null)
    {
        if ($var === null) {

            return $this->env;
        }
        
        return $this->env[$var] ?? "";
    }

    // ******************** FILES ******************** \\

    /**
     * @param string $var
     * @return bool
     */
    protected function checkFiles(string $var = null)
    {
        if (!empty($this->files)) {

            if ($var === null) {

                return true;
            }

            if (in_array($var, $this->files) && !empty($this->files[$var])) {

                return true;
            }
        }

        return false;
    }

    /**
     * @return array|mixed
     */
    protected function getFiles(string $var = null)
    {
        if ($var === null) {

            return $this->files;
        }
        
        return $this->file[$var] ?? "";
    }

    /**
     * @param string $fileDir
     * @param string|null $fileName
     * @return string
     */
    protected function setFileName(string $fileDir, string $fileName = null)
    {
        if ($fileName === null) {

            return $fileDir . $this->file["name"];
        }

        return $fileDir . $fileName . $this->setFileExtension();
    }

    /**
     * @return string
     */
    protected function setFileExtension()
    {
        try {
            switch ($this->file["type"]) {
                case "image/bmp":
                    return ".bmp";
                    break;

                case "image/gif":
                    return ".gif";
                    break;

                case "image/jpeg":
                    return ".jpg";
                    break;

                case "image/png":
                    return ".png";
                    break;

                case "image/webp":
                    return ".webp";
                    break;

                default:
                    throw new Exception(
                        "The File Type : " . $this->file["type"] . " is not accepted..."
                    );
            }

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * @param string $fileDir
     * @param string|null $fileName
     * @return mixed|string
     */
    protected function uploadFile(string $fileDir, string $fileName = null, int $fileSize = 50000000) {
        try {
            if (
                !isset($this->file["error"]) 
                || is_array($this->file["error"])
            ) {
                throw new Exception("Invalid parameters...");
            }

            if ($this->file["size"] > $fileSize) {
                throw new Exception("Exceeded filesize limit...");
            }

            if (
                !move_uploaded_file(
                    $this->file["tmp_name"], 
                    $this->setFileName($fileDir, $fileName)
                )
            ) {
                throw new Exception("Failed to move uploaded file...");
            }

            return $this->file["name"];

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    // ******************** GET ******************** \\

    /**
     * @param string $var
     * @return bool
     */
    protected function checkGet(string $var = null)
    {
        if (!empty($this->get)) {

            if ($var === null) {

                return true;
            }

            if (in_array($var, $this->get) && !empty($this->get[$var])) {

                return true;
            }
        }

        return false;
    }

    /**
     * @return array|mixed
     */
    protected function getGet(string $var = null)
    {
        if ($var === null) {

            return $this->get;
        }
        
        return $this->get[$var] ?? "";
    }

    // ******************** POST ******************** \\

    /**
     * @param string $var
     * @return bool
     */
    protected function checkPost(string $var = null)
    {
        if (!empty($this->post)) {

            if ($var === null) {

                return true;
            }

            if (in_array($var, $this->post) && !empty($this->post[$var])) {

                return true;
            }
        }

        return false;
    }

    /**
     * @param string $var
     * @return mixed
     */
    protected function getPost(string $var = null)
    {
        if ($var === null) {

            return $this->post;
        }

        return $this->post[$var] ?? "";
    }

    // ******************** REQUEST ******************** \\

    /**
     * @param string $var
     * @return bool
     */
    protected function checkRequest(string $var = null)
    {
        if (!empty($this->request)) {

            if ($var === null) {

                return true;
            }

            if (in_array($var, $this->request) && !empty($this->request[$var])) {

                return true;
            }
        }

        return false;
    }

    /**
     * @return array|mixed
     */
    protected function getRequest(string $var = null)
    {
        if ($var === null) {

            return $this->request;
        }
        
        return $this->request[$var] ?? "";
    }

    // ******************** SERVER ******************** \\

    /**
     * @param string $var
     * @return bool
     */
    protected function checkServer(string $var = null)
    {
        if (!empty($this->server)) {

            if ($var === null) {

                return true;
            }

            if (in_array($var, $this->server) && !empty($this->server[$var])) {

                return true;
            }
        }

        return false;
    }

    /**
     * @return array|mixed
     */
    protected function getServer(string $var = null)
    {
        if ($var === null) {

            return $this->server;
        }
        
        return $this->server[$var] ?? "";
    }

    // ******************** SESSION ******************** \\
    
    /**
     * @param string $var
     * @return bool
     */
    protected function checkAlert(string $var = null)
    {
        if (!empty($this->alert)) {

            if ($var === null) {

                return true;
            }

            if (in_array($var, $this->alert) && !empty($this->alert[$var])) {

                return true;
            }
        }

        return false;
    }

    /**
     * @param string $var
     * @return bool
     */
    protected function checkSession(string $var = null)
    {
        if (!empty($this->session)) {

            if ($var === null) {

                return true;
            }

            if (in_array($var, $this->session) && !empty($this->session[$var])) {

                return true;
            }
        }

        return false;
    }

    /**
     * @param string $var
     * @return bool
     */
    protected function checkUser(string $var = null)
    {
        if (!empty($this->user)) {

            if ($var === null) {

                return true;
            }

            if (in_array($var, $this->user) && !empty($this->user[$var])) {

                return true;
            }
        }

        return false;
    }

    /**
     * @param string $message
     * @param string $type
     */
    protected function createAlert(string $message, string $type = "black")
    {
        $_SESSION["alert"] = [
            "message" => $message,
            "type"    => $type
        ];
    }

    /**
     * @param array $user
     */
    protected function createSession(array $user)
    {
        if (isset($user["pass"])) {
            unset($user["pass"]);

        } elseif (isset($user["password"])) {
            unset($user["password"]);
        }

        $_SESSION["user"] = $user;
    }

    protected function destroySession()
    {
        $_SESSION["user"] = [];
        session_destroy();
    }

    /**
     * @return mixed
     */
    protected function getAlertType()
    {
        if (isset($this->alert)) {

            return $this->alert["type"];
        }
    }

    protected function getAlertMessage()
    {
        if (isset($this->alert)) {

            echo filter_var($this->alert["message"]);

            unset($_SESSION["alert"]);
        }
    }

    /**
     * @return array|mixed
     */
    protected function getSession(string $var = null)
    {
        if ($var === null) {

            return $this->session;
        }
        
        return $this->session[$var] ?? "";
    }

    /**
     * @param $var
     * @return mixed
     */
    protected function getUser($var)
    {
        if ($this->isLogged() === false) {
            $this->user[$var] = null;
        }

        return $this->user[$var];
    }

    /**
     * @return bool
     */
    protected function hasAlert()
    {
        return empty($this->alert) === false;
    }

    /**
     * @return bool
     */
    protected function isLogged()
    {
        if (array_key_exists("user", $this->session)) {

            if (!empty($this->user)) {

                return true;
            }
        }

        return false;
    }
}

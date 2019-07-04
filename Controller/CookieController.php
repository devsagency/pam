<?php

namespace Pam\Controller;

/**
 * Class CookieController
 * @package Pam\Controller
 */
class CookieController implements CookieControllerInterface
{
    /**
     * @param string $name
     * @param string $value
     * @param int $expire
     * @return mixed|void
     */
    public function createCookie(string $name, string $value = '', int $expire = 0)
    {
        if ($expire === 0) {
            $expire = time() + 3600;
        }
        setcookie($name, $value, $expire, '/');
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function readCookie(string $name)
    {
        return filter_input(INPUT_COOKIE, $name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function deleteCookie(string $name)
    {
        if (filter_input(INPUT_COOKIE, $name) !== null) {

            $this->createCookie($name, '', time() - 3600);

            return true;
        }
        return false;
    }

    /**
     * @param string $message
     */
    public function createAlert(string $message)
    {
        $this->createCookie('alert', $message);
    }

    /**
     * @return bool
     */
    public function hasAlert()
    {
        return empty($this->readCookie('alert')) == false;
    }

    /**
     * @return mixed|void
     */
    public function readAlert()
    {
        $alert = $this->readCookie('alert');

        if (isset($alert)) {

            echo filter_var($alert);

            $this->deleteCookie('alert');
        }
    }
}

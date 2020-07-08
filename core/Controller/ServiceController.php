<?php

namespace Pam\Controller;

use Pam\Controller\Service\ArrayManager;
use Pam\Controller\Service\CurlManager;
use Pam\Controller\Service\ImageManager;
use Pam\Controller\Service\MailManager;
use Pam\Controller\Service\SecurityManager;
use Pam\Controller\Service\StringManager;

/**
 * Class ServiceController
 * @package Pam\Controller
 */
abstract class ServiceController extends GlobalsController
{
    /**
     * @var ArrayManager
     */
    private $array = null;

    /**
     * @var CurlManager
     */
    private $curl = null;

    /**
     * @var ImageManager
     */
    private $image = null;

    /**
     * @var MailManager
     */
    private $mail = null;

    /**
     * @var SecurityManager
     */
    private $security = null;

    /**
     * @var StringManager
     */
    private $string = null;

    /**
     * ServiceController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->array    = new ArrayManager();
        $this->curl     = new CurlManager();
        $this->image    = new ImageManager();
        $this->mail     = new MailManager();
        $this->security = new SecurityManager();
        $this->string   = new StringManager();
    }

    /**
     * @return ArrayManager
     */
    public function getArray(): ArrayManager
    {
        return $this->array;
    }

    /**
     * @return CurlManager
     */
    public function getCurl(): CurlManager
    {
        return $this->curl;
    }

    /**
     * @return ImageManager
     */
    public function getImage(): ImageManager
    {
        return $this->image;
    }

    /**
     * @return MailManager
     */
    public function getMail(): MailManager
    {
        return $this->mail;
    }

    /**
     * @return SecurityManager
     */
    public function getSecurity(): SecurityManager
    {
        return $this->security;
    }

    /**
     * @return StringManager
     */
    public function getString(): StringManager
    {
        return $this->string;
    }
}

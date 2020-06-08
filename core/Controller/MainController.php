<?php

namespace Pam\Controller;

use Pam\View\PamExtension;
use ReCaptcha\ReCaptcha;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Class MainController
 * @package Pam\Controller
 */
abstract class MainController
{
    /**
     * @var GlobalsController|null
     */
    protected $globals = null;

    /**
     * @var MailController|null
     */
    protected $mail = null;

    /**
     * @var Environment|null
     */
    protected $twig = null;

    /**
     * MainController constructor
     */
    public function __construct()
    {
        $this->globals  = new GlobalsController();
        $this->mail     = new MailController();

        $this->twig = new Environment(new FilesystemLoader("../src/View"), array(
            "cache" => false,
            "debug" => true
        ));

        $this->twig->addExtension(new DebugExtension());
        $this->twig->addExtension(new PamExtension());
    }

    /**
     * @param string $page
     * @param array $params
     * @return string
     */
    public function url(string $page, array $params = [])
    {
        $params["access"] = $page;

        return "index.php?" . http_build_query($params);
    }

    /**
     * @param string $page
     * @param array $params
     */
    public function redirect(string $page, array $params = [])
    {
        header("Location: " . $this->url($page, $params));

        exit;
    }

    /**
     * @param string $view
     * @param array $params
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $view, array $params = [])
    {
        return $this->twig->render($view, $params);
    }

    /**
     * @param string $response
     * @return bool
     */
    public function checkRecaptcha(string $response)
    {
        $recaptcha = new ReCaptcha(RECAPTCHA_TOKEN);

        $result = $recaptcha
            ->setExpectedHostname($this->globals->getServer()->getServerVar("SERVER_NAME"))
            ->verify($response, $this->globals->getServer()->getServerVar("REMOTE_ADDR"));

        return $result->isSuccess();
    }

    public function checkAdminAccess()
    {
        $session = $this->globals->getSession()->getSessionArray();
        $isAdmin = false;

        if (isset($session["user"]["admin"])) {
            if ($this->globals->getSession()->getUserVar("admin") === true || $this->globals->getSession()->getUserVar("admin") === 1) {
                $isAdmin = true;
            }

        } elseif (isset($session["user"]["role"])) {
            if ($this->globals->getSession()->getUserVar("role") === 1 || $this->globals->getSession()->getUserVar("role") === "admin") {
                $isAdmin = true;
            }

        } else {
            if ($this->globals->getSession()->islogged() === true) {
                $isAdmin = true;
            }
        }

        if ($isAdmin === false) {
            $this->globals->getSession()->createAlert("You must be logged in as Admin to access to the administration", "black");

            $this->redirect("home");
        }
    }
}

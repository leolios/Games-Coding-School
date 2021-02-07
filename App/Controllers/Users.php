<?php
/*
 * MIT License
 *
 * Copyright (c) 2021. Paul Tedesco
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

namespace App\Controllers;


use App\Translations\EN;
use App\Translations\FR;
use App\Utils\Common;
use \App\Models\Users as UserModel;
use App\Utils\Utils;

/**
 * Class Users
 * @package App\Controllers
 */
class Users extends Common
{
    /**
     * @var EN|FR
     */
    private EN|FR $lang;

    /**
     * Users constructor.
     */
    public function __construct()
    {
        $this->lang = parent::getLang();
        parent::__construct($this->lang->getTranslation("user"));
    }

    /**
     * @param array|null $params
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getLogin(array|null $params = null)
    {
        parent::setPageTitle($this->lang->getTranslation("user") . " | " . $this->lang->getTranslation("login"));
        parent::getView(templatePath: "Users/login.twig", params: $params, navbar: true);
        return;
    }

    /**
     * @param array|null $params
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getRegister(array|null $params = null)
    {
        parent::setPageTitle($this->lang->getTranslation("user") . " | " . $this->lang->getTranslation("register"));
        parent::getView(templatePath: "Users/register.twig", params: $params, navbar: true);
        return;
    }

    public function postRegister(string $username, string $email, string $password, string $confirmPassword)
    {
        if (empty($username)) return self::getRegister(["error" => $this->lang->getTranslation(["username", "isRequired"])]);
        if (empty($email)) return self::getRegister(["error" => $this->lang->getTranslation(["email", "isRequired"])]);
        if (empty($password)) return self::getRegister(["error" => $this->lang->getTranslation(["password", "isRequired"])]);
        if ($password != $confirmPassword) return self::getRegister(["error" => $this->lang->getTranslation("passwordNotPasswordConfirm")]);
        $user_model = new UserModel();
        $user_model->user_schema->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user_model->user_schema->setUsername($username);
        $user_model->user_schema->setEmail($email);
        $result = $user_model->register();
        return self::getRegister((is_string($result)) ?
            ["error" => $this->lang->getTranslation($result)] :
            ["success" => $this->lang->getTranslation(["user", "create"])]
        );
    }

    public function postLogin(string $login, string $password)
    {
        if (empty($login)) return self::getRegister(["error" => $this->lang->getTranslation(["username", "isRequired"])]);
        if (empty($password)) return self::getRegister(["error" => $this->lang->getTranslation(["password", "isRequired"])]);
        $user_model = new UserModel();
        $user_model->user_schema->setPassword($password);
        $user_model->user_schema->setEmail($login);
        $result = $user_model->login();
        if (!is_string($result)) $_SESSION['user'] = $result;
        if (!is_string($result)) $_SESSION['language'] = $result->getLang();
        $params = (is_string($result)) ?
            ["error" => $this->lang->getTranslation($result)] :
            ["success" => $this->lang->getTranslation(["connected"])];
        if (!is_string($result)) {
            self::getLogin($params);
            sleep(3);
            Utils::redirect('/profil/' . $result->getId());
            return;
        } else {
            self::getLogin($params);
            return;
        }
    }

    /**
     * @param string|null $uuid
     */
    public function getProfils(string|null $uuid = null)
    {
        $model = new UserModel();
        parent::setPageTitle($this->lang->getTranslation("user") . " | " . $this->lang->getTranslation("profils"));
        if (!is_null($uuid)) {
            $model->user_schema->setId($uuid);
            $profil = $model->getOne($model->user_schema);
            $pp = md5(strtolower(trim($profil->getEmail())));
            parent::getView(templatePath: "Users/single.twig", params: ["profil" => $profil, "pp" => $pp], navbar: true);
        } else {
            $profils = $model->getAll($model->user_schema);
            parent::getView(templatePath: "Users/list.twig", params: ["profils" => $profils], navbar: true);
        }
        return;
    }

    /**
     * @param array|null $params
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getSettings(array|null $params = null)
    {
        $user = $_SESSION["user"];
        parent::setPageTitle($this->lang->getTranslation("settings") . " | " . $user->getUsername());
        parent::getView(templatePath: "Users/settings.twig", params: $params, navbar: true);
        return;
    }

    public function updateLang()
    {
        $_SESSION['language'] = $_POST["lang"];
        $model = new UserModel();
        $_SESSION['user']->setLang($_POST["lang"]);
        $model->update($_SESSION['user']);
        Utils::redirect('/settings');
    }

    /**
     * @param string|null $page
     * @param string|null $uuid
     */
    public function show(string|null $page = null, string|null $uuid = null)
    {
        switch ($page):
            case 'login':
                self::getLogin();
                break;
            case 'register':
                self::getRegister();
                break;
            case 'setting':
                self::getSettings();
                break;
            default:
                self::getProfils($uuid);
                break;
        endswitch;
    }

    public function update(array $info)
    {
        if (!empty($info["password"])):
            if ($info["password"] == $info["confPassword"]):
                $password = password_hash($info["password"], PASSWORD_DEFAULT);
                $_SESSION['user']->setPassword($password);
            endif;
        endif;
        if (!empty($info["username"])):
            $_SESSION['user']->setUsername($info["username"]);
        endif;
        if (!empty($info['email'])):
            $_SESSION['user']->setEmail($info["email"]);
        endif;

        $model = new UserModel();
        $model->update($_SESSION["user"]);

        Utils::redirect("/settings");
    }

}
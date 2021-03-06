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

namespace App\Routes;


use AltoRouter;
use App\Controllers\Admin\Dashboard;
use App\Controllers\Admin\LevelsCategories;
use App\Controllers\Admin\Users as userAdmin;
use App\Controllers\Home;
use App\Controllers\Users;
use App\Utils\Utils;
use Exception;

/**
 * Class Routes
 * @package App\Routes
 */
class Routes
{
    /**
     * @var AltoRouter
     */
    private AltoRouter $router;

    /**
     * Routes constructor.
     */
    public function __construct()
    {
        $this->router = new AltoRouter();
        (!isset($_SESSION['language'])) ? $_SESSION["language"] = "en" : $_SESSION["language"];
    }

    /**
     * @throws Exception
     */
    public function setRoutes(): void
    {
        $homeController = new Home();
        $userController = new Users();
        $dashboardController = new Dashboard();
        $user_adminController = new userAdmin();
        $levels_categoriesController = new LevelsCategories();

        $this->router->map('GET', '/', function () use ($homeController) {
            return $homeController->show();
        }, 'Home');

        if (!Utils::isConnected()):
            $this->router->map('GET', '/login', function () use ($userController) {
                return $userController->show("login");
            }, 'login');
            $this->router->map('GET', '/register', function () use ($userController) {
                return $userController->show("register");
            }, 'register');
        endif;

        $this->router->map('GET', '/profil', function () use ($userController) {
            return $userController->show();
        }, 'profils');
        $this->router->map('GET', '/profil/[*:uuid]', function ($uuid) use ($userController) {
            return $userController->show(uuid: $uuid);
        }, 'profil');

        if (Utils::isConnected()):
            $this->router->map('GET', '/settings', function () use ($userController) {
                return $userController->show('setting');
            }, 'settings');
            $this->router->map('POST', '/settings', function () use ($userController) {
                return $userController->update($_POST);
            }, 'update_information');
            $this->router->map('POST', '/settings/lang', function () use ($userController) {
                return $userController->updateLang();
            }, 'update_lang');
            $this->router->map('GET', '/logout', function () {
                session_destroy();
                Utils::redirect('/login');
            }, 'logout');
        endif;
        if (Utils::isConnected() && Utils::isAdmin()):
            $this->router->map('GET', '/admin', function () use ($dashboardController) {
                return $dashboardController->show();
            }, 'dashboard');
            $this->router->map('GET', '/admin/users', function () use ($user_adminController) {
                return $user_adminController->show();
            }, 'admin_users');
            $this->router->map('GET', '/admin/users/delete/[*:uuid]', function ($uuid) use ($user_adminController) {
                return $user_adminController->deleteUser($uuid);
            }, 'admin_users_delete');
            $this->router->map('POST', '/admin/users/update/[*:uuid]', function ($uuid) use ($user_adminController) {
                return $user_adminController->updateUser($_POST, $uuid);
            }, 'admin_users_update');
            $this->router->map('GET', '/admin/levels', function () use ($dashboardController) {
                return $dashboardController->show();
            }, 'admin_levels');
            $this->router->map('GET', '/admin/levels_cat', function () use ($levels_categoriesController) {
                return $levels_categoriesController->show();
            }, 'admin_levels_cat');
            $this->router->map('POST', '/admin/levels_cat/create', function () use ($levels_categoriesController) {
                return $levels_categoriesController->create($_POST);
            }, 'admin_levels_cat_create');
            $this->router->map('POST', '/admin/levels_cat/update/[*:uuid]', function ($uuid) use ($levels_categoriesController) {
                return $levels_categoriesController->update($uuid, $_POST);
            }, 'admin_levels_cat_update');
            $this->router->map('GET', '/admin/levels_cat/delete/[*:uuid]', function ($uuid) use ($levels_categoriesController) {
                return $levels_categoriesController->delete($uuid);
            }, 'admin_levels_cat_delete');
        endif;

        $this->router->map('POST', "/register", function () use ($userController) {
            return $userController->postRegister($_POST['username'], $_POST['email'], $_POST['password'], $_POST['password_confirmation']);
        }, "registerPost");

        $this->router->map('POST', "/login", function () use ($userController) {
            return $userController->postLogin($_POST['login'], $_POST['password']);
        }, "loginPost");
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        self::setRoutes();

        $match = $this->router->match();
        // call closure or throw 404 status
        if (is_array($match) && is_callable($match['target'])) {
            call_user_func_array($match['target'], $match['params']);
        } else {
            Utils::redirect("/");
        }
        return;
    }


}
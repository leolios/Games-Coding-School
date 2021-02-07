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

namespace App\Utils;

use App\Translations\EN;
use App\Translations\FR;
use JetBrains\PhpStorm\Pure;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * Class Common
 * @package App\Utils
 */
abstract class Common
{
    const ASSETS        = "/App/Assets";
    const TEMPLATE_PATH = "App/Views";


    /**
     * common constructor.
     * @param $pageTitle
     */
    function __construct(private string $pageTitle = "Games Coding School") { }

    /**
     * @param string $pageTitle
     */
    public function setPageTitle(string $pageTitle): void
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * @param string $templatePath
     * @param array|null $params
     * @param bool $sidebar
     * @param bool $navbar
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    function getView(string $templatePath, array|null $params = null, bool $sidebar = false, bool $navbar = false)
    {
        // Load template Twig
        $loader = new FilesystemLoader($this::TEMPLATE_PATH);
        $twig = new Environment($loader, ['debug' => true]);

        // Add Parameters
        (is_null($params)) ? $params = ["title" => $this->pageTitle] : $params["title"] = $this->pageTitle;
        $params["assets"] = self::ASSETS;
        if (Utils::isConnected()) $params["user"] = $_SESSION["user"];
        if (Utils::isConnected()) $params["hashMD5"] = md5(strtolower(trim($_SESSION['user']->getEmail())));
        $params["isAdmin"] = Utils::isAdmin();
        $params["sidebar"] = $sidebar;
        $params["navbar"] = $navbar;
        $params["lang"] = self::getLang();
        //Load Page
        $filter = new \Twig\TwigFilter('md5', function (string $string) {
            return md5($string);
        });
        $twig->addFilter($filter);
        $page = $twig->load($templatePath);

        //Show Page
        return $page->display($params);
    }

    /**
     * @return FR|EN
     */
    #[Pure] function getLang(): FR|EN
    {
        $lg = $_SESSION["language"];
        switch ($lg):
            case "fr":
                $lang = new FR();
                break;
            case "en":
                $lang = new EN();
                break;
            default:
                $lang = new FR();
        endswitch;

        return $lang;
    }
}
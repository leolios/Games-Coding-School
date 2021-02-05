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

namespace App\Translations;


use JetBrains\PhpStorm\Pure;

class EN extends Language
{
    const lang = [
        "lang" => "en",
        "home" => "Home",
        "login" => "login",
        "register" => "register",
        "user" => "user",
        "users" => "users",
        "profil" => "profil",
        "profils" => "profils",
        "email" => "email",
        "username" => "username",
        "your" => "your",
        "of" => "of",
        "form" => "form",
        "password" => "password",
        "confirmPassword" => "confirm password",
        "registerForm" => "register form",
        "or" => "or",
        "allreadyAccount" => "I already have an account",
        "noAccount" => "I do not have an account",
        "isRequired" => "is required",
        "passwordNotPasswordConfirm" => "Password and confirm is not equal",
        "create" => "was created",
        "errorRegister" => "an error occurred during registration",
        "emailAllreadyUsed" => "email already used",
        "pseudoAllreadyUsed" => "Username already used",
        "invalidLogin" => "Incorrect username, email or password",
        "connected" => "You are connected",
        "welcome" => "welcome",
        "my" => "My",
        "settings" => "settings",
        "logout" => "logout",
        "infoUser" => "Informations",
        "language" => "language",
        "update" => "update",
        "level" => "levels",
        "pp" => "profil's picture",
        "back" => "back",
        "to" => "to",
        "list" => "the list",
        "role" => "role"
    ];

    /**
     * @param string|array $key
     * @return string
     */
    #[Pure] public static function getTranslation(string|array $key): string
    {
        if (!is_array($key)) {
            return parent::word($key, self::lang);
        } else {
            $string = "";
            foreach ($key as $k):
                if (array_key_last($key) == $k) {
                    $string .= parent::word($k, self::lang);
                } else {
                    $string .= parent::word($k, self::lang) . " ";
                }
            endforeach;
            return $string;
        }
    }
}
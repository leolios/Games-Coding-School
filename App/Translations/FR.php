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

class FR extends Language
{
    const lang = [
        "lang" => "fr",
        "home" => "accueil",
        "login" => "connexion",
        "register" => "inscription",
        "user" => "utilisateur",
        "Users" => "utilisateurs",
        "profil" => "profile",
        "profils" => "profiles",
        "of" => "de",
        "form" => "formulaire",
        "your" => "votre",
        "username" => "nom d'utilisateur",
        "email" => "email",
        "password" => "mot de passe",
        "confirmPassword" => "confirmation du mot de passe",
        "registerForm" => "formulaire d'inscription",
        "or" => "ou",
        "allreadyAccount" => "j'ai déjà un compte",
        "noAccount" => "Je n'ai pas de compte",
        "isRequired" => "est requis",
        "passwordNotPasswordConfirm" => "Le mot de passe et la confirmation ne sont pas équal",
        "create" => "à été créer",
        "errorRegister" => "une erreur c'est produite lors de l'enregistrement",
        "emailAllreadyUsed" => "Email est déjà utilisé",
        "pseudoAllreadyUsed" => "Nom d'utilisateur déjà utilisé",
        "invalidLogin" => "Nom d'utilisateur, email ou mot de passe incorrect",
        "connected" => "Vous êtes connecté",
        "welcome" => "Bienvenue",
        "my" => "Mon",
        "settings" => "paramètre",
        "logout" => "deconnection",
        "infoUser" => "Vos Informations",
        "language" => "langue",
        "update" => "mise à jour",
        "level" => "niveaux",
        "pp" => "photo de profile",
        "back" => "retour",
        "to" => "a",
        "list" => "la liste",
        "role" => "role",
        "homeSubTitle"=>"sur la Games Coding School",
        "homeTitleParagraphe" => "le site qui va vous apprendre à coder avec différent jeux.<br> Alors qu'es ce que tu attends pour te lancer ?",
        "admin" => "administration",
        "backToSite" => "retour au site",
        "userCount" => "compte des utilisateurs",
        "levelCount" => "compte des niveaux",
        "categories" => "categories"
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
                $word = parent::word($k, self::lang);
                if (array_key_last($key) == $k) {
                    $string .= $word;
                } else {
                    $string .= $word . " ";
                }
            endforeach;
            return $string;
        }
    }
}
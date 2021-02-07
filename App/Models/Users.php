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

namespace App\Models;


use App\Utils\DataBaseConnection;
use App\Schemas\Users as Users_Schema;
use PDO;

class Users extends DataBaseConnection
{
    public Users_Schema $user_schema;

    public function __construct()
    {
        parent::__construct();
        $this->user_schema = new Users_Schema();
    }

    /**
     * @return bool|string|null
     */
    public function register()
    {
        $register = $this->DB->prepare("SELECT * FROM `users` WHERE email = :1");
        $register->bindParam(":1", $this->user_schema->email, PDO::PARAM_STR);
        $register->execute();
        $users = $register->fetchAll(PDO::FETCH_CLASS, get_class($this->user_schema));
        if (count($users) > 0) return "emailAllreadyUsed";

        $register = $this->DB->prepare("SELECT * FROM `users` WHERE username = :1");
        $register->bindParam(":1", $this->user_schema->username, PDO::PARAM_STR);
        $register->execute();
        $users = $register->fetchAll(PDO::FETCH_CLASS, get_class($this->user_schema));
        if (count($users) > 0) return "pseudoAllreadyUsed";

        return parent::create($this->user_schema);
    }

    public function login()
    {
        $login = $this->DB->prepare("SELECT * FROM `users` WHERE email = :email OR username = :email");
        $login->bindParam(":email", $this->user_schema->email, PDO::PARAM_STR);
        $login->execute();
        $users = $login->fetchAll(PDO::FETCH_CLASS, get_class($this->user_schema));
        if (empty($users)) return "invalidLogin";
        else {
            $users = $users[0];
            if (!password_verify($this->user_schema->password, $users->getPassword())) return "invalidLogin";
            return $users;
        }
    }
}
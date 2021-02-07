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

namespace App\Controllers\Admin;


use App\Utils\Common;
use App\Models\Users as UsersModel;
use App\Utils\Utils;

class Users extends Common
{
    public function __construct()
    {
        parent::__construct("Admin");
    }

    public function show()
    {
        $users = new UsersModel();
        $param["profils"] = $users->getAll($users->user_schema);
        parent::getView('Admin/users.twig', $param, sidebar: true);
    }

    public function deleteUser($id)
    {
        $users = new UsersModel();
        $users->user_schema->setId($id);
        $users->delete($users->user_schema);
        Utils::redirect("/admin/users");
        return;
    }

    public function updateUser(array $form, $uuid)
    {
        $users = new UsersModel();
        $user_schema = $users->user_schema;
        $user_schema->setId($uuid);
        $user_schema->setEmail($form["email"]);
        $user_schema->setRole($form["role"]);
        $user_schema->setUsername($form["username"]);

        $users->update($user_schema);

        Utils::redirect("/admin/users");
        return;
    }
}
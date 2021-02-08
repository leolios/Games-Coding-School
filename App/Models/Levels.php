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


use App\Schemas\Levels as Levels_Schema;
use App\Utils\DataBaseConnection;
use PDO;

class Levels extends DataBaseConnection
{
    public Levels_Schema $levels_schema;

    public function __construct()
    {
        parent::__construct();
        $this->levels_schema = new Levels_Schema();
    }

    public function getLevelByCat($id_cat)
    {
        $get = $this->DB->prepare("SELECT * FROM `" . $this->levels_schema::TABLE_NAME . "` WHERE cat_id=:id;");
        $get->bindParam(":id", $id_cat, PDO::PARAM_INT);
        $get->execute();
        $result = $get->fetchAll(PDO::FETCH_CLASS, get_class($this->levels_schema));
        return ($result) ? $result : false;
    }
}
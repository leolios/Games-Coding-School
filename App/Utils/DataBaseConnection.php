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


use PDO;
use PDOException;

/**
 * Class DataBaseConnection
 * @package App\Utils
 */
abstract class DataBaseConnection
{
    protected ?PDO $DB;

    /**
     * DataBaseConnection constructor.
     */
    protected function __construct()
    {
        $DATABASE = (getenv("DB_NAME")) ? getenv("DB_NAME") : "codinggame";
        $HOST = (getenv("DB_HOST")) ? getenv("DB_HOST") : "localhost";
        $PORT = (getenv("DB_PORT")) ? getenv("DB_PORT") : "3306";
        $DB_USER = (getenv("DB_USER")) ? getenv("DB_USER") : "root";
        $DB_PASSWORD = (getenv("DB_PASSWORD")) ? getenv("DB_PASSWORD") : "";
        try {
            $this->DB = new PDO("mysql:dbname=" . $DATABASE . ";host=" . $HOST . ";port=" . $PORT, $DB_USER, $DB_PASSWORD);
        } catch (PDOException $e) {
            var_dump('Connection failed : ' . $e->getMessage());
        }
    }

    /**
     * @param object $OBJECT
     * @return array
     */
    #[Pure] protected function getVars(object $OBJECT): array
    {
        return get_object_vars($OBJECT);
    }

    /**
     * @param object $OBJECT
     * @return array|null
     * @author pault
     */
    function getAll(object $OBJECT): ?array
    {
        $get = $this->DB->prepare("SELECT * FROM `" . $OBJECT::TABLE_NAME . "`;");
        $get->execute();
        $result = $get->fetchAll(PDO::FETCH_CLASS, get_class($OBJECT));
        return ($result) ? $result : false;
    }

    /**
     * @param object $OBJECT
     * @return object|null
     * @author pault
     */
    function getOne(object $OBJECT): ?object
    {
        $get = $this->DB->prepare("SELECT * FROM `" . $OBJECT::TABLE_NAME . "` WHERE id=:id;");
        $get->bindParam(":id", $OBJECT->id, PDO::PARAM_INT);
        $get->execute();
        $result = $get->fetchAll(PDO::FETCH_CLASS, get_class($OBJECT));
        return ($result[0]) ? $result[0] : false;
    }

    /**
     * @param object $OBJECT
     * @return Exception|bool|null
     * @author pault
     */
    function update(object $OBJECT): Exception|bool|null
    {
        $array = $this->getVars($OBJECT);
        $sql = "UPDATE `" . $OBJECT::TABLE_NAME . "` SET";
        unset($array['tableName']);
        foreach ($array as $key => $value):
            if (array_key_last($array) != $key):
                if ($key != "id"):
                    $sql .= " `$key` = :$key,";
                endif;
            else:
                $sql .= "`$key` = :$key WHERE id = :id";
            endif;
        endforeach;
        foreach ($array as $key => $value):
            $array[":$key"] = $array[$key];
            unset($array[$key]);
        endforeach;
        var_dump($sql);
        $update = $this->DB->prepare($sql);
        return $update->execute($array);
    }

    /**
     * @param object $OBJECT
     * @return Exception|bool|null
     * @author pault
     */

    function create(object $OBJECT): Exception|bool|null
    {
        $array = $this->getVars($OBJECT);
        $sql = "INSERT INTO `" . $OBJECT::TABLE_NAME . "` ";
        $columnName = "( `id`,";
        $columnValue = "VALUES ( uuid(),";
        unset($array["tableName"]);
        if (array_key_exists("id", $array)) unset($array['id']);
        foreach ($array as $key => $value):
            if (array_key_last($array) != $key):
                $columnName .= "`$key`,";
                $columnValue .= ":$key,";
            else:
                $columnName .= "`$key` ) ";
                $columnValue .= ":$key )";
            endif;
        endforeach;
        foreach ($array as $key => $value):
            $array[":$key"] = $array[$key];
            unset($array[$key]);
        endforeach;
        $sql .= $columnName . $columnValue;
        $create = $this->DB->prepare($sql);
        return $create->execute($array);
    }


    /**
     * @param object $OBJECT
     * @return Exception|bool|null
     * @author pault
     */
    function delete(object $OBJECT): Exception|bool|null
    {
        $get = $this->DB->prepare("DELETE FROM `" . $OBJECT::TABLE_NAME . "` WHERE `id`=:1;");
        $get->bindParam(":1", $OBJECT->id, PDO::PARAM_INT);
        return $get->execute();
    }

}
<?php

namespace app\core\db;

use app\core\Application;
use app\core\Model;

abstract class DbModel extends Model
{
    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;

    public function save()
    {
        $tableName = $this->tableName(); 
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).")
            VALUES(".implode(',', $params).")");
        foreach ($attributes as $attr) {
            $statement->bindValue(":$attr", $this->{$attr});
        }
        $statement->execute();
        return true;
    }

    /**
     * prepare sql for execute
     */
    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    public function findOne($where) // [email => test@example.com, firstname => test]
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sqlWhere = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sqlWhere");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }
}
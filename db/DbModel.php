<?php

namespace dimmvc\phpmvc\db;

use dimmvc\phpmvc\Application;
use dimmvc\phpmvc\Model;
use Exception;

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;

    public function save()
    {
        try {
            $tableName = static::tableName(); 
            $attributes = $this->attributes();
            $params = array_map(fn($attr) => ":$attr", $attributes);
            $statement = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).")
                VALUES(".implode(',', $params).")");
            foreach ($attributes as $attr) {
                $statement->bindValue(":$attr", $this->{$attr});
            }
            $statement->execute();
        } catch (Exception $e) {
            throw $e;           
        }
        return true;
    }
    
    public function update($id)
    {
        try {
            $tableName = static::tableName(); 
            $attributes = $this->attributes();
            foreach ($attributes as $attr) {
                $updateClause[] = $attr . ' = \'' . $this->{$attr} . '\'';
            }

            $statement = self::prepare("UPDATE $tableName SET " . implode(', ', $updateClause)
                . " WHERE id = " . $id);
            $statement->execute();
        } catch (Exception $e) {
            throw $e;           
        }
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

    public static function findAll()
    {
        $tableName = static::tableName();
        $statement = self::prepare("SELECT * from $tableName");
        $statement->execute();

        return $statement->fetchAll();
    }
}
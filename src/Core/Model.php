<?php

namespace App\Core;

abstract class Model {

    protected static $fillable = [];
    protected static $table;
    protected static $db;

    public int $id;

    public function __construct(array $args = []) {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public static function setDb($database) {
        self::$db = $database;
    }

    protected static function verifyFillable(array $args): bool {
        foreach (static::$fillable as $field) {
            if (!array_key_exists($field, $args)) {
                return false;
            }
        }
        return true;
    }

    public static function create(array $args): int|bool {

        if (!static::verifyFillable($args)) {
            return false;
        }

        $columns = implode(', ', array_keys($args));
        $placeholders = implode(', ', array_fill(0, count($args), '?'));

        $query = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";

        $stmt = static::$db->prepare($query);

        $stmt->execute(array_values($args));

        return static::$db->lastInsertId();

    }

    public function update(array $args): bool {

        if (!static::verifyFillable($args)) {
            return false;
        }

        $fields = implode(', ', array_map(
            fn($key) => "$key = :$key",
            array_keys($args)
        ));

        $query = "UPDATE " . static::$table . " SET $fields WHERE id = :id";

        $stmt = static::$db->prepare($query);

        return $stmt->execute(array_merge($args, ['id' => $this->id]));
    }

    public function delete(): bool {

        $query = "DELETE FROM " . static::$table . " WHERE id = :id";

        $stmt = static::$db->prepare($query);

        $stmt->execute(['id' => $this->id]);

        return $resultado = $stmt->get_result();
    }

public static function all(): array {
    $query = "SELECT * FROM " . static::$table;

    $stmt = static::$db->prepare($query);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $array = [];
    while ($registro = $resultado->fetch_assoc()) {
        $array[] = static::crearObjeto($registro);
    }

    $stmt->close();

    return $array;
}

    public static function find($idt){
        $query = "SELECT * FROM ". static::$table . " WHERE id = ". $idt. ";";
        $stmt = static::$db->prepare($query);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
        $array[] = static::crearObjeto($registro);
    }

    $stmt->close();

    return $array;
    }

    public static function where(array $args): array {
        $where = implode(' AND ', array_map(
            fn($key) => "$key = :$key",
            array_keys($args)
        ));

        $query = "SELECT * FROM " . static::$table . " WHERE $where";

        $stmt = static::$db->prepare($query);

        $stmt->execute($args);

        $resultado = $stmt->get_result();

        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        $stmt->close();
        return $array;
    }
    protected static function crearObjeto($registro){
        $objeto = new static;
        foreach($registro as $key => $value){
            if(property_exists($objeto, $key)){
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }
}

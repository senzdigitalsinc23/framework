<?php

namespace Database\ORM;
 /*

namespace Database\ORM;

use App\Core\Database;
use PDO;

abstract class Model
{
    //protected string $table;
    protected PDO $this->db;
    protected static string $table;
    protected array $attributes = [];
    protected array $relations = [];

    public function __construct(array $attributes = [])
    {
        $this->db = Database::get
        $this->attributes = $attributes;
    }

    public static function find(int $id): ?object
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE id = :id LIMIT 1";
        $stmt = $instance->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchObject(static::class);
        return $result ?: null;
    }

    public static function findByEmail(string $email): ?object
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE email = :email LIMIT 1";
        $stmt = $instance->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetchObject(static::class);
        return $result ?: null;
    }

    public static function where(string $column, $value): ?object
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE {$column} = :value LIMIT 1";
        $stmt = $instance->db->prepare($sql);
        $stmt->execute(['value' => $value]);
        $result = $stmt->fetchObject(static::class);
        return $result ?: null;
    }

    public static function all(): ?object
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table}";
        $stmt = $instance->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchObject(static::class);
        return $result ?: null;
    }

   
    public static function create(array $data): object
    {
        $instance = new static();
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_map(fn($key) => ":$key", array_keys($data)));

        $sql = "INSERT INTO {$instance->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $instance->db->prepare($sql);
        $stmt->execute($data);

        $id = $instance->db->lastInsertId();
        return static::find((int) $id);
    }
}*/

use App\Core\Database;
use App\Models\User;
use PDO;

abstract class Model
{
    protected static string $table;
    protected array $attributes = [];
    protected array $relations = [];
    protected PDO $db;

    public function __construct(array $attributes = [])
    {
        $this->db = Database::getInstance()->getConnection();
        $this->attributes = $attributes;
    }

    public function __get($key)
    {
        // Lazy load relation if defined
        if (method_exists($this, $key)) {
            if (!isset($this->relations[$key])) {
                $this->relations[$key] = $this->$key();
            }
            return $this->relations[$key];
        }

        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public static function all()
    {
        
        $table = static::$table;
        $instance = new static();

        $sql = "SELECT * FROM {$table}";
        $stmt = $instance->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //show($rows);
        return $rows;
    }

    public static function find(int $id): ?static
    {
        $table = static::$table;
        $instance = new static();

        $sql = "SELECT * FROM {$table} WHERE id = ? LIMIT 1";
        $stmt = $instance->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $rows = $stmt->fetchObject(static::class);

        return $rows;
    }

    public static function where(string $column, $value)
    {        
        $table = static::$table;
        $instance = new static();

        $sql = "SELECT * FROM {$table} WHERE {$column} = :value LIMIT 1";
        $stmt = $instance->db->prepare($sql);
        $stmt->execute(['value' => $value]);
        $rows = $stmt->fetchObject(static::class);

        return $rows;
    }

    public static function create(array $data)
    {
        $table = static::$table;
        $instance = new static();

        $columns = implode(",", array_keys($data));

        $placeholders = implode(",:", array_keys($data));

        $placeholders = ":" . $placeholders;

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        //echo json_encode($placeholders);exit;
        $stmt = $instance->db->prepare($sql);
        $stmt->execute($data);

        $id = $instance->db->lastInsertId();
        $data['id'] = $id;

        //return new static($data);
    }

    // -------------------
    // 🚀 Relation Helpers
    // -------------------

    protected function hasOne(string $related, string $foreignKey, string $localKey = 'id')
    {
        $instance = new $related();
        return $related::where($foreignKey, $this->$localKey)[0] ?? null;
    }

    protected function hasMany(string $related, string $foreignKey, string $localKey = 'id')
    {
        return $related::where($foreignKey, $this->$localKey);
    }

    protected function belongsTo(string $related, string $foreignKey, string $ownerKey = 'id')
    {
        $instance = new $related();

        //show(json_encode($this->foreignKey));
        return $related::where($ownerKey, $foreignKey) ?? null;

        $table = static::$table;

        show(json_encode($pivotTable));
        $sql = "SELECT us.* FROM {$table} us JOIN {$pivotTable} p ON r.id = p.{$relatedKey} WHERE p.{$foreignKey} = :$foreignKey";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$foreignKey => $this->id]);
        $rows = $stmt->fetchObject(static::class);

        return $rows;
    }

    protected function belongsToMany(string $related, string $pivotTable, string $foreignKey, string $relatedKey)
    {
        
        $table = static::$table;

        show(json_encode($foreignKey));
        $sql = "SELECT r.* FROM {$table} r JOIN {$pivotTable} p ON r.id = p.{$relatedKey} WHERE p.{$foreignKey} = :$foreignKey";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$foreignKey => $this->id]);
        $rows = $stmt->fetchObject(static::class);

        return $rows;
    }

     public function save(): bool
    {
        $db = Database::getInstance(); // assume you already have this
        $pdo = $db->getConnection();

        $columns = array_keys($this->attributes);
        $placeholders = array_map(fn($c) => ":$c", $columns);

        $sql = "INSERT INTO {$this->table} (" . implode(',', $columns) . ")
                VALUES (" . implode(',', $placeholders) . ")";

        $stmt = $pdo->prepare($sql);

        foreach ($this->attributes as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

}

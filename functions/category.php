<?php
require_once "database.php";

const CATEGORIES_PER_PAGE = 10;


class Category
{
    /** @var PDO */
    private static $connection;

    public static function initialize()
    {
        self::$connection = Database::get_connection();
    }

    /**
     * @param int|null $page
     * @return array{0: object{
     *  id: number,
     *  name: string,
     *  description: string,
     * }[], 1: bool}
     */
    public static function fetchPage(?int $afterId = null)
    {
        $limit = CATEGORIES_PER_PAGE + 1;

        if ($afterId === null) {
            $stmt = self::$connection->prepare("SELECT * FROM Category ORDER BY id ASC LIMIT $limit");
            $stmt->execute();
        } else {
            $stmt = self::$connection->prepare("SELECT * FROM Category WHERE id > :afterId ORDER BY id ASC LIMIT $limit");
            $stmt->execute(['afterId' => $afterId]);
        }

        $all = $stmt->fetchAll(PDO::FETCH_OBJ);

        $hasMore = count($all) > CATEGORIES_PER_PAGE;
        if ($hasMore) {
            array_pop($all);
        }

        return [$all, $hasMore];
    }

    /**
     * @return object{
     *  id: number,
     *  name: string,
     *  description: string,
     * }[]}
     */
    public static function fetchAll()
    {
        $stmt = self::$connection->query("SELECT * FROM Category ORDER BY id ASC");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @return number | false
     */
    public static function getCount()
    {
        $statement = self::$connection->prepare("SELECT count(*) FROM Category;");
        $statement->execute();

        return $statement->fetchColumn();
    }

    /**
     * @param number $id
     * @return object{
     *  id: number,
     *  name: string,
     *  description: string,
     * }|false
     */
    public static function get($id)
    {
        $statement = self::$connection->prepare("SELECT * FROM Category WHERE id = :id;");
        $statement->execute(["id" => $id]);

        return $statement->fetchObject();
    }

    /**
     * @param number $id
     * @param string $name
     * @param string $description
     * @return bool
     */
    public static function set($id, $name, $description)
    {
        $statement = self::$connection->prepare("UPDATE Category SET name=:name, description=:description WHERE id = :id;");

        return $statement->execute([
            "id" => $id,
            "name" => $name,
            "description" => $description
        ]);
    }

    /**
     * @param string $name
     * @param string $description
     * @return int 0 on success, 1 if category of this name exisits
     */
    public static function create($name, $description)
    {
        $statement = self::$connection->prepare("Select CreateCategory(:name, :description);");
        $statement->execute([
            "name" => $name,
            "description" => $description
        ]);

        return $statement->fetchColumn();
    }

    /**
     * @param int $id
     */
    public static function delete($id)
    {
        $statement = self::$connection->prepare("Select DeleteCategory(:id);");
        $statement->execute([
            "id" => $id
        ]);

        return $statement->fetchColumn();
    }
}

Category::initialize();
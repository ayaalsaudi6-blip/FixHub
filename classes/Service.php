<?php
class Service
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM services
             WHERE deleted_at IS NULL
             ORDER BY id DESC"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDeleted()
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM services
             WHERE deleted_at IS NOT NULL
             ORDER BY deleted_at DESC"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPopular($limit = 8)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM services
             WHERE deleted_at IS NULL
             ORDER BY id ASC
             LIMIT :limit"
        );

        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM services
             WHERE id = :id
             AND deleted_at IS NULL
             LIMIT 1"
        );

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($keyword = '', $category = '', $location = '')
    {
        $sql = "SELECT * FROM services WHERE deleted_at IS NULL";
        $params = [];

        if ($keyword != '') {
            $sql .= " AND (name LIKE :keyword OR description LIKE :keyword)";
            $params[':keyword'] = "%$keyword%";
        }

        if ($category != '') {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }

        if ($location != '') {
            $sql .= " AND location = :location";
            $params[':location'] = $location;
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategories()
    {
        $stmt = $this->pdo->prepare(
            "SELECT DISTINCT category FROM services
             WHERE category IS NOT NULL
             AND deleted_at IS NULL"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getLocations()
    {
        $stmt = $this->pdo->prepare(
            "SELECT DISTINCT location FROM services
             WHERE location IS NOT NULL
             AND deleted_at IS NULL"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function add($name, $description, $category, $icon, $image)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO services
            (name, description, category, icon, image)
            VALUES
            (:name, :description, :category, :icon, :image)"
        );

        return $stmt->execute([
            ":name" => $name,
            ":description" => $description,
            ":category" => $category,
            ":icon" => $icon,
            ":image" => $image
        ]);
    }

    public function update($id, $name, $description, $category, $icon, $image)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE services
             SET name = :name,
                 description = :description,
                 category = :category,
                 icon = :icon,
                 image = :image
             WHERE id = :id"
        );

        return $stmt->execute([
            ":id" => $id,
            ":name" => $name,
            ":description" => $description,
            ":category" => $category,
            ":icon" => $icon,
            ":image" => $image
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE services
             SET deleted_at = NOW()
             WHERE id = :id"
        );

        return $stmt->execute([
            ":id" => $id
        ]);
    }

    public function restore($id)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE services
             SET deleted_at = NULL
             WHERE id = :id"
        );

        return $stmt->execute([
            ":id" => $id
        ]);
    }

    public function permanentDelete($id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM services
             WHERE id = :id"
        );

        return $stmt->execute([
            ":id" => $id
        ]);
    }
}
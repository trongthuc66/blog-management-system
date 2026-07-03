<?php

@session_start();
require_once('connect.php');

// Debugging function (wrapped)
if (!function_exists('dd')) {
    function dd($value) 
    {
        echo "<pre>", print_r($value, true), "</pre>";
        die();
    }
}

// Execute query (wrapped)
if (!function_exists('executeQuery')) {
    function executeQuery($sql, $data)
    {
        global $conn;
        $stmt = $conn->prepare($sql);
        $values = array_values($data);
        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        return $stmt;
    }
}

// Select all (wrapped)
if (!function_exists('selectAll')) {
    function selectAll($table, $conditions = [])
    {
        global $conn;
        $sql = "SELECT * FROM $table";
        if (empty($conditions)) {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } else {
            $i = 0;
            foreach ($conditions as $key => $value) {
                $sql .= $i === 0 ? " WHERE $key=?" : " AND $key=?";
                $i++;
            }
            $stmt = executeQuery($sql, $conditions);
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
    }
}

// Select one (wrapped)
if (!function_exists('selectOne')) {
    function selectOne($table, $conditions)
    {
        global $conn;
        $sql = "SELECT * FROM $table";
        $i = 0;
        foreach ($conditions as $key => $value) {
            $sql .= $i === 0 ? " WHERE $key=?" : " AND $key=?";
            $i++;
        }
        $sql .= " LIMIT 1";
        $stmt = executeQuery($sql, $conditions);
        return $stmt->get_result()->fetch_assoc();
    }
}

// Create (wrapped)
if (!function_exists('create')) {
    function create($table, $data)
    {
        global $conn;
        $sql = "INSERT INTO $table SET ";
        $i = 0;
        foreach ($data as $key => $value) {
            $sql .= $i === 0 ? " $key=?" : ", $key=?";
            $i++;
        }
        $stmt = executeQuery($sql, $data);
        return $stmt->insert_id;
    }
}

// Update (wrapped)
if (!function_exists('update')) {
    function update($table, $id, $data)
    {
        global $conn;
        $sql = "UPDATE $table SET ";
        $i = 0;
        foreach ($data as $key => $value) {
            $sql .= $i === 0 ? " $key=?" : ", $key=?";
            $i++;
        }
        $sql .= " WHERE id=?";
        $data['id'] = $id;
        $stmt = executeQuery($sql, $data);
        return $stmt->affected_rows;
    }
}

// Delete (wrapped)
if (!function_exists('delete')) {
    function delete($table, $id)
    {
        global $conn;
        $sql = "DELETE FROM $table WHERE id=?";
        $stmt = executeQuery($sql, ['id' => $id]);
        return $stmt->affected_rows;
    }
}

// Get published posts (wrapped)
if (!function_exists('getPublishedPosts')) {
    function getPublishedPosts()
    {
        global $conn;
        $sql = "SELECT p.*, u.username FROM posts AS p JOIN users AS u ON p.user_id=u.id WHERE p.published=?";
        $stmt = executeQuery($sql, ['published' => 1]);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

// Get posts by topic ID (wrapped)
if (!function_exists('getPostsByTopicId')) {
    function getPostsByTopicId($topic_id)
    {
        global $conn;
        $sql = "SELECT p.*, u.username FROM posts AS p JOIN users AS u ON p.user_id=u.id WHERE p.published=? AND topic_id=?";
        $stmt = executeQuery($sql, ['published' => 1, 'topic_id' => $topic_id]);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

// Search posts (wrapped)
if (!function_exists('searchPosts')) {
    function searchPosts($term)
    {
        $match = '%' . $term . '%';
        global $conn;
        $sql = "SELECT p.*, u.username 
                FROM posts AS p 
                JOIN users AS u ON p.user_id=u.id 
                WHERE p.published=? AND (p.title LIKE ? OR p.body LIKE ?)";

        $stmt = executeQuery($sql, ['published' => 1, 'title' => $match, 'body' => $match]);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>

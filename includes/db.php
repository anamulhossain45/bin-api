<?php
class db
{
    /*
This is a free-to-use php pdo wrapper. You can use this in any of your php custom project.
@author: Merajul Islam
@email: merajbd7@gmail.com
@github: https://github.com/CraftSofts/php-pdo-wrapper
    */
    public $conn;
    public $last_id;
    public $last_message;

    // costructor functio which will connect to database and store connection to the var named $conn
    public function __construct()
    {
        // get all arguments to the array $args
        $args = func_get_args();

        // connect to the database based on quantity of arguments passed
        if (func_num_args() == 4) {
            // all parameters was passed
            try {
                $conn = new PDO('mysql:host=' . $args[0] . ';dbname=' . $args[3], $args[1], $args[2]);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn = $conn;
            } catch (Exception $e) {
                $this->last_message = "Error connecting to db: " . $e->getMessage();
            }
        } elseif (func_num_args() == 3) {
            // host wasn't passed, set localhost default host
            try {
                $conn = new PDO('mysql:host=localhost;dbname=' . $args[2], $args[0], $args[1]);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn = $conn;
            } catch (Exception $e) {
                $this->last_message = "Error connecting to db: " . $e->getMessage();
            }
        } elseif (func_num_args() == 1) {
            // only connection object was passed
            $this->conn = $args[0];
        } else {
            // no arguments was passed
            $this->last_message = "Error no connection parameters are provided";
        }
    }

    // create a database named $name
    public function create_db($name)
    {
        try {
            $this->conn->exec("CREATE DATABASE $name");
            return true;
        } catch (PDOException $e) {
            $this->last_message = "Could not create the database: " . $e->getMessage();
            return false;
        }
    }

    // delete a database named $name
    public function delete_db($name)
    {
        try {
            $this->conn->exec("DROP DATABASE $name");
            return true;
        } catch (PDOException $e) {
            $this->last_message = "Could not drop the database: " . $e->getMessage();
            return false;
        }
    }

    // create a table named $name and columns from $data array
    public function create_table($name, $data)
    {
        $cols = implode(",", $data);
        try {
            $this->conn->exec("CREATE TABLE $name ($cols)");
            return true;
        } catch (PDOException $e) {
            $this->last_message = "Could not create the table: " . $e->getMessage();
            return false;
        }
    }

    // alter a table $name with $sql
    public function alter_table($name, $sql)
    {
        try {
            $this->conn->exec("ALTER TABLE $name $sql");
            return true;
        } catch (PDOException $e) {
            $this->last_message = "Could not alter the table: " . $e->getMessage();
            return false;
        }
    }

    // delete a database named $name
    public function delete_table($name)
    {
        try {
            $this->conn->exec("DROP table $name");
            return true;
        } catch (PDOException $e) {
            $this->last_message = "Could not drop the table: " . $e->getMessage();
            return false;
        }
    }

    // insert a row
    public function insert_row($table, $columns, $data)
    {
        // get column names from $columns and convert to comma seperated string
        $cols = implode(',', $columns);
        $params = '';
        $values = '';
        $array = array_values($columns);
        // get binding parameters
        for ($i = 0; $i < count($data); $i++) {
            if ($i == count($data) - 1) {
                $params .= ':' . $array[$i];
                $values .= '\'' . $data[$i] . '\'';
            } else {
                $params .= ':' . $array[$i] . ',';
                $values .= '\'' . $data[$i] . '\', ';
            }
        }
        try {
            $sql = "INSERT INTO $table ($cols) VALUES ($params)";
            $stmt = $this->conn->prepare($sql);
            for ($i = 0; $i < count($data); $i++) {
                // bind parameters
                $stmt->bindParam(':' . $array[$i] . '', $data[$i]);
            }
            // execute
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            $this->last_message = "Could not insert row: " . $e->getMessage();
            return false;
        }
        // store insert id to the var $last_id
        $this->last_id = $this->conn->lastInsertId();
    }

    // delete a row from $table
    public function delete_row()
    {
        $args = func_get_args();

        if (func_num_args() == 3) {
            try {
                $stmt = $this->conn->prepare("DELETE FROM $args[0] WHERE $args[1] = ?");
                $stmt->execute([$args[2]]);
                return true;
            } catch (PDOException $e) {
                $this->last_message = "Could not delete row: " . $e->getMessage();
                return false;
            }
        } elseif (func_num_args() == 5) {
            try {
                $stmt = $this->conn->prepare("DELETE FROM $args[0] WHERE $args[1] = ? AND $args[3] = ?");
                $stmt->execute([$args[2], $args[4]]);
                return true;
            } catch (PDOException $e) {
                $this->last_message = "Could not delete row: " . $e->getMessage();
                return false;
            }
        }
    }

    // update a row
    public function update_row()
    {
        $args = func_get_args();

        $cols = implode(',', $args[3]);
        $params = '';
        $values = '';
        $array = array_values($args[3]);
        for ($i = 0; $i < count($args[4]); $i++) {
            if ($i == count($args[4]) - 1) {
                $params .= ':' . $array[$i];
                $values .= '' . $array[$i] . ' = :' . $array[$i] . '';
            } else {
                $params .= ':' . $array[$i] . ',';
                $values .= '' . $array[$i] . ' = :' . $array[$i] . ', ';
            }
        }

        if (func_num_args() == 5) {
            try {
                $sql = "UPDATE $args[0] SET $values WHERE $args[1] = '$args[2]'";
                $stmt = $this->conn->prepare($sql);
                for ($i = 0; $i < count($args[4]); $i++) {
                    $stmt->bindParam(':' . $array[$i], $args[4][$i]);
                }
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                $this->last_message = "Could not update row: " . $e->getMessage();
                return false;
            }
        } elseif (func_num_args() == 7) {
            try {
                $sql = "UPDATE $args[0] SET $values WHERE $args[1]='$args[2]' AND $args[5]='$args[6]'";
                $stmt = $this->conn->prepare($sql);
                for ($i = 0; $i < count($args[4]); $i++) {
                    $stmt->bindParam(':' . $array[$i], $args[4][$i], PDO::PARAM_STR);
                }
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                $this->last_message = "Could not update row: " . $e->getMessage();
                return false;
            }
        }
    }

    // get a single row
    public function select_row()
    {
        $args = func_get_args();

        if (func_num_args() == 3) {
            try {
                $stmt = $this->conn->prepare("SELECT * FROM $args[0] WHERE $args[1] = ?");
                $stmt->execute([$args[2]]);
                $arr = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$arr) {
                    return false;
                } else {
                    return $arr;
                }
            } catch (PDOException $e) {
                $this->last_message = "Could not select row: " . $e->getMessage();
                return false;
            }
        } elseif (func_num_args() == 5) {
            try {
                $stmt = $this->conn->prepare("SELECT * FROM $args[0] WHERE $args[1] = ? AND $args[3] = ?");
                $stmt->execute([$args[2], $args[4]]);
                $arr = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$arr) {
                    return false;
                } else {
                    return $arr;
                }
            } catch (PDOException $e) {
                $this->last_message = "Could not select row: " . $e->getMessage();
                return false;
            }
        }
    }

    // get multiple rows
    public function select_rows()
    {
        $args = func_get_args();
        $option = '';
        $opt_stmt1 = '';
        $opt_stmt2 = '';

        if (func_num_args() == 1) {
            try {
                $stmt = $this->conn->prepare("SELECT * FROM $args[0]");
                $stmt->execute();
                $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!$arr) {
                    return false;
                } else {
                    return $arr;
                }
            } catch (PDOException $e) {
                $this->last_message = "Could not select rows: " . $e->getMessage();
                return false;
            }
        } elseif (func_num_args() == 2) {
            $options = $args[1];
            if (is_array($options)) {
                if (count($options) == 2) {
                    if (is_int($options[0]) && is_int($options[1])) {
                        $option = " LIMIT $options[0], $options[1]";
                    } else {
                        $option = " ORDER BY $options[0] $options[1]";
                    }
                } elseif (count($options) == 4) {
                    $option = " ORDER BY $options[0] $options[1] LIMIT $options[2], $options[3]";
                }
            }
            try {
                $stmt = $this->conn->prepare("SELECT * FROM $args[0]$option");
                $stmt->execute();
                $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!$arr) {
                    return false;
                } else {
                    return $arr;
                }
            } catch (PDOException $e) {
                $this->last_message = "Could not select rows: " . $e->getMessage();
                return false;
            }
        } elseif (func_num_args() == 3) {
            try {
                $stmt = $this->conn->prepare("SELECT * FROM $args[0] WHERE $args[1]=?");
                $stmt->execute([$args[2]]);
                $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!$arr) {
                    return false;
                } else {
                    return $arr;
                }
            } catch (PDOException $e) {
                $this->last_message = "Could not select rows: " . $e->getMessage();
                return false;
            }
        } elseif (func_num_args() == 4) {
            $options = $args[3];
            if (is_array($options)) {
                if (count($options) == 2) {
                    if (is_int($options[0]) && is_int($options[1])) {
                        $option = " LIMIT $options[0], $options[1]";
                    } else {
                        $option = " ORDER BY $options[0] $options[1]";
                    }
                } elseif (count($options) == 4) {
                    $option = " ORDER BY $options[0] $options[1] LIMIT $options[2], $options[3]";
                }
                $stmt = $this->conn->prepare("SELECT * FROM $args[0] WHERE $args[1]=?$option");
            } else {
                $stmt = $this->conn->prepare("SELECT * FROM $args[0] WHERE $args[1] $args[3] ?");
            }
            try {
                $stmt->execute([$args[2]]);
                $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!$arr) {
                    return false;
                } else {
                    return $arr;
                }
            } catch (PDOException $e) {
                $this->last_message = "Could not select rows: " . $e->getMessage();
                return false;
            }
        } elseif (func_num_args() == 5) {
            $options = $args[4];
            if (is_array($options)) {
                if (count($options) == 2) {
                    if (is_int($options[0]) && is_int($options[1])) {
                        $option = " LIMIT $options[0], $options[1]";
                    } else {
                        $option = " ORDER BY $options[0] $options[1]";
                    }
                } elseif (count($options) == 4) {
                    $option = " ORDER BY $options[0] $options[1] LIMIT $options[2], $options[3]";
                }
                try {
                    $stmt = $this->conn->prepare("SELECT * FROM $args[0] WHERE $args[1] $args[3] ?$option");
                    $stmt->execute([$args[2]]);
                    $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (!$arr) {
                        return false;
                    } else {
                        return $arr;
                    }
                } catch (PDOException $e) {
                    $this->last_message = "Could not select rows: " . $e->getMessage();
                    return false;
                }
            } else {
                try {
                    $stmt = $this->conn->prepare("SELECT * FROM $args[0] WHERE $args[1]=? AND $args[3]=?");
                    $stmt->execute([$args[2], $args[4]]);
                    $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (!$arr) {
                        return false;
                    } else {
                        return $arr;
                    }
                } catch (PDOException $e) {
                    $this->last_message = "Could not select rows: " . $e->getMessage();
                    return false;
                }
            }
        } elseif (func_num_args() == 6) {
            $options = $args[5];
            if (is_array($options)) {
                if (count($options) == 2) {
                    if (is_int($options[0]) && is_int($options[1])) {
                        $option = " LIMIT $options[0], $options[1]";
                    } else {
                        $option = " ORDER BY $options[0] $options[1]";
                    }
                } elseif (count($options) == 4) {
                    $option = " ORDER BY $options[0] $options[1] LIMIT $options[2], $options[3]";
                }
                $stmt = $this->conn->prepare("SELECT * FROM $args[0] WHERE $args[1]=? AND $args[3] = ?$option");
            } else {
                $stmt = $this->conn->prepare("SELECT * FROM $args[0] WHERE $args[1]=? AND $args[3] $args[5] ?");
            }
            try {
                $stmt->execute([$args[2], $args[4]]);
                $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!$arr) {
                    return false;
                } else {
                    return $arr;
                }
            } catch (PDOException $e) {
                $this->last_message = "Could not select rows: " . $e->getMessage();
                return false;
            }
        }
    }

    // search for rows with a specified keyword provided
    public function search_rows($table, $query, $parameter, $options='')
    {
        $option = '';
        if (is_array($options)) {
            if (count($options) == 2) {
                if (is_int($options[0]) && is_int($options[1])) {
                    $option = " LIMIT $options[0], $options[1]";
                } else {
                    $option = " ORDER BY $options[0] $options[1]";
                }
            } elseif (count($options) == 4) {
                if (is_int($options[0]) && is_int($options[1]) && is_int($options[2]) && is_int($options[3])) {
                    $option = " AND price BETWEEN $options[2] AND $options[3] LIMIT $options[0], $options[1]";
                } else {
                    $option = " ORDER BY $options[0] $options[1] LIMIT $options[2], $options[3]";
                }
            } elseif (count($options) == 6) {
                $option = " AND price BETWEEN $options[4] AND $options[5] ORDER BY $options[0] $options[1] LIMIT $options[2], $options[3]";
            }
        }
        try {
            $stmt = $this->conn->prepare("SELECT * FROM $table WHERE $query LIKE ?$option");
            $stmt->execute(["%{$parameter}%"]);
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$arr) {
                return false;
            } else {
                return $arr;
            }
        } catch (PDOException $e) {
            $this->last_message = "Could not select rows: " . $e->getMessage();
            echo "SELECT * FROM $table WHERE $query LIKE ?$option";
            return false;
        }
    }

    // search for rows with a specified keyword provided
    public function count_search_rows()
    {
        $args = func_get_args();
        $count = 0;
        if (func_num_args() == 3) {
            try {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $args[0] WHERE $args[1] LIKE ?");
                $stmt->execute(["%{$args[2]}%"]);
                $count = $stmt->fetch(PDO::FETCH_COLUMN);
            } catch (PDOException $e) {
                $this->last_message = "Could not select rows: " . $e->getMessage();
            }
        } if (func_num_args() == 4) {
            $options = $args[3];
            if (is_int($options[0]) && is_int($options[1])) {
                $option = " AND price BETWEEN $options[0] AND $options[1]";
            }
            try {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $args[0] WHERE $args[1] LIKE ?$option");
                $stmt->execute(["%{$args[2]}%"]);
                $count = $stmt->fetch(PDO::FETCH_COLUMN);
            } catch (PDOException $e) {
                $this->last_message = "Could not select rows: " . $e->getMessage();
            }
        } elseif (func_num_args() == 5) {
            try {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $args[0] WHERE $args[1] LIKE ? AND $args[3] LIKE ?");
                $stmt->execute(["%{$args[2]}%", "%{$args[4]}%"]);
                $count = $stmt->fetch(PDO::FETCH_COLUMN);
            } catch (PDOException $e) {
                $this->last_message = "Could not select rows: " . $e->getMessage();
            }
        } 
        return (int) $count;
    }

    public function count_rows()
    {
        $args = func_get_args();
        $count = 0;
        if (func_num_args() == 1) {
            try {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $args[0]");
                $stmt->execute();
                $count = $stmt->fetch(PDO::FETCH_COLUMN);
            } catch (PDOException $e) {
                $this->last_message = "Could not count rows: " . $e->getMessage();
            }
        } elseif (func_num_args() == 3) {
            try {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $args[0] WHERE $args[1]=?");
                $stmt->execute([$args[2]]);
                $count = $stmt->fetch(PDO::FETCH_COLUMN);
            } catch (PDOException $e) {
                $this->last_message = "Could not select rows: " . $e->getMessage();
            }
        } elseif (func_num_args() == 4) {
            try {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $args[0] WHERE $args[1]$args[3]?");
                $stmt->execute([$args[2]]);
                $count = $stmt->fetch(PDO::FETCH_COLUMN);
            } catch (PDOException $e) {
                $this->last_message = "Could not select rows: " . $e->getMessage();
            }
        } elseif (func_num_args() == 5) {
            try {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $args[0] WHERE $args[1]=? AND $args[3]=?");
                $stmt->execute([$args[2], $args[4]]);
                $count = $stmt->fetch(PDO::FETCH_COLUMN);
            } catch (PDOException $e) {
                $this->last_message = "Could not select rows: " . $e->getMessage();
            }
        }
        return (int) $count;
    }

    public function is_exists()
    {
        $args = func_get_args();
        $total = 0;
        if (func_num_args() == 3) {
            $stmt = $this->conn->prepare("SELECT * FROM $args[0] WHERE $args[1]=?");
            $stmt->execute([$args[2]]);
            $total = $stmt->rowCount();
        } elseif (func_num_args() == 5) {
            $stmt = $this->conn->prepare("SELECT * FROM $args[0] WHERE $args[1]=? AND $args[3]=?");
            $stmt->execute([$args[2], $args[4]]);
            $total = $stmt->rowCount();
        }
        try {
            if ($total > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            $this->last_message = "Could not select rows: " . $e->getMessage();
            return false;
        }
    }

}

<?php
    require "db/db.php";

    function validate_form_dish($name, $price, $thumbnail, $thumbnail_tmp_name) {
        $validation_errors = [];

        if(!$name) {
            $validation_errors[] = "Name is required.";
        }

        if(!$price) {
            $validation_errors[] = "Price is required.";
        }

        if(!$thumbnail && !$thumbnail_tmp_name) {
            $validation_errors[] = "The thumbnail of the blog is required.";
        }

        if(!empty($name) && strlen($name < 20)) {
            $validation_errors[] = "The name of the dish must have atleast 5 characters.";
        }

        return $validation_errors;
    }

    function save_dish($name, $user_id, $price, $thumbnail, $id=null) {
        global $conn;
        $flag = false;

        $date_created = date("Y-m-d H:i:s");
        
        if($id == null) 
            $query = "INSERT INTO `dishes` (`user_id`, `name`,  `price`, `thumbnail`, `created_at`) VALUES ('".$conn->real_escape_string($user_id)."', '".$conn->real_escape_string($name)."', '".$conn->real_escape_string($price)."', '".$conn->real_escape_string($thumbnail)."', '".$date_created."')";
        else {
            $last_updated = date('Y-m-d H:i:s');
            $query = "UPDATE `dishes as `d` SET `d`.`name` = '".$conn->real_escape_string($name)."', `d`.`price` = '".$conn->real_escape_string($price)."', `d`.`thumbnail` = '".$conn->real_escape_string($thumbnail)."', `b`.`updated_at` = '".$last_updated."' WHERE `b`.`id` = '".$conn->real_escape_string($id)."'";
        } 

        if ($conn->query($query)) {
            $flag = true;
        }

        return $flag;    
    }

    function get_all_dishes($filter = [], $pagination = []) {
        global $conn;
        $dishes= [];
       
        $query = "SELECT `d`.`name` as `name`, `d`.`thumbnail`, `u`.`name` as `created_by`, `d`.`created_at`, `d`.`updated_at` 
                FROM `dishes` as `d` 
                INNER JOIN `users` as `u` ON `u`.`id` = `d`.`user_id`";

        if(!empty($filter)) {
            $query .= " WHERE ";

            $conditions = [];
            $search = [];
            
            foreach ($filter as $column => $value) {
                if (is_array($value) && $column === "search") {
                    foreach ($value[0] as $column_search => $value_search) {
                        $search[] = "{$value_search} LIKE '%{$conn->real_escape_string($value[1])}%'";
                    }
                } else {
                    $conditions[] = "$column = '{$conn->real_escape_string($value)}'";
                }
            }

            $where_clause = [];
            
            if(!empty($conditions))
                $where_clause[] = implode(' AND ', $conditions);

            if(!empty($search))
                $where_clause[] = implode(' OR ', $search);

            $query .= implode(' AND ', $where_clause);

        }
        
        $query .= " ORDER BY `d`.`id` DESC";

        if (!empty($pagination)) {
            $query .= " LIMIT {$pagination['offset']}, {$pagination['total_records_per_page']}";
        }

        if ($result = $conn->query($query)) {
            $dishes = $result->fetch_all(MYSQLI_ASSOC);
        }

        return $dishes;
    }  

    function get_total_number_records() {
        global $conn;
        $total = 0;

        $query = "SELECT * FROM `dishes`";

        if ($result = $conn->query($query)) {
            $total = $result->num_rows;
        }
    
        return $total;
    }
?>
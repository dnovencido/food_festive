<?php
    function pagination_config($total_records, $page_no, $total_records_per_page = 10) {
    
        // Calculate pagination details
        $offset = ($page_no - 1) * $total_records_per_page;
        $previous_page = $page_no - 1;
        $next_page = $page_no + 1;
        $total_no_of_pages = ceil($total_records / $total_records_per_page);
    
        return [
            'total_records' => $total_records,
            'total_no_of_pages' => $total_no_of_pages,
            'offset' => $offset,
            'previous_page' => $previous_page,
            'next_page' => $next_page,
            'current_page' => $page_no
        ];
    }
    ?>
?>
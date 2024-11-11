<?php
    include "session.php"; 
    include "require_login.php";
    include "models/dish.php";

   
    if (isset($_GET['page_no'])) {
        $page_no = $_GET['page_no'];
    } else {
        $page_no = 1;
    }

    $dishes = [];
    
    $total_records_per_page = 10; // defines the number of records (blogs) to be displayed on each page
    $offset = ($page_no - 1) * $total_records_per_page; //determine the starting point (index) of records to be fetched
	$previous_page = $page_no - 1; // previous page
	$next_page = $page_no + 1; // next page

	$total_records = get_total_number_records();
    $total_no_of_pages = ceil($total_records / $total_records_per_page); // determines the number of pages to be displayed

    if (isset($_SESSION['id'])) {
        $dishes = get_all_dishes($filter = [], $pagination = ['offset'=> $offset, $total_records_per_page = 10, 'total_records_per_page' => $total_records_per_page]);
    }
?>
<?php include "layouts/_header.php"; ?>
    <?php include "layouts/_navigation.php"; ?>
    <main class="account">
        <section id="dish" class="container">
            <?php if(isset($_SESSION['flash_message'])) { ?>
                <?php include "layouts/_messages.php"; ?>
            <?php } ?>
            <div id="account-container">               
                <?php include "layouts/_account-navigation.php" ?>
                <div id="account-preview">
                    <div id="account-preview-heading">
                        <h3>Dishes</h3>
                        <a href="/employee/dish/new" class="btn btn-sm btn-rounded">Add New Dish</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Thumbnail</th>
                                <th>Name</th>
                                <th>Last Updated</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($dishes)) { ?>
                                <?php foreach($dishes as $row) { ?>
                                    <tr>
                                        <td>
                                            <div class="thumbnail-image">
                                                <img src="data:image/jpeg;base64,<?= base64_encode($row['thumbnail']) ?>" alt="" class="featured-image">
                                            </div>
                                        </td>
                                        <td><?= $row['name'] ?></td>
                                        <td><?= date('M d, Y @ h:i a', strtotime($row['created_at'])) ?></td>
                                        <td><?= !empty($row['updated_at']) ? date('M d, Y @ h:i a', strtotime($row['updated_at'])) : '-' ?></td>
                                        <td class="action-buttons">
                                            <a href="#">Edit</a>
                                            <a href="#" class="btn-delete" data-id="">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <td colspan="5">No dishes to be displayed...</td>
                            <?php } ?>
                        </tbody>
                    </table>  
                </div>
            </div>
        </section>
    </main>
<?php include "layouts/_footer.php"; ?>
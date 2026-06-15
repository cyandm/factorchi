<?php
if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}

include( FCI_VIEW_PATH . 'header.php' );
?>
    <div class="view-1 container">
        <?php
            echo FCI_View_Render::product_label_view_1($this->get_order_id(), $this->type);
        ?>
    </div>
<?php
include( FCI_VIEW_PATH . 'footer.php' );
<?php
if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}

include( FCI_VIEW_PATH . 'header.php' );

?>
    <div class="view-1 container">
        <div class="inner">
			<?php echo  $data['title']; ?>
			<?php echo $data['sender']; ?>
            <?php echo $data['phone']; ?>
            <?php echo $data['email']; ?>
            <?php echo $data['url']; ?>
			<?php echo $data['postcode']; ?>
			<?php echo $data['economical']; ?>
			<?php echo $data['reg']; ?>
			<?php echo $data['order_id_html']; ?>
			<?php echo $data['print_date']; ?>
			<?php echo $data['transmission_date']; ?>
			<?php echo $data['barcode']; ?>
        </div>
    </div>
<?php
include( FCI_VIEW_PATH . 'footer.php' );
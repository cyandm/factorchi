<?php

if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}

include( FCI_VIEW_PATH . 'header.php' );
?>
    <div class="classic container">
        <div class="inner">
			<?php echo $data['recipient']; ?>
			<?php echo $data['full_name']; ?>
            <?php echo  $data['r_phone']; ?>
            <?php echo  $data['r_email']; ?>
			<?php echo  $data['r_postcode']; ?>
			<?php echo $data['order_date']; ?>
			<?php echo $data['trans_id']; ?>
			<?php echo $data['national_id']; ?>
			<?php echo $data['user_meta']; ?>
			<?php echo $data['shipping']; ?>
			<?php echo $data['pay_method']; ?>
			<?php echo $data['order_meta']; ?>
        </div>
    </div>
<?php
include( FCI_VIEW_PATH . 'footer.php' );
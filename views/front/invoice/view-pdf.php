<?php
if (!defined('ABSPATH')) {
	exit(__('No Access!', 'fci'));
}


$labels = new FCI_Labels();
$data = FCI_View_Render::header($this->get_order_id(), $this->type, $this->get_font_family() , true);

?>


<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<title><?php echo $this->type; ?>(<?php echo $data['shop_order_id']; ?>)</title>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">

</head>

<style>
	body {

		text-align: right;
	}

	.shop-detail {
		width: 100%;
		border: 1px solid #000;
		margin-bottom: 14px;
		padding: 14px;
	}
	.customer-detail{
		width: 100%;
		border: 1px solid #000;
		margin-bottom: 14px;
		padding: 8x;
	}
	.total-table{
		width: 40%;
		border: 1px solid #000;
	}
	.products-table{
		border: 1px solid #000;
		width: 100%;
	}
	.total-table td, .total-table th , .products-table td,.products-table th {
		border: 1px solid #e7e7e7;
	}

	.shop-detail tfoot tr {
		background-color: #e7e7e7;
	}

	table th {
		padding: 8;
		font-size: 16px;
	}

	th.total , th.final, th.shipping{
		background-color: #e7e7e7;
	}
	table thead tr {
		background-color: #e7e7e7;
		padding: 8px;
	}

	table td {
		padding: 10px;
	}

	.shop-detail tbody td {
		padding: 8px;
	}

	.order-note{
		margin-top: 8px;
	}
	.profit-wrapper{
		margin-top: 5px;
		text-align: left;
	}
</style>

<body class="<?php echo $this->append_body_class(); ?>">

	<div class="view-1 container">
		<?php if (
			$data['title'] || $data['print_date'] ||
			$data['transmission_date'] || $data['url'] || $data['email'] || $data['phone']  || $data['order_id_html'] ||
			$data['barcode'] || $data['sender'] || $data['postcode'] || $data['economical'] || $data['reg']
		) : ?>


			<table class="shop-detail fci-res-table fci-fix-table">
				<tbody>
					<tr>
						<?php $colspan = 0; ?>
						<?php if ($data['title'] || $data['url'] ||  $data['email'] || $data['phone']) : ?>
							<td style="width: 30%">
								<?php echo $data['title']; ?>
								<?php echo $data['url']; ?>
								<?php echo $data['email']; ?>
								<?php echo $data['phone']; ?>
							</td>
							<?php $colspan++; ?>
						<?php endif; ?>
						<?php if (strlen($data['logo']) > 42) : ?>
							<td style="width: 40%"><?php echo $data['logo']; ?></td>
							<?php $colspan++; ?>
						<?php endif; ?>
						<?php if (
							$data['print_date'] ||  $data['transmission_date'] ||  $data['order_id_html']
							|| $data['barcode']
						) : ?>
							<td style="width: 30%;text-align: left;">
								<?php echo $data['print_date']; ?>
								<?php echo $data['transmission_date']; ?>
								<?php echo $data['order_id_html']; ?>

								<?php //echo $data['barcode']; 
								?>
								<?php $colspan++; ?>
							</td>
						<?php endif; ?>
					</tr>
				</tbody>


				<?php if ($data['sender'] || $data['postcode'] || $data['economical'] || $data['reg']) : ?>
					<tfoot>
						<tr>

							<?php if ($data['sender']) : ?>
								<td style="width: 40%;">
									<?php echo $data['sender']; ?>
								</td>
							<?php endif; ?>

							<?php if ($data['postcode']) : ?>
								<td>
									<?php echo $data['postcode']; ?>
								</td>

							<?php endif; ?>


							<?php if ($data['economical']) : ?>
								<td>
									<?php echo $data['economical']; ?>
								</td>
							<?php endif; ?>


							<?php if ($data['reg']) : ?>
								<td>
									<?php echo $data['reg']; ?>
								</td>
							<?php endif; ?>

						</tr>
					</tfoot>
				<?php endif; ?>

			</table>



		<?php endif; ?>
		<?php if (
			$data['recipient'] ||  $data['full_name'] || $data['r_postcode'] || $data['r_phone'] ||
			$data['r_email'] || $data['order_date'] || $data['pay_method'] ||
			$data['trans_id'] || $data['national_id'] || $data['shipping'] || $data['user_meta'] ||
			$data['order_meta']
		) : ?>
			<table class="customer-detail">

				<tr>
					<?php if ($data['recipient']) : ?>
						<td style="width: 30%;">
							<?php echo  $data['recipient']; ?>
						</td>
					<?php endif; ?>


					<?php if ($data['full_name']) : ?>
						<td style="width: 17%;">
							<?php echo $data['full_name']; ?>
						</td>
					<?php endif; ?>

					<?php if ($data['r_postcode']) : ?>
						<td>
							<?php echo $data['r_postcode']; ?>
						</td>
					<?php endif; ?>

					<?php if ($data['r_phone']) : ?>
						<td>
							<?php echo $data['r_phone']; ?>
						</td>
					<?php endif; ?>

					<?php if ($data['r_email']) : ?>
						<td>
							<?php echo  $data['r_email']; ?>
						</td>
					<?php endif; ?>

					<?php if ($data['order_date']) : ?>
						<td>
							<?php echo $data['order_date']; ?>
						</td>
					<?php endif; ?>

					<?php if ($data['pay_method']) : ?>
						<td>
							<?php echo $data['pay_method']; ?>
						</td>

					<?php endif; ?>

					<?php if ($data['trans_id']) : ?>
						<td>
							<?php echo  $data['trans_id']; ?>
						</td>
					<?php endif; ?>

					<?php if ($data['national_id']) : ?>
						<td>
							<?php echo $data['national_id']; ?>

						</td>
					<?php endif; ?>

					<?php if ($data['shipping']) : ?>
						<td>
							<?php echo $data['shipping']; ?>

						</td>
					<?php endif; ?>

					<?php if ($data['user_meta']) : ?>
						<td>
							<?php echo $data['user_meta']; ?>

						</td>
					<?php endif; ?>

					<?php if ($data['order_meta']) : ?>
						<td>
							<?php echo $data['order_meta']; ?>

						</td>
					<?php endif; ?>
				</tr>


			</table>
		<?php endif; ?>
		<?php if ($data['products_table']) : ?>
			<?php echo $data['products_table']; ?>
		<?php endif; ?>
		<?php if ($data['total_table']) : ?>
			<?php echo $data['total_table']; ?>
		<?php endif; ?>
		<?php if ($data['customer_note']) : ?>
			<?php echo $data['customer_note']; ?>
		<?php endif; ?>
		<?php if ($data['order_note']) : ?>
			<?php echo $data['order_note']; ?>
		<?php endif; ?>
		<?php if ($data['shop_sign']  || $data['customer_sign']) : ?>
			<table class="fci-sign-table fci-res-table fci-fix-table">
				<tbody>
					<tr>
						<?php if ($data['shop_sign']) : ?>
							<td><?php echo $data['shop_sign']; ?></td>
						<?php endif; ?>
						<?php if ($data['customer_sign']) : ?>
							<td><?php echo $data['customer_sign']; ?></td>
						<?php endif; ?>
					</tr>
				</tbody>
			</table>
		<?php endif; ?>

		<?php if(strlen($data['watermark']) > 51): ?>
			<?php echo $data['watermark']; ?>
		<?php endif; ?>
		
	</div>
</body>
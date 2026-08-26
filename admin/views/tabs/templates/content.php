<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var array<string, mixed> $s */
/** @var string $partials */

$title = __('تاریخ‌ها', 'factorchi');
$description = __('نمایش یا مخفی‌کردن تاریخ‌ها روی اسناد.', 'factorchi');
include $partials . 'card-section.php';

$name = 'show_print_date';
$label = __('تاریخ چاپ', 'factorchi');
$checked = ($s['show_print_date'] ?? 'yes') === 'yes';
$description = '';
include $partials . 'field-toggle.php';

$name = 'show_order_date';
$label = __('تاریخ سفارش', 'factorchi');
$checked = ($s['show_order_date'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_date_time';
$label = __('نمایش ساعت همراه تاریخ', 'factorchi');
$checked = ($s['show_date_time'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';

$title = __('جدول اقلام', 'factorchi');
$description = __('تصویر، شماره ردیف و فیلتر نام محصول.', 'factorchi');
include $partials . 'card-section.php';

$name = 'show_product_image';
$label = __('تصویر محصول', 'factorchi');
$checked = ($s['show_product_image'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_product_row_number';
$label = __('شماره ردیف اقلام', 'factorchi');
$checked = ($s['show_product_row_number'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'filter_product_name_codes';
$label = __('حذف کد از انتهای نام محصول', 'factorchi');
$description = __('مثلاً G00927.', 'factorchi');
$checked = ($s['filter_product_name_codes'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';

$title = __('بارکد', 'factorchi');
$description = __('موقعیت و متن بارکد در فاکتور / پیش‌فاکتور.', 'factorchi');
include $partials . 'card-section.php';

$name = 'show_barcode_top';
$label = __('بالای فاکتور (کنار لوگو)', 'factorchi');
$checked = ($s['show_barcode_top'] ?? 'no') === 'yes';
$description = '';
include $partials . 'field-toggle.php';

$name = 'show_barcode_top_text';
$label = __('متن زیر بارکد بالا', 'factorchi');
$checked = ($s['show_barcode_top_text'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_barcode_under_title';
$label = __('زیر عنوان فروشگاه', 'factorchi');
$checked = ($s['show_barcode_under_title'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_barcode_under_title_text';
$label = __('متن زیر بارکد عنوان', 'factorchi');
$checked = ($s['show_barcode_under_title_text'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_barcode_bottom';
$label = __('پایین فاکتور', 'factorchi');
$checked = ($s['show_barcode_bottom'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_barcode_bottom_text';
$label = __('متن زیر بارکد پایین', 'factorchi');
$checked = ($s['show_barcode_bottom_text'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';

$title = __('متغیرها و ویژگی‌های محصول', 'factorchi');
$description = __('در همه اسناد بعد از نام محصول اعمال می‌شود.', 'factorchi');
include $partials . 'card-section.php';

$name = 'product_attrs_mode';
$label = __('نمایش متغیرها', 'factorchi');
$value = (string) ($s['product_attrs_mode'] ?? 'all');
$options = [
    'all'      => __('همه', 'factorchi'),
    'selected' => __('فقط موارد انتخاب‌شده', 'factorchi'),
    'none'     => __('هیچکدام', 'factorchi'),
];
$description = '';
include $partials . 'field-select.php';

$name = 'product_attrs_show_label';
$label = __('نام ویژگی قبل از مقدار', 'factorchi');
$description = __('مثلاً «سایز: XL».', 'factorchi');
$checked = ($s['product_attrs_show_label'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$attr_options = [];
if (function_exists('wc_get_attribute_taxonomies')) {
    foreach (wc_get_attribute_taxonomies() as $attribute_tax) {
        $attr_key = 'pa_' . $attribute_tax->attribute_name;
        $attr_options[$attr_key] = $attribute_tax->attribute_label !== ''
            ? $attribute_tax->attribute_label
            : $attribute_tax->attribute_name;
    }
}

if ($attr_options !== []) {
    $name = 'product_attrs_selected';
    $label = __('ویژگی‌های انتخابی', 'factorchi');
    $options = $attr_options;
    $selected = array_map('strval', (array) ($s['product_attrs_selected'] ?? []));
    $description = __('فقط در حالت «فقط موارد انتخاب‌شده»؛ ترتیب همان لیست است.', 'factorchi');
    include $partials . 'field-checkbox-group.php';
    unset($selected, $options);
} else {
    echo '<p class="fc-description">' . esc_html__('هیچ ویژگی سراسری در ووکامرس تعریف نشده است.', 'factorchi') . '</p>';
}

include $partials . 'card-section-end.php';

$title = __('اطلاعات خریدار', 'factorchi');
$description = __('فیلدهای بلوک خریدار در فاکتور و پیش‌فاکتور.', 'factorchi');
include $partials . 'card-section.php';

$name = 'customer_address_source';
$label = __('منبع آدرس مشتری', 'factorchi');
$value = (string) ($s['customer_address_source'] ?? 'woocommerce');
$options = [
    'woocommerce' => __('پیروی از ووکامرس', 'factorchi'),
    'shipping'    => __('همیشه حمل و نقل', 'factorchi'),
    'billing'     => __('همیشه صورت‌حساب', 'factorchi'),
];
$description = '';
include $partials . 'field-select.php';

$name = 'show_payment_method';
$label = __('روش پرداخت', 'factorchi');
$checked = ($s['show_payment_method'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_shipping_method';
$label = __('روش ارسال', 'factorchi');
$checked = ($s['show_shipping_method'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_transaction_id';
$label = __('شماره تراکنش', 'factorchi');
$checked = ($s['show_transaction_id'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_customer_note_buyer';
$label = __('یادداشت در اطلاعات خریدار', 'factorchi');
$checked = ($s['show_customer_note_buyer'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_customer_note_footer';
$label = __('یادداشت در پایین فاکتور', 'factorchi');
$checked = ($s['show_customer_note_footer'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'address_enter_spacing_below';
$label = __('فاصله خالی زیر آدرس', 'factorchi');
$checked = ($s['address_enter_spacing_below'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';

$title = __('برگه جداشدنی', 'factorchi');
$description = __('نوار برش پایین فاکتور.', 'factorchi');
include $partials . 'card-section.php';

$name = 'show_tearoff';
$label = __('نمایش برگه جداشدنی', 'factorchi');
$checked = ($s['show_tearoff'] ?? 'yes') === 'yes';
$description = '';
include $partials . 'field-toggle.php';

$name = 'show_tearoff_recipient';
$label = __('اطلاعات گیرنده', 'factorchi');
$checked = ($s['show_tearoff_recipient'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_tearoff_payment';
$label = __('روش پرداخت', 'factorchi');
$checked = ($s['show_tearoff_payment'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'use_payzito_gateway_tracking';
$label = __('کد پیگیری از پی‌زیتو', 'factorchi');
$checked = ($s['use_payzito_gateway_tracking'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_tearoff_tracking';
$label = __('کدپیگیری درگاه', 'factorchi');
$checked = ($s['show_tearoff_tracking'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_tearoff_order_date';
$label = __('تاریخ سفارش', 'factorchi');
$checked = ($s['show_tearoff_order_date'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_tearoff_order_id';
$label = __('شناسه سفارش', 'factorchi');
$checked = ($s['show_tearoff_order_id'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_tearoff_customer_note';
$label = __('یادداشت مشتری', 'factorchi');
$checked = ($s['show_tearoff_customer_note'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';

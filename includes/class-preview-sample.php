<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sample document data for admin-only template preview.
 */
class Factorchi_Preview_Sample
{
    public const NONCE_ACTION = 'factorchi_template_preview';

    /**
     * @return array<string, mixed>
     */
    public static function get_data(string $type = 'invoice'): array
    {
        $shop_name = (string) factorchi_get_setting('shop_name', get_bloginfo('name'));
        $shop_url  = (string) factorchi_get_setting('shop_url', home_url('/'));
        $shop_phone = (string) factorchi_get_setting('shop_phone', '021-12345678');
        $shop_addr  = (string) factorchi_get_setting('shop_address', __('تهران، خیابان نمونه، پلاک ۱', 'factorchi'));
        $shop_post  = (string) factorchi_get_setting('shop_postcode', '1234567890');
        $logo_url   = (string) factorchi_get_setting('shop_logo', '');
        $sample_id  = 1001;
        $date       = Factorchi_Helper::date_format(time());

        $logo = '';
        if ($logo_url !== '') {
            $logo = '<p class="shop-logo"><img class="shop-logo" src="' . esc_url($logo_url) . '" alt="" /></p>';
        }

        $p = static function (string $class, string $label, string $value): string {
            $value = trim($value);
            if ($value === '') {
                return '';
            }
            if ($label === '') {
                return '<p class="' . esc_attr($class) . '">' . esc_html($value) . '</p>';
            }
            return '<p class="' . esc_attr($class) . '"><strong>' . esc_html($label) . '</strong> ' . esc_html($value) . '</p>';
        };

        $products = '<table class="factorchi-products-table fci-fix-table products-table fci-border-table">'
            . '<thead><tr>'
            . '<th>' . esc_html__('محصول', 'factorchi') . '</th>'
            . '<th>' . esc_html__('تعداد', 'factorchi') . '</th>'
            . '<th>' . esc_html__('مبلغ', 'factorchi') . '</th>'
            . '</tr></thead><tbody>'
            . '<tr><td>' . esc_html__('محصول نمونه ۱، قرمز - PRD-1001', 'factorchi') . '</td><td class="fc-cell-qty">۲</td><td class="fc-cell-price">' . Factorchi_Helper::format_price(250000) . '</td></tr>'
            . '<tr><td>' . esc_html__('محصول نمونه ۲ - PRD-1002', 'factorchi') . '</td><td class="fc-cell-qty">۱</td><td class="fc-cell-price">' . Factorchi_Helper::format_price(180000) . '</td></tr>'
            . '</tbody></table>';

        $total = '<table class="factorchi-total-table fci-fix-table total-table">'
            . '<tr><th>' . esc_html__('جمع جزء', 'factorchi') . '</th><td>' . Factorchi_Helper::format_price(680000) . '</td></tr>'
            . '<tr><th>' . esc_html__('هزینه ارسال', 'factorchi') . '</th><td>' . Factorchi_Helper::format_price(45000) . '</td></tr>'
            . '<tr><th class="final">' . esc_html__('جمع کل', 'factorchi') . '</th><td class="final">' . Factorchi_Helper::format_price(725000) . '</td></tr>'
            . '</table>';

        $title_prefix = $type === 'pre-invoice'
            ? __('پیش‌فاکتور نمونه', 'factorchi')
            : __('پیش‌نمایش فاکتور', 'factorchi');

        $data = [
            'title'               => '<p class="shop-title">' . esc_html($shop_name !== '' ? $shop_name : $title_prefix) . '</p>',
            'url'                 => $p('shop-url', __('سایت:', 'factorchi'), $shop_url),
            'email'               => '',
            'phone'               => $p('shop-phone', __('تلفن:', 'factorchi'), $shop_phone !== '' ? $shop_phone : '021-12345678'),
            'logo'                => $logo,
            'print_date'          => factorchi_get_setting('show_print_date', 'yes') === 'yes'
                ? '<span class="print-date section print-date"><span class="title">' . esc_html__('تاریخ چاپ:', 'factorchi') . '</span> ' . esc_html($date) . '</span>'
                : '',
            'transmission_date'   => factorchi_get_setting('show_order_date', 'yes') === 'yes'
                ? '<span class="transmission-date section transmission-date"><span class="title">' . esc_html__('تاریخ سفارش:', 'factorchi') . '</span> ' . esc_html($date) . '</span>'
                : '',
            'order_id_html'       => '<span class="order-id section order-id"><span class="title">' . esc_html__('شناسه سفارش:', 'factorchi') . '</span> ' . esc_html((string) $sample_id) . '</span>',
            'barcode'             => Factorchi_Barcode::html((string) $sample_id, 80),
            'sender'              => $p('shop-address', __('آدرس:', 'factorchi'), $shop_addr),
            'postcode'            => $p('shop-postcode', __('کدپستی:', 'factorchi'), $shop_post !== '' ? $shop_post : '1234567890'),
            'economical'          => $p('shop-economical', __('شماره اقتصادی:', 'factorchi'), '123456789012'),
            'reg'                 => $p('shop-reg', __('شماره ثبت:', 'factorchi'), '12345'),
            'recipient'           => $p('customer-address', __('آدرس:', 'factorchi'), __('تهران، خیابان آزادی، کوچه نمونه، پلاک ۱۲', 'factorchi')),
            'full_name'           => $p('customer-name', __('نام:', 'factorchi'), __('علی رضایی', 'factorchi')),
            'r_postcode'          => $p('customer-postcode', __('کدپستی:', 'factorchi'), '9876543210'),
            'r_phone'             => $p('customer-phone', __('تلفن:', 'factorchi'), '09121234567'),
            'r_email'             => '',
            'order_date'          => factorchi_get_setting('show_order_date', 'yes') === 'yes'
                ? $p('order-date', __('تاریخ سفارش:', 'factorchi'), $date)
                : '',
            'pay_method'          => factorchi_get_setting('show_payment_method', 'yes') === 'yes'
                ? $p('payment-method', __('روش پرداخت:', 'factorchi'), __('پرداخت آنلاین', 'factorchi'))
                : '',
            'trans_id'            => factorchi_get_setting('show_transaction_id', 'yes') === 'yes'
                ? $p('transaction-id', __('شناسه تراکنش:', 'factorchi'), 'TRX-SAMPLE-1001')
                : '',
            'national_id'         => $p('national-id', __('کد ملی:', 'factorchi'), '0012345678'),
            'shipping'            => factorchi_get_setting('show_shipping_method', 'yes') === 'yes'
                ? $p('shipping-method', __('روش ارسال:', 'factorchi'), __('پست پیشتاز', 'factorchi'))
                : '',
            'user_meta'           => '',
            'order_meta'          => '',
            'delivery_date'       => '',
            'customer_note'       => '<table class="factorchi-note-table fci-form-table customer-note"><thead><tr><th>' . esc_html__('یادداشت', 'factorchi') . '</th></tr></thead><tbody><tr><td>' . esc_html__('لطفاً قبل از ظهر ارسال شود.', 'factorchi') . '</td></tr></tbody></table>',
            'order_note'          => '',
            'shop_sign'           => '',
            'customer_sign'       => '',
            'deliver_date'        => '',
            'deliver_time'        => '',
            'watermark'           => '',
            'products_table'      => $products,
            'products_list'       => '<ul><li>' . esc_html__('محصول نمونه ۱، قرمز - PRD-1001 × ۲', 'factorchi') . '</li><li>' . esc_html__('محصول نمونه ۲ - PRD-1002 × ۱', 'factorchi') . '</li></ul>',
            'total_table'         => $total,
            'postbarcode'         => '',
            'shop_order_id'       => $sample_id,
            'shop_barcode_render' => Factorchi_Barcode::html((string) $sample_id, 60),
            'tearoff'             => Factorchi_View_Render::build_tearoff_html_from_values([
                'payment'    => __('پرداخت آنلاین', 'factorchi'),
                'tracking'   => '111111111',
                'order_date' => $date,
                'order_id'   => (string) $sample_id,
            ]),
        ];

        if ($type === 'post-label') {
            $data['title'] = $p('shop-name', __('نام:', 'factorchi'), $shop_name !== '' ? $shop_name : __('فروشگاه نمونه', 'factorchi'));
            $data['phone'] = $p('shop-phone', __('تلفن:', 'factorchi'), $shop_phone !== '' ? $shop_phone : '021-12345678');
            $data['url'] = '';
            $data['barcode'] = '';
            $data['postbarcode'] = '';
            $data['products_table'] = '';
            $data['total_table'] = '';
            $data['tearoff'] = '';
        }

        return $data;
    }

    public static function render(string $type, string $view): void
    {
        $invoice_view = new Factorchi_Invoice_View($type, 0, $view);
        $invoice_view->set_preview(true);
        $invoice_view->render();
    }
}

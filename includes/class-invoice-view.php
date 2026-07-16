<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Invoice_View
{
    public string $type = 'invoice';
    /** @var int|array<int, int>|string */
    public $order_id = 0;
    private string $view = 'modern';
    private bool $check_email = false;
    private bool $is_preview = false;

    /**
     * @param int|array<int, int>|string $order_id
     */
    public function __construct(string $type, $order_id, string $view = '')
    {
        $this->type     = sanitize_key($type);
        $this->order_id = $order_id;
        $resolved = $view !== '' ? sanitize_file_name($view) : $this->resolve_default_view($type);

        if (in_array($type, ['invoice', 'pre-invoice'], true)) {
            $this->view = Factorchi_Settings::normalize_invoice_view($resolved);
        } elseif ($type === 'post-label') {
            $this->view = Factorchi_Settings::normalize_post_label_view($resolved);
        } else {
            $this->view = $resolved;
        }
    }

    public function set_preview(bool $value): void
    {
        $this->is_preview = $value;
    }

    public function is_preview(): bool
    {
        return $this->is_preview;
    }

    private function resolve_default_view(string $type): string
    {
        $map = [
            'invoice'        => 'invoice_default_view',
            'pre-invoice'    => 'pre_invoice_view',
            'post-label'     => 'post_label_view',
        ];

        $key     = $map[$type] ?? 'invoice_default_view';
        $default = $type === 'post-label' ? 'modern-a4' : (in_array($type, ['invoice', 'pre-invoice'], true) ? 'modern' : 'view-1');

        return (string) factorchi_get_setting($key, $default);
    }

    /**
     * Style slug used for template/CSS files (modern|classic for invoices and post labels).
     */
    public function get_view_style(): string
    {
        if ($this->type === 'post-label') {
            return Factorchi_Settings::post_label_style_from_view($this->view);
        }

        return $this->view;
    }

    public function get_order_id(): int
    {
        if (is_array($this->order_id)) {
            return (int) reset($this->order_id);
        }
        return (int) $this->order_id;
    }

    /**
     * @return array<int, int>
     */
    public function get_order_ids(): array
    {
        if (is_array($this->order_id)) {
            return array_values(array_filter(array_map('intval', $this->order_id)));
        }

        $id = (int) $this->order_id;
        return $id > 0 ? [$id] : [];
    }

    public function is_batch(): bool
    {
        return is_array($this->order_id) && count($this->order_id) > 1;
    }

    public function get_print_size(): string
    {
        $size = isset($_GET['print-size']) ? sanitize_key(wp_unslash($_GET['print-size'])) : '';
        if (in_array($size, ['a4', 'a5'], true)) {
            return $size;
        }

        if ($this->type === 'post-label') {
            return Factorchi_Settings::post_label_size_from_view($this->view);
        }

        $size = (string) factorchi_get_setting('print_page_size', 'a4');

        return in_array($size, ['a4', 'a5'], true) ? $size : 'a4';
    }

    public function get_font_family(): string
    {
        return Factorchi_Font_Registry::resolve_css_family(
            (string) factorchi_get_setting('font_family', 'peyda')
        );
    }

    public function get_font_setting_key(): string
    {
        return Factorchi_Font_Registry::normalize_key(
            (string) factorchi_get_setting('font_family', 'peyda')
        );
    }

    public function get_font_size(): int
    {
        $map = [
            'invoice'        => 'font_size_invoice',
            'pre-invoice'    => 'font_size_pre_invoice',
            'post-label'     => 'font_size_post_label',
            'shop-label'     => 'font_size_label',
            'customer-label' => 'font_size_label',
            'product-label'  => 'font_size_label',
            'mini-label'     => 'font_size_label',
        ];

        $defaults = [
            'font_size_invoice'     => 14,
            'font_size_pre_invoice' => 14,
            'font_size_post_label'  => 12,
            'font_size_label'       => 12,
        ];

        $key     = $map[$this->type] ?? 'font_size_invoice';
        $default = $defaults[$key] ?? 14;

        return max(10, min(24, (int) factorchi_get_setting($key, $default)));
    }

    public function append_document_variables(): string
    {
        $font_family = $this->get_font_family();
        $font_size   = $this->get_font_size();
        $font_sm     = max(8, (int) round($font_size * 0.85));
        $font_lg     = min(28, (int) round($font_size * 1.15));
        $line_height = max(1.3, min(2.2, round(($font_size / 14) * 1.6, 2)));
        $product_img = max(24, min(200, (int) factorchi_get_setting('product_image_size', 70)));
        $radius      = factorchi_get_setting('enable_border_radius', 'yes') === 'yes' ? '10px' : '0';
        $print_size  = $this->get_print_size();
        $section_gap = max(0, min(60, (int) factorchi_get_setting('section_gap_' . $print_size, 12)));

        return '<style>'
            . ':root{'
            . '--fc-font-family:' . esc_attr($font_family) . ',Tahoma,sans-serif;'
            . '--fc-font-size:' . (int) $font_size . 'px;'
            . '--fc-font-size-sm:' . (int) $font_sm . 'px;'
            . '--fc-font-size-lg:' . (int) $font_lg . 'px;'
            . '--fc-line-height:' . esc_attr((string) $line_height) . ';'
            . '--fc-product-image-size:' . (int) $product_img . 'px;'
            . '--fc-section-gap:' . (int) $section_gap . 'px;'
            . '--fc-brand:#5f1b29;'
            . '--fc-brand-light:#bd3854;'
            . '--fc-accent:#ed3819;'
            . '--fc-border:#e2e8f0;'
            . '--fc-border-strong:#cbd5e1;'
            . '--fc-surface:#ffffff;'
            . '--fc-muted:#64748b;'
            . '--fc-soft:#f8fafc;'
            . '--fc-radius:' . $radius . ';'
            . '--fc-shadow:0 4px 24px rgba(15,23,42,0.08);'
            . '}'
            . 'body.factorchi-document,body.factorchi-print,body.factorchi-document.post-label .modern,body.factorchi-document.post-label .classic,body.factorchi-document.post-label .modern .inner,body.factorchi-document.post-label .classic .inner{'
            . 'font-family:var(--fc-font-family)!important;'
            . 'font-size:var(--fc-font-size);'
            . 'line-height:var(--fc-line-height);'
            . 'direction:rtl;'
            . '}'
            . '</style>';
    }

    private function append_document_base_stylesheet(): string
    {
        return '<link rel="stylesheet" href="' . esc_url(FACTORCHI_CSS_URL . 'document-base.css') . '?ver=' . esc_attr(FACTORCHI_VERSION) . '" />';
    }

    public function get_check_email(): bool
    {
        return $this->check_email;
    }

    public function set_check_email(bool $value): void
    {
        $this->check_email = $value;
    }

    public function append_styles(): string
    {
        $css_file = $this->resolve_css_file();
        $html     = '';

        $html .= Factorchi_Font_Registry::append_stylesheet_link($this->get_font_setting_key());
        $html .= '<link rel="stylesheet" href="' . esc_url(FACTORCHI_CSS_URL . 'fontawesome.min.css') . '?ver=' . esc_attr(FACTORCHI_VERSION) . '" />';

        if ($css_file !== '') {
            $html .= '<link rel="stylesheet" href="' . esc_url(FACTORCHI_CSS_URL . 'views/' . $css_file) . '?ver=' . esc_attr(FACTORCHI_VERSION) . '" />';
        }

        $html .= $this->append_document_base_stylesheet();
        $html .= $this->append_document_variables();

        return $html;
    }

    public function append_print_styles(): string
    {
        return $this->append_styles()
            . '<link rel="stylesheet" href="' . esc_url(FACTORCHI_CSS_URL . 'print-layout.css') . '?ver=' . esc_attr(FACTORCHI_VERSION) . '" />';
    }

    public function get_print_body_classes(): string
    {
        $size = $this->get_print_size();

        $classes = [
            'factorchi-print',
            'factorchi-document',
            'rtl',
            esc_attr($this->type),
            'fc-print-' . $size,
            'fc-print-1up',
        ];

        if ($this->type === 'pre-invoice') {
            $classes[] = 'invoice';
        }

        return implode(' ', $classes);
    }

    private function resolve_css_file(): string
    {
        if ($this->type === 'invoice' || $this->type === 'pre-invoice') {
            $map = [
                'modern'  => 'invoice-modern.css',
                'classic' => 'invoice-classic.css',
            ];

            return $map[$this->view] ?? 'invoice-modern.css';
        }

        if ($this->type === 'post-label') {
            $style = $this->get_view_style();
            $map   = [
                'modern'  => 'post-label-modern.css',
                'classic' => 'post-label-classic.css',
            ];

            return $map[$style] ?? 'post-label-modern.css';
        }

        $label_map = [
            'shop-label'     => 'shop-label-1.css',
            'customer-label' => 'customer-label-1.css',
            'product-label'  => 'product-label-1.css',
            'mini-label'     => 'shop-label-1.css',
        ];

        return $label_map[$this->type] ?? '';
    }

    public function append_body_class(): string
    {
        $type  = esc_attr($this->type);
        $view  = esc_attr($this->view);
        $extra = $this->type === 'pre-invoice' ? ' invoice' : '';
        $size  = ' fc-print-' . esc_attr($this->get_print_size());
        $print = '';

        if ($this->type === 'post-label') {
            $print = ' ' . esc_attr($this->get_view_style());
        }

        return $type . $extra . ' rtl ' . $view . ' factorchi-document' . $print . $size;
    }

    public function render(): void
    {
        if ($this->should_use_print_batch()) {
            $this->render_print_batch();
            return;
        }

        $template = $this->resolve_template_path();
        if (!file_exists($template)) {
            wp_die(esc_html__('قالب فاکتور یافت نشد.', 'factorchi'));
        }

        if ($this->type !== 'pre-invoice') {
            $data = Factorchi_View_Render::build_data($this->get_order_id(), $this->type);
        }

        include $template;
    }

    private function should_use_print_batch(): bool
    {
        if (!in_array($this->type, ['invoice', 'post-label'], true)) {
            return false;
        }

        return $this->is_batch();
    }

    public function render_print_batch(): void
    {
        $order_ids = $this->get_order_ids();
        if ($order_ids === []) {
            wp_die(esc_html__('شناسه سفارش نامعتبر است.', 'factorchi'));
        }

        if (!file_exists($this->resolve_template_path_public())) {
            wp_die(esc_html__('قالب فاکتور یافت نشد.', 'factorchi'));
        }

        $print_size = $this->get_print_size();

        include FACTORCHI_VIEW_PATH . 'print/shell.php';
    }

    public function resolve_template_path_public(): string
    {
        return $this->resolve_template_path();
    }

    private function resolve_template_path(): string
    {
        $dir_map = [
            'invoice'         => 'invoice',
            'pre-invoice'     => 'invoice',
            'post-label'      => 'post-label',
            'shop-label'      => 'shop-label',
            'customer-label'  => 'customer-label',
            'product-label'   => 'product-label',
            'mini-label'      => 'mini-label',
        ];

        $subdir = $dir_map[$this->type] ?? 'invoice';
        $view   = $this->type === 'post-label' ? $this->get_view_style() : $this->view;

        return FACTORCHI_VIEW_PATH . 'front/' . $subdir . '/' . $view . '.php';
    }
}

<?php

if (!defined('ABSPATH')) {
    exit;
}

abstract class Factorchi_Notify_Channel_Base implements Factorchi_Notify_Channel
{
    protected string $id;
    protected string $setting_key;

    public function __construct(string $id, string $setting_key)
    {
        $this->id          = $id;
        $this->setting_key = $setting_key;
    }

    public function get_id(): string
    {
        return $this->id;
    }

    public function is_enabled(): bool
    {
        return factorchi_get_setting($this->setting_key, 'no') === 'yes';
    }

    /**
     * @param array<string, string> $context
     */
    protected function render_template(string $template, array $context): string
    {
        $search  = [];
        $replace = [];
        foreach ($context as $key => $value) {
            $search[]  = '{' . $key . '}';
            $replace[] = $value;
        }

        return str_replace($search, $replace, $template);
    }
}

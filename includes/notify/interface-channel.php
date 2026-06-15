<?php

if (!defined('ABSPATH')) {
    exit;
}

interface Factorchi_Notify_Channel
{
    public function get_id(): string;

    public function is_enabled(): bool;

    /**
     * @param array<string, string> $context
     */
    public function send(int $order_id, array $context): bool;
}

<div class="header bread-crumb clearfix">

    <span class="title"><?= esc_html($labels->get_label('orders')) ?></span>
    <span class="date"><?= FCI_Helper::date_format(time()) ?></span>

    <?php if ($state && $state != 'all'): ?>
        <?php foreach ($wc_countries as $country) { ?>
            <?php
            $st = FCI_Helper::get_state_item($country, $state);
            if (!$st) {
                continue;
            }
            ?>
            <span class="state"> <?= $st ?> </span>
        <?php } ?>

    <?php endif; ?>


    <?php if ($start): ?>
        <span class="start-date"> <?= $start ?> </span>
    <?php endif; ?>
    <?php if ($end): ?>
        <span class="end-date"> <?= $end ?> </span>
    <?php endif; ?>


</div>

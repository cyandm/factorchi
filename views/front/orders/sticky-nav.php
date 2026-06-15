<nav class="filter-nav-sticky">

    <form style="display: flex; align-items:center;" method="get">
        <input type="hidden" name="action" value="<?php echo $this->action; ?>">
        <input type="hidden" name="type" value="<?php echo $this->type; ?>">



        <select name="state">
            <option value=""><?php _e('Filter by state', 'fci'); ?></option>
            <option value="all" <?php selected($state, 'all'); ?>><?php _e('All', 'fci'); ?></option>
            <?php if ($wc_countries): ?>
                <?php foreach ($wc_countries as $country): ?>
                    <?php $states = FCI_Helper::get_state_lists($country); ?>
                    <?php if ($states): ?>
                        <optgroup label="<?php echo FCI_Helper::get_country($country); ?>">
                            <?php foreach ($states as $code => $name): ?>
                                <option value="<?php echo $code; ?>" <?php selected($state, $code); ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>


        <select name="status">
            <option value=""><?php _e('Filter status', 'fci'); ?></option>
            <option value="all" <?php selected($status, 'all'); ?>><?php _e('All', 'fci'); ?></option>
            <?php foreach ($statuses as $code => $name): ?>
                <option value="<?php echo str_replace('wc-', '', $code); ?>" <?php selected($status, str_replace('wc-', '', $code)); ?>><?php echo $name; ?></option>
            <?php endforeach; ?>
        </select>

        <input type="text" class="datepicker-field" name="start-date" value="<?php echo esc_attr($start); ?>" placeholder="<?php _e('Start date', 'fci'); ?>" autocomplete="off">
        <input type="text" class="datepicker-field" name="end-date" value="<?php echo esc_attr($end); ?>" placeholder="<?php _e('End date', 'fci'); ?>" autocomplete="off">


        <label style="display: flex; align-items:center; margin:0 10px;">
            <input <?php echo $_GET['fci-currency'] == 'true' ? 'checked' : '' ?> style="min-width: unset;" name="fci-currency" value="true" type="checkbox" id="currency-conversion">
            <div style="display: flex; flex-direction:column;">
                <span style="font-weight: 900; color:white; font-size:14px; line-height:8px; padding-top:12px;"><?php _e('Currency conversion', 'fci'); ?></span>
                <span style="color: #ccc; font-size:10px;"><?php _e('from Rial to Toman and vice versa', 'fci'); ?></span>
            </div>

        </label>


        <input type="submit" value="<?php echo __('Filter', 'fci'); ?>">

    </form>

    <div class="print">
        <!-- <a href="#" style="background: green; color: #fff;" onclick="location.href = document.referrer; return false;" class="button">
            
        </a> -->
        <a href="#" class="button" style="background: green; color: #fff;" onclick="print()">
            <?php echo __('print', 'fci') ?>
        </a>
        <a href="#" onclick="location.href = document.referrer; return false;" class="button">
            <?php echo __('back', 'fci') ?>
        </a>
    </div>

</nav>
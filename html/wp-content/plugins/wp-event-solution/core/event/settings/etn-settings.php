<?php
defined('ABSPATH') || exit;
$settings =  \Etn\Core\Event\Settings\Settings::instance()->get_settings_option();
// print_r($settings);
$sample_date = strtotime('January 15 ' . date('Y'));
$date_options         = [
    '0' => date('Y-m-d', $sample_date),
    '1' => date('n/j/Y', $sample_date),
    '2' => date('m/d/Y', $sample_date),
    '3' => date('j/n/Y', $sample_date),
    '4' => date('d/m/Y', $sample_date),
    '5' => date('n-j-Y', $sample_date),
    '6' => date('m-d-Y', $sample_date),
    '7' => date('j-n-Y', $sample_date),
    '8' => date('d-m-Y', $sample_date),
    '9' => date('Y.m.d', $sample_date),
    '10' => date('m.d.Y', $sample_date),
    '11' => date('d.m.Y', $sample_date),
];
$checked_exclude_from_search =  (isset($settings['etn_include_from_search']) ? 'checked' : '');
$checked_purchase_login_required =  (isset($settings['etn_purchase_login_required']) ? 'checked' : '');
$checked_hide_time_from_details =  (isset($settings['etn_hide_time_from_details']) ? 'checked' : '');
$checked_hide_location_from_details =  (isset($settings['etn_hide_location_from_details']) ? 'checked' : '');
$checked_hide_seats_from_details =  (isset($settings['etn_hide_seats_from_details']) ? 'checked' : '');
$checked_hide_organizers_from_details =  (isset($settings['etn_hide_organizers_from_details']) ? 'checked' : '');
$checked_hide_schedule_from_details =  (isset($settings['etn_hide_schedule_from_details']) ? 'checked' : '');
$checked_hide_address_from_details =  (isset($settings['etn_hide_address_from_details']) ? 'checked' : '');
$etn_price_label =  (isset($settings['etn_price_label']) ? $settings['etn_price_label'] : '');
?>
<div class="wrap  etn-settings-dashboard">
    <h2 class="etn-main-title"><i class="dashicons dashicons-admin-generic"></i><?php esc_html_e('Eventin Settings', 'eventin'); ?></h2>

    <div class="etn-settings-tab">
        <div class="nav-tab-wrapper">
            <a href="#etn-general_options" class="nav-tab"><?php echo esc_html__('General', 'eventin'); ?></a>
            <a href="#etn-details_options" class="nav-tab"><?php echo esc_html__('Details', 'eventin'); ?></a>
            <a href="#etn-hooks_options" class="etnshortcode-nav nav-tab"><?php echo esc_html__('Shortcode', 'eventin'); ?></a>
        </div>
    </div>
    <div class="etn-admin-container stuffbox">
        <div class="attr-card-body etn-admin-container--body">
            <form action="" method="post" class="form-group etn-admin-input-text etn-settings-from">

                <!-- General Tab -->
                <div class="etn-settings-section" id="etn-general_options">
                    <div class="etn-settings-single-section">
                        <div class="etn-recaptcha-settings-wrapper">
                            <div class="etn-recaptcha-settings">

                                <div class="attr-form-group etn-label-item">
                                    <div class="etn-label">
                                        <label class="etn-setting-label" for="captcha-method"><?php esc_html_e('Select Date Format', 'eventin'); ?></label>
                                        <div class="etn-desc"> <?php esc_html_e('Select date format to display. For instance 15-01-2020.', 'eventin'); ?> </div>
                                    </div>
                                    <div class="etn-meta">
                                        <select id="date_format" name="date_format" class="etn-setting-input attr-form-control">
                                            <option value=''> --- </option>
                                            <?php
                                            foreach ($date_options as $key => $date_option) {
                                                echo "<option" . esc_html(selected($settings['date_format'], $key, false)) . " value='" . esc_attr($key) . "'> " . esc_html($date_option) . " </option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="attr-form-group etn-label-item">
                                    <div class="etn-label">
                                        <label for="captcha-method"><?php esc_html_e('Select Time Format:', 'eventin'); ?></label>
                                        <div class="etn-desc"> <?php esc_html_e('Select time format. For instance 12h.', 'eventin'); ?> </div>
                                    </div>
                                    <div class="etn-meta">
                                        <select id="time_format" name="time_format" class="etn-setting-input attr-form-control">
                                            <option value=''> --- </option>
                                            <?php
                                            echo '<option value="24"' . esc_html(selected($settings['time_format'], '24', false)) . '>' . __('24h', 'eventin') . '</option>';
                                            echo '<option value="12"' . esc_html(selected($settings['time_format'], '12', false)) . '>' . __('12h', 'eventin') . '</option>';
                                            ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="attr-form-group etn-label-item">
                                    <div class="etn-label">
                                        <label>
                                            <?php esc_html_e('Include Into Search', 'eventin'); ?>
                                        </label>
                                        <div class="etn-desc"> <?php esc_html_e('Do you want to include into search ?', 'eventin'); ?> </div>
                                    </div>
                                    <div class="etn-meta">
                                        <input id='checked_exclude_from_search' type="checkbox" <?php echo esc_html($checked_exclude_from_search); ?> class="etn-admin-control-input etn-form-modalinput-paypal_sandbox" name="etn_include_from_search" />
                                        <label for="checked_exclude_from_search" class="etn_switch_button_label"></label>
                                    </div>
                                </div>
                                <div class="attr-form-group etn-label-item">
                                    <div class="etn-label">
                                        <label>
                                            <?php esc_html_e('Require login', 'eventin'); ?>
                                        </label>
                                        <div class="etn-desc"> <?php esc_html_e('Require login to purchase event ticket', 'eventin'); ?> </div>
                                    </div>
                                    <div class="etn-meta">
                                        <input id="checked_purchase_login_required" type="checkbox" <?php echo esc_html($checked_purchase_login_required); ?> class="etn-admin-control-input etn-form-modalinput-paypal_sandbox" name="etn_purchase_login_required" />
                                        <label for="checked_purchase_login_required" class="etn_switch_button_label"></label>
                                    </div>
                                </div>

                                <div class="attr-form-group etn-label-item">
                                    <div class="etn-label">
                                        <label><?php esc_html_e('Price label text', 'eventin'); ?> </label>
                                        <div class="etn-desc"> <?php esc_html_e('Place price label', 'eventin'); ?> </div>

                                    </div>
                                    <div class="etn-meta">
                                        <input type="text" name="etn_price_label" value="<?php echo esc_attr(isset($etn_price_label) ? $etn_price_label : ''); ?>" class="etn-setting-input attr-form-control etn-recaptcha-secret-key" placeholder="<?php esc_html_e('Label Text', 'eventin'); ?>">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- ./End General Tab -->

                <!-- Description Tab -->
                <div class="etn-settings-section" id="etn-details_options">

                    <div class="etn-settings-single-section">
                        <div class="etn-recaptcha-settings-wrapper">
                            <div class="etn-recaptcha-settings">

                                <div class="attr-form-group etn-label-item">
                                    <div class="etn-label">
                                        <label>
                                            <?php esc_html_e('Hide time', 'eventin'); ?>
                                        </label>
                                        <div class="etn-desc"> <?php esc_html_e('Hide time from event details.', 'eventin'); ?> </div>
                                    </div>
                                    <div class="etn-meta">
                                        <input id="checked_hide_time_from_details" type="checkbox" <?php echo esc_html($checked_hide_time_from_details); ?> class="etn-admin-control-input etn-form-modalinput-paypal_sandbox" name="etn_hide_time_from_details" />
                                        <label for="checked_hide_time_from_details" class="etn_switch_button_label"></label>
                                    </div>
                                </div>

                                <div class="attr-form-group etn-label-item">
                                    <div class="etn-label">
                                        <label>
                                            <?php esc_html_e('Hide localtion', 'eventin'); ?>
                                        </label>
                                        <div class="etn-desc"> <?php esc_html_e('Hide localtion from event details.', 'eventin'); ?> </div>
                                    </div>
                                    <div class="etn-meta">
                                        <input id="checked_hide_location_from_details" type="checkbox" <?php echo esc_html($checked_hide_location_from_details); ?> class="etn-admin-control-input etn-form-modalinput-paypal_sandbox" name="etn_hide_location_from_details" />
                                        <label for="checked_hide_location_from_details" class="etn_switch_button_label"></label>
                                    </div>
                                </div>

                                <div class="attr-form-group etn-label-item">
                                    <div class="etn-label">
                                        <label>
                                            <?php esc_html_e('Hide total seats', 'eventin'); ?>
                                        </label>
                                        <div class="etn-desc"> <?php esc_html_e('Hide total seats from event details.', 'eventin'); ?> </div>
                                    </div>
                                    <div class="etn-meta">
                                        <input id="checked_hide_seats_from_details" type="checkbox" <?php echo esc_html($checked_hide_seats_from_details); ?> class="etn-admin-control-input etn-form-modalinput-paypal_sandbox" name="etn_hide_seats_from_details" />
                                        <label for="checked_hide_seats_from_details" class="etn_switch_button_label"></label>
                                    </div>
                                </div>


                                <div class="attr-form-group etn-label-item">
                                    <div class="etn-label">
                                        <label>
                                            <?php esc_html_e('Hide organizers', 'eventin'); ?>
                                        </label>
                                        <div class="etn-desc"> <?php esc_html_e('Hide organizers from event details.', 'eventin'); ?> </div>
                                    </div>
                                    <div class="etn-meta">
                                        <input id="checked_hide_organizers_from_details" type="checkbox" <?php echo esc_html($checked_hide_organizers_from_details); ?> class="etn-admin-control-input etn-form-modalinput-paypal_sandbox" name="etn_hide_organizers_from_details" />
                                        <label for="checked_hide_organizers_from_details" class="etn_switch_button_label"></label>
                                    </div>
                                </div>

                                <div class="attr-form-group etn-label-item">
                                    <div class="etn-label">
                                        <label>
                                            <?php esc_html_e('Hide schedule details', 'eventin'); ?>
                                        </label>
                                        <div class="etn-desc"> <?php esc_html_e('Hide schedule details from event details.', 'eventin'); ?> </div>
                                    </div>
                                    <div class="etn-meta">
                                        <input id="checked_hide_schedule_from_details" type="checkbox" <?php echo esc_html($checked_hide_schedule_from_details); ?> class="etn-admin-control-input etn-form-modalinput-paypal_sandbox" name="etn_hide_schedule_from_details" />
                                        <label for="checked_hide_schedule_from_details" class="etn_switch_button_label"></label>
                                    </div>
                                </div>


                                <div class="attr-form-group etn-label-item">
                                    <div class="etn-label">
                                        <label>
                                            <?php esc_html_e('Hide full address', 'eventin'); ?>
                                        </label>
                                        <div class="etn-desc"> <?php esc_html_e('Hide full address from event details.', 'eventin'); ?> </div>
                                    </div>
                                    <div class="etn-meta">
                                        <input id="checked_hide_address_from_details" type="checkbox" <?php echo esc_html($checked_hide_address_from_details); ?> class="etn-admin-control-input etn-form-modalinput-paypal_sandbox" name="etn_hide_address_from_details" />
                                        <label for="checked_hide_address_from_details" class="etn_switch_button_label"></label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- ./End Description Tab -->

                <div class="etn-settings-section etn-shortcode-settings" id="etn-hooks_options">
                    <div class="etn-settings-single-section">
                        <div class="attr-form-group etn-label-item">
                            <div class="etn-label">
                                <label><?php esc_html_e('Event List', 'eventin'); ?> </label>
                            </div>
                            <div class="etn-meta">
                                <input type="text" disabled name="etn_price_label" value="[events]" class="etn-setting-input attr-form-control etn-recaptcha-secret-key" placeholder="<?php esc_html_e('Label Text', 'eventin'); ?>">
                                <div class="etn-desc"> <?php esc_html_e("You can use [events limit='1'/]", 'eventin'); ?> </div>
                            </div>
                        </div>
                        <div class="attr-form-group etn-label-item">
                            <div class="etn-label">
                                <label><?php esc_html_e('Speaker List', 'eventin'); ?> </label>
                            </div>
                            <div class="etn-meta">
                                <input type="text" disabled name="etn_price_label" value=" [speakers cat_id='19']" class="etn-setting-input attr-form-control etn-recaptcha-secret-key" placeholder="<?php esc_html_e('Label Text', 'eventin'); ?>">
                                <div class="etn-desc"> <?php esc_html_e("Use id of the category which is being used as speaker. You can use [speakers term_id='19' limit='3'/]", 'eventin'); ?> </div>
                            </div>
                        </div>
                        <div class="attr-form-group etn-label-item">
                            <div class="etn-label">
                                <label><?php esc_html_e('Schedule List', 'eventin'); ?> </label>
                            </div>
                            <div class="etn-meta">
                                <input type="text" disabled name="etn_price_label" value="[schedules ids ='18,19'/]" class="etn-setting-input attr-form-control etn-recaptcha-secret-key" placeholder="<?php esc_html_e('Label Text', 'eventin'); ?>">
                                <div class="etn-desc"> <?php esc_html_e("Use comma seperated schedule id's that you want to show in schedule list", 'eventin'); ?> </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="etn_settings_page_action" value="save">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'eventin'); ?>">

                <?php wp_nonce_field('etn-settings-page', 'etn-settings-page'); ?>
            </form>
        </div>
    </div>
</div>
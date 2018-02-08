<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    //--- general settings -----------------------------------------------------------------------------------
    $settings->add(new admin_setting_configtext('eventials/client_id',
        'Client Id',
        'Your application key - Available at <a href="https://www.eventials.com/oauth-clients/" target="_blank">https://www.eventials.com/oauth-clients/</a> ', ''));
    $settings->add(new admin_setting_configtext('eventials/client_secret',
        'Client Secret',
        'Your application secret - Available at <a href="https://www.eventials.com/oauth-clients/" target="_blank">https://www.eventials.com/oauth-clients/</a> ', ''));
}

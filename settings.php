<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    //--- general settings -----------------------------------------------------------------------------------
    $settings->add(new admin_setting_configtext('eventials/client_id',
        'Client Id',
        'Your application key', ''));
    $settings->add(new admin_setting_configtext('eventials/client_secret',
        'Client Secret',
        'Your application secret', ''));
}

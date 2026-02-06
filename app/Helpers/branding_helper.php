<?php

if (!function_exists('get_company_logo')) {
    function get_company_logo()
    {
        $db = \Config\Database::connect();
        $owner = $db->table('users')->where('role', 'owner')->get()->getRowArray();

        if ($owner && !empty($owner['company_logo'])) {
            return base_url($owner['company_logo']);
        }

        return base_url('assets/img/logo_.png');
    }
}

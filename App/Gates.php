<?php

namespace App;

class Gates
{

    public function checkAdminAuth() {
        if (!empty($_SESSION['admin_user'])) {
            return true;
        }
        return false;
    }

}


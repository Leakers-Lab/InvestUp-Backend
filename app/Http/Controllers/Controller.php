<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function hasCompany($user, $id)
    {
        $is_true = $user->Companies->where('id', $id)->first();

        if ($is_true) return 1;

        return 0;
    }

    public function hasProject($user, $id)
    {
        $is_true = $user->Companies->Projects->where('id', $id)->first();

        if ($is_true) return 1;

        return 0;
    }
}

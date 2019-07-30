<?php


namespace App\Http\Controllers\v1;


use App\Models\Nb\Goods;
use App\Models\Up\Wallet;
use App\Models\User;


class TestController extends BaseController
{

    function index()
    {
        Wallet::checkChangLock('1000000');
    }
}

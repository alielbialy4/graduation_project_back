<?php

namespace App\Modules\Rooms\Controllers;

use App\Modules\Rooms\Requests\StoreRequest;
use App\Modules\Rooms\Services\RoomsService;
use App\Http\Controllers\AdminBaseController;
use App\Modules\Rooms\Requests\UpdateRequest;

class AdminController extends AdminBaseController
{
    public function __construct()
    {
        $this->service       = new RoomsService();
        $this->StoreRequest  = new StoreRequest();
        $this->UpdateRequest = new UpdateRequest();
    }

}

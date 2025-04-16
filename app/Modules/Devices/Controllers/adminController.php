<?php

namespace App\Modules\Devices\Controllers;

use App\Modules\Devices\Requests\StoreRequest;
use App\Modules\Devices\Services\DevicesService;
use App\Http\Controllers\AdminBaseController;
use App\Modules\Devices\Requests\UpdateRequest;

class AdminController extends AdminBaseController
{
    public function __construct()
    {
        $this->service       = new DevicesService();
        $this->StoreRequest  = new StoreRequest();
        $this->UpdateRequest = new UpdateRequest();
    }

}

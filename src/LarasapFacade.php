<?php

namespace Toolkito\Larasap;

use \Illuminate\Support\Facades\Facade;

class LarasapFacade extends Facade {

    protected static function getFacadeAccessor() {
        return 'larasap';
    }
}

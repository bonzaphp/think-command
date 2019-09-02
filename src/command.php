<?php
/**
 * Created by yang
 * User: bonzaphp@gmail.com
 * Date: 2019-08-29
 * Time: 14:20
 */

use bonza\think\command\command\make\Msvc;
use bonza\think\command\command\make\Models;
use bonza\think\command\command\make\Action;
use bonza\think\command\command\make\Service;
use think\Console;

Console::addDefaultCommands([
    'bonza:service' => Service::class,
    'bonza:action'  => Action::class,
    'bonza:models'  => Models::class,
    'bonza:msvc'    => Msvc::class,
]);

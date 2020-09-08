<?php
declare(strict_types=1);

namespace App\Frontend;
use App\Frontend\Controller;

interface BackendController extends Controller
{
    public function init():void;
}

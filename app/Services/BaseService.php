<?php

namespace App\Services;

class BaseService
{
    protected $model;
    protected $response = ['code' => 200, 'data' => []];
}

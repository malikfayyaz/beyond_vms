<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CareerOpportunitiesContract extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'careerOpportunitiescontract';
    }
 
}
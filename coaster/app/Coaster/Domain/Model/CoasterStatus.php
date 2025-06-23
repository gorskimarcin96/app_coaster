<?php

namespace App\Coaster\Domain\Model;

enum CoasterStatus: string
{
    case OK = 'ok';
    case MISSING_CLIENTS = 'missing clients';
    case MISSING_WAGONS_AND_PERSONNEL = 'missing wagons and personnel';
    case MISSING_WAGONS = 'missing wagons';
    case MISSING_PERSONNEL = 'missing personnel';
    case EXCESS_WAGONS = 'excess wagons';
    case EXCESS_PERSONNEL = 'excess personnel';
}

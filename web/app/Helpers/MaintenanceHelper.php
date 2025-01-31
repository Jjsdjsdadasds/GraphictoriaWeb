<?php

/*
	XlXi 2022
	Maintenance helper
*/

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\MaintenanceModeBypassCookie;

class MaintenanceHelper
{
	public static function isDown()
	{
		if(GridHelper::isIpWhitelisted())
			return false;
		
		if(!file_exists(storage_path('framework/down')))
			return false;
		
		$data = json_decode(file_get_contents(storage_path('framework/down')), true);
		
		return (app()->isDownForMaintenance() && !MaintenanceHelper::hasValidBypassCookie($data));
	}
	
	protected static function hasValidBypassCookie(array $data)
    {
		$request = request();
		
        return isset($data['secret']) &&
                $request->cookie('vb_constraint') &&
                MaintenanceModeBypassCookie::isValid(
                    $request->cookie('vb_constraint'),
                    $data['secret']
                );
    }
}

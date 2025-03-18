<?php

namespace App\Helpers;

use App\Visitor;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

class VisitorHelper 
{
    static function add($property_id = null) 
    {
        $ip = request()->ip;
        $location = Location::get($ip);

        if ($ip === '127.0.0.1' || $ip == null || ($location && !$location->countryName)) return;

        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        
        $exists = Visitor::where('ip', $ip)
            ->where('property_id', $property_id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->exists();
        
        if (!$exists) {
            $data = [
                "ip" => $ip,
                "user_id" => Auth::user()->id ?? null,
                "property_id" => $property_id, 
                "city_name" => $location->cityName ?? null,
                "country_code" => $location->countryCode ?? null,
                "country_name" => $location->countryName ?? null,
            ];
        
            Visitor::create($data);
        }
    }
}
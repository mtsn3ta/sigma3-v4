<?php

namespace App\Http\Middleware;

use App\Support\SettingHelper;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [

            'settings' => [

                'school_name' => SettingHelper::schoolName(),

                'school_address' => SettingHelper::schoolAddress(),

                'school_phone' => SettingHelper::schoolPhone(),

                'school_email' => SettingHelper::schoolEmail(),

                'logo' => SettingHelper::schoolLogoUrl(),

                'official_logo' => SettingHelper::schoolOfficialLogoUrl(),

                'favicon' => SettingHelper::schoolFaviconUrl(),

                'academic_year' => SettingHelper::tahunAjaranLabel(),

                'principal' => SettingHelper::kepalaMadrasahNama(),

            ],

        ]);
    }
}

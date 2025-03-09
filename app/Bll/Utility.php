<?php

namespace App\Bll;

use App\Enums\Session;
use App\Models\Language;
use Illuminate\Support\Str;
use App\Modules\Auth\Models\User;
use App\Modules\Mcp_Moderators\Models\Moderator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Modules\Mcp_Settings\Models\Settings;

class Utility
{

    private static function GetLangObject()
    {
        $firstLang = Language::where('code', App::getLocale())->first();
        if ($firstLang == null)
            $firstLang = Language::first();
        if ($firstLang != null) {
            session(Session::SCHOOL_LANG->value, $firstLang);
            return $firstLang;
        }
        $firstLang = Language::create(["code" => App::getLocale(), "title" => App::getLocale()]);
        session(Session::SCHOOL_LANG->value, $firstLang);

        return $firstLang;
    }

    public static function lang_id()
    {

        if (App::getLocale() != Session::getLangCode()) {
            session()->remove(Session::SCHOOL_LANG->value);
            return Utility::GetLangObject()->id;
        }
        if ((session()->input(Session::SCHOOL_LANG->value)))
            return session()->input(Session::SCHOOL_LANG->value)->id;

        return Utility::GetLangObject()->id ?? 1;
    }

    public static function get_dialing_code()
    {
        return +966;
    }

    public function removeZeroFomphone($phone)
    {
        if (substr($phone, 0, 1) == '0') {
            return substr($phone, 1);
        }
        return $phone;
    }

    // get user guard
    public static function getUserGuard()
    {
        return Auth::guard('sanctum')?->user()?->guard;
    }

    // check permission if not admin
    public static function checkPermission($permission)
    {
        $user = Auth::guard('sanctum')?->user();
        if (!$user) {
            return false;
        }
        if ($user?->guard == 'admin') {
            return true;
        }
        return Moderator::findOrFail($user->id)->getAllPermissions()->pluck('name')->contains($permission);
    }

    public static function get_user_id()
    {
        $user = Auth::guard('sanctum')?->user();
        if ($user) {
            return $user->id;
        }
        return null;
    }

    /**
     * Paginate data
     *
     * @param $resource
     * @param $data
     * @param int $limit
     * @return array
     */

    public static function paginateData($resource, $data, $limit): array
    {

        $data = $data->paginate($limit);
        $responseData = [
            "data" => $resource::collection($data)->resolve(),  // Resolving collection to standard array
            "links" => [
                "first" => $data->url(1),
                "last" => $data->url($data->lastPage()),
                "prev" => $data->previousPageUrl(),
                "next" => $data->nextPageUrl()
            ],
            "meta" => [
                "current_page" => $data->currentPage(),
                "from" => $data->firstItem(),
                "last_page" => $data->lastPage(),
                "links" => array_map(function ($link) {
                    return [
                        "url" => $link['url'],
                        "label" => $link['label'],
                        "active" => $link['active'],
                    ];
                }, $data->linkCollection()->toArray()),
                "path" => $data->path(),
                "per_page" => $data->perPage(),
                "to" => $data->lastItem(),
                "total" => $data->total()
            ]
        ];
        return $responseData;
    }

    public static function generateUsername($fullName)
    {
        // Split the full name into parts by spaces
        $nameParts = explode(' ', trim($fullName));

        // Start with the first word for the base of the username
        $username = $nameParts[0];

        // Add initials of other name parts to make the username unique and meaningful
        if (count($nameParts) > 1) {
            for ($i = 1; $i < count($nameParts); $i++) {
                $username .= $nameParts[$i][0]; // Add initials of remaining words
            }
        }

        // Convert username to ASCII (slugify) for URL-safe characters
        $username = Str::slug($username, '_');

        // Ensure uniqueness in the database by appending a counter if necessary
        $originalUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . '_' . $counter;
            $counter++;
        }

        return $username;
    }

    // get file size by path miga byte
    public static function getFileSize($path)
    {
        return round(filesize($path) / 1024 / 1024, 2);
    }

    // get file extension
    public static function getFileExtension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    // settings
    public static function getSettings($key)
    {
        return Settings::where('id' , '1')->first()?->$key;
    }
}

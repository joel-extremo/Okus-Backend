<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model{

    /**
     * Get Provincias.
     *
     * @return array
     */
    public static function verifyClientCredentials($clientId, $clientSecret)
    {

        $query = "SELECT * FROM oauth_clients WHERE id='$clientId' AND secret='$clientSecret'";

        $result = DB::select($query);

        return $result ? true : false;
    }

    public static function avoidCredentials()
    {
        return true;
    }

}

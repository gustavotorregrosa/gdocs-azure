<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Google_Client;
use Google_Service_Drive;

class GToken extends Model
{

    protected $table = "token";
    protected $guarded = [];
    protected $meuToken;


    public function atualizaToken(){
        $meuToken = null;
        $meuClient = new Google_Client;
        $meuClient->useApplicationDefaultCredentials();
        $meuClient->addScope(Google_Service_Drive::DRIVE);
        $tokenCallback = function ($cacheKey, $accessToken) use (&$meuToken){
            $meuToken = $accessToken;
          };
        $meuClient->setTokenCallback($tokenCallback);
        $httpClient = $meuClient->authorize();
        $response = $httpClient->get('https://www.googleapis.com/auth/drive');
        $this->access_token = $meuToken;
        $this->save();
    }


    public function getAccessToken()
    {

        try{
            $this->meuToken = self::findOrFail(1);
        }catch(\Exception $e){
            $this->meuToken = new self;
        }
    
        $tokenExpirado = false;
        if(Carbon::now()->diffInMinutes($this->meuToken->updated_at) > 10){
            $tokenExpirado = true;
        }

        if (!$this->meuToken->access_token || $tokenExpirado) {
           $this->meuToken->atualizaToken(); 
        }
        return $this->meuToken->access_token;
    }
}

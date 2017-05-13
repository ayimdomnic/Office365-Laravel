<?php

namespace Ayimdomnic\Office365Laravel;
use Illuminate\Support\Facades\Config;

/**
* connecting to the Office365Api
*/
class Connect
{
	
	protected $provider;

	public function __construct()
	{
		$this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => Config::get('office365.CLIENT_ID'),
            'clientSecret'            => Config::get('office365.CLIENT_SECRET'),
            'redirectUri'             => Config::get('office365.REDIRECT_URI'),
            'urlAuthorize'            => Config::get('office365.AUTHORITY_URL') . Config::get('office365.AUTHORIZE_ENDPOINT'),
            'urlAccessToken'          => Config::get('office365.AUTHORITY_URL') . Config::get('office365.TOKEN_ENDPOINT'),
            'urlResourceOwnerDetails' => '',
            'scopes'                  => Config::get('office365.SCOPES')
        ]);
	}

	public function connect()
	{
		if (session_status()==PHP_SESSION_NONE) {
			# code...
			session_start();
		}
	}
}
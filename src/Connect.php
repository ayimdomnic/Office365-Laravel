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

	public static function  connect()
	{
		if (session_status()==PHP_SESSION_NONE) {
			# code...
			session_start();
		}

		$_this = new self;

	    $authorizationUrl = $_this->provider->getAuthorizationUrl();
	    // The OAuth library automaticaly generates a state value that we can
	    // validate later. We just save it for now.
	    $_SESSION['state'] = $_this->provider->getState();
	    header('Location: ' . $authorizationUrl);
	    exit();
	}

	public static function get_connection()
	{
		session_start();

	    // Validate the OAuth state parameter
	    if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['state'])) {
	        unset($_SESSION['state']);
	        exit('State value does not match the one initially sent');
	    }

	    // With the authorization code, we can retrieve access tokens and other data.
	    try {
	        $_this = new self;
	        // Get an access token using the authorization code grant
	        $accessToken = $_this->provider->getAccessToken('authorization_code', [
	            'code'     => $_GET['code']
	        ]);
	        $_SESSION['access_token'] = $accessToken->getToken();
	        // The id token is a JWT token that contains information about the user
	        // It's a base64 coded string that has a header, payload and signature
	        $idToken = $accessToken->getValues()['id_token'];
	        $decodedAccessTokenPayload = base64_decode(
	            explode('.', $idToken)[1]
	        );
	        $jsonAccessTokenPayload = json_decode($decodedAccessTokenPayload, true);
	        // The following user properties are needed in the next page
	        session()->put('office365_email', $jsonAccessTokenPayload['preferred_username']);
	        session()->put('office365_name', $jsonAccessTokenPayload['name']);
	    } catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
	        echo 'Something went wrong, couldn\'t get tokens: ' . $e->getMessage();
	    }
	}

}
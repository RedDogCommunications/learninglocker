<?php namespace Controllers\adminAPI;

use \Illuminate\Routing\Controller;
use \Response as IlluminateResponse;
use \LockerRequest as LockerRequest;
use \Config as Config;
use \Request as Request;
use \Route as Route;
use \DB as DB;
use \Locker\Repository\Lrs\EloquentRepository as LrsRepository;
use \Lrs as Lrs;
use \Client as Client;
use \Locker\Helpers\Helpers as Helpers;
use \Locker\Helpers\Exceptions as Exceptions;
use \LucaDegasperi\OAuth2Server\Filters\OAuthFilter as OAuthFilter;

class Base extends Controller {

  /**
   * Constructs a new base controller.
   */
  public function __construct() {
    $this->checkApiClient();
  }
  
    /**
     * Authenticates admin user from the request
     */
    protected function checkApiClient(){
        $userAuthenticated = false;
        list($username, $password) = Helpers::getUserPassFromAuth();
        if (! \Auth::attempt(['email' => $username, 'password' => $password]))
        {
            throw new Exceptions\Exception('Invalid credentials provided', 401);
        }
        else if( \Auth::user()->role != 'super')
        {
            throw new Exceptions\Exception('Not enought privileges', 401);
        }
    }

  protected function returnJson($data) {
    $params = LockerRequest::getParams();
    if (LockerRequest::hasParam('filter')) {
      $params['filter'] = json_decode(LockerRequest::getParam('filter'));
    }

    return IlluminateResponse::json([
      'version' => Config::get('api.using_version'),
      'route' => Request::path(),
      'url_params' => Route::getCurrentRoute()->parameters(),
      'params' => $params,
      'data' => $data,
      'debug' => !Config::get('app.debug') ? trans('api.info.trace') : DB::getQueryLog()
    ]);
  }
}

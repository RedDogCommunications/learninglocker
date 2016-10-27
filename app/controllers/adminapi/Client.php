<?php 
namespace Controllers\adminAPI;

use Carbon\Carbon;
use \Locker\Repository\Lrs\Repository as LrsRepo;
use \Locker\Repository\Client\Repository as ClientRepo; 
use \Locker\Repository\Query\QueryRepository as QueryRepository;
use \Locker\Helpers\Exceptions as Exceptions;

class Client extends Base 
{
    protected $user, $lrs, $client;

    /**
     * Constructs a new Client controller.
     */
    public function __construct(LrsRepo $lrs, ClientRepo $client) 
    {
        parent::__construct();
        $this->lrs = $lrs;
        $this->client = $client;
    }
    
    /**
    * Load the required information to manage.
    * @param String $lrs_id
    */
    public function manage($lrs_id) 
    {
        $opts = ['user' => \Auth::user()];
        $lrs = $this->lrs->show($lrs_id, $opts);
        $lrs_list = $this->lrs->index($opts);
        $clients = \Client::where('lrs_id', $lrs->id)->get();
        $data = [
            'clients' => $clients,
            'lrs' => $lrs,
            'list' => $lrs_list
        ];
        return \Response::json($data,200);
    }
    
    /**
    * Load the manage clients page.
    * @param String $lrs_id
    */
    public function clients($lrs_id) 
    {
        $opts = ['user' => \Auth::user()];
        $lrs = $this->lrs->show($lrs_id, $opts);
        $clients = \Client::where('lrs_id', $lrs->id)->get();
        $data = [
            'clients' => $clients,
        ];
        return \Response::json($data,200);
    }
}

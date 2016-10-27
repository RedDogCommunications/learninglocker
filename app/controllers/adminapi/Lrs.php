<?php 
namespace Controllers\adminAPI;

use Carbon\Carbon;
use \Locker\Repository\Lrs\Repository as LrsRepo;
use \Locker\Repository\Client\Repository as ClientRepo; 
use \Locker\Repository\Query\QueryRepository as QueryRepository;
use \Locker\Helpers\Exceptions as Exceptions;
use app\locker\data\reporting\getVerbs;

class Lrs extends Base 
{
    protected $user, $lrs, $client;

    /**
     * Constructs a new LRS controller.
     */
    public function __construct(LrsRepo $lrs, ClientRepo $client) 
    {
        parent::__construct();
        $this->lrs = $lrs;
        $this->client = $client;
    }
  
  
    /**
     * Return the list of LRS
     * @param Object $options
     * @return Json $results
     **/
    public function index(){
        $lrs = \Lrs::where('users._id', \Auth::user()->_id)->get();
        return $lrs;
    }
    
    
    /**
     * Load the required information to mana.
     * @param string $id
     */
    public function getById($id)
    {
        $opts = ['user' => \Auth::user()];
        $lrs = $this->lrs->show($id, $opts);
        $data = [
            'lrs' => $lrs,
        ];
        return \Response::json($data,200);
    }

  /**
   * Store a newly created resource in storage.
   */
  public function store() 
  {      
    $data = \Input::all();

    //lrs input validation
    $rules['title']        = 'required';
    $rules['description']  = '';
    
    $validator = \Validator::make($data, $rules);
    if ($validator->fails())
    { 
        $errors = $validator->errors();
        return \Response::json(['errors'=>$errors], 422); 
    }
    
    // Store lrs
    $opts = ['user' => \Auth::user()];
    $s = $this->lrs->store($data, $opts);

    if($s){
        return \Response::json($s, 201);
    }
    
    return \Response::json(trans('create_problem'), 500);
  }
}

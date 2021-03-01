<?php
namespace App\Repository\Base;

use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository implements IBaseRepository
{
    protected $_model;
    
    public function __construct()
    {
        $this->getModel();
    }

    abstract public function setModel();

    public function getModel()
    {
        $this->_model = app()->make(
            $this->setModel()
        );
    }
    public function count(){   
    }

    public function all(){
    }

    public function index(){
    }

    public function create($params)
    {
    }

    public function update($id, $params)
    {
    }

    public function delete($id)
    {
    }

    public function find($id, $with = null)
    {
    }
    
    public function findByCondition($with = null){
        return $this->_model::where($with);
    }
    
    public function findById($id)
    {   
       return $this->_model::findOrFail($id);
    }

    protected function sendSuccess($data=null){
        return array(
            'success'=> true,
            'data'=> $data
        );
    }
    protected function sendFailed($message='Failed'){
        return array(
            'success'=> false,
            'message'=> $message
        );
    }
}
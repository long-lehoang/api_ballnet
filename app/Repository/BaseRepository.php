<?php
namespace App\Repository;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

abstract class BaseRepository
{
    protected $_model;
    
    public function __construct()
    {
        $this->setModel();
    }

    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->_model = app()->make(
            $this->getModel()
        );
    }

    /**
     * Get All
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all(){
        return $this->_model->all();
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create($keys = [], $params = [])
    {
        return $this->_model->firstOrCreate($keys,$params);
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function forceCreate($params)
    {
        return $this->_model->Create($params);
    }

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function update($id, $params){
        try{
            $result = $this->find($id);
            $result->update($params);
            return $result->fresh();
        }catch(Exception $e){
            return false;
        }
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id){
        try{
            $result = $this->find($id);
            $result->delete();
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id){
        return $this->_model->findOrFail($id);
    }
    
    public function findByCondition($param , $value ,  $operator = '='){
        return $this->_model::where($param, $operator, $value)->get();
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
<?php
namespace App\Repository;

use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    public function create($params)
    {
        return $this->_model->create($params);
    }

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function update($id, $params){
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id){
        $result = $this->find($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id){
        return $this->_model->find($id);
    }
    
    public function findByCondition($with = null){
        return $this->_model::where($with);
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
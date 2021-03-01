<?php
namespace App\Repository\Base;

interface IBaseRepository{
    public function all();
    public function index();
    public function delete($id);
    public function create($params);
    public function find($id,$with=null);
    public function findById($id);
    public function update($id, $params);
    public function count();
    public function findByCondition($with = null);
}
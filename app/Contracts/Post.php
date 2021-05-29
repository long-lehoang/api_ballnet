<?php

namespace App\Contracts;

interface Post{    
    
    /**
     * getPostOfUser
     *
     * @param  mixed $id
     * @return void
     */
    public function getPostOfUser($id);
    
    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id);
    
    /**
     * update
     *
     * @param  mixed $id
     * @param  mixed $request
     * @return void
     */
    public function update($id, $request);
    
    /**
     * create
     *
     * @param  mixed $request
     * @return void
     */
    public function create($request);
}
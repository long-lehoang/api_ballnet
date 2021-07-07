<?php

namespace App\Repository;

use App\Repository\BaseRepository;

class StadiumRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Stadium::class;
    }

    public function active()
    {
        return $this->_model::where('status', 1)->paginate(config("constant.PAGINATION.STADIUM.LIMIT"));
    }

    public function inactive()
    {
        return $this->_model::where('status', 0)->get();
    }

    public function show($id)
    {
        $stadium = $this->find($id);
        $stadium->extensions;
        $stadium->images;
        $stadium->booking;
        return $stadium;
    }

    public function filterBySport($sport)
    {
        return $this->_model::where('sport', $sport)->get();
    }

    public function search($name, $location, $sport)
    {
        $params = [];
        if(!empty($name))
        $params[] = ["name","LIKE", "%$name%"];

        if(!empty($location))
        $params[] = ["location","LIKE", "%$location%"];

        if(!empty($sport))
        $params[] = ["sport","LIKE", "%$sport%"];

        return $this->_model::where($params)->paginate(config("constant.PAGINATION.STADIUM.LIMIT"));
    }
}
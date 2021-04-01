<?php

namespace App\Contracts;

interface Post{
    public function getPostByUser($username);
}
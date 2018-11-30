<?php 

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Food;


class UserService
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    public function index()
    {
        return $this->model::all();
    }
}
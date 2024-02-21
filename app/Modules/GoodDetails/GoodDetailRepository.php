<?php

namespace App\Modules\GoodDetails;

use App\Models\Brand;
use App\Models\Modal;
use App\Models\Category;


use App\Modules\GoodDetails\GoodDetailRepositoryInterface;

class GoodDetailRepository implements GoodDetailRepositoryInterface
{
    protected $model;
    public function defineModel($type)
    {
      if($type=='brand'){
        $this->model=new Brand();
      }
      else if($type=='modal')
      {
        $this->model=new Modal();
      }
      else
      {
        $this->model=new Category();
      }
    }

    public function getAll(string $type)
    {
        $this->defineModel($type);
        return  $this->model::paginate(10);
    }

    public function getById(string $type,$id)
    {   
        $this->defineModel($type);
        return  $this->model::find($id);
    }

    public function create(string $type,array $data)
    {
        $this->defineModel($type);
        return  $this->model::create($data);
    }

    public function update(string $type,$id, array $data)
    {
        $this->defineModel($type);
        $model = $this->getById($type,$id);
        if($model)
        {
          $model->update($data);
        }
        else
        {
         return ['error'=>$type.' not found!']; 
        }
        return $model;
    }

    public function delete(string $type,$id)
    {
      $this->defineModel($type);
      $model = $this->getById($type,$id);
      if($model)
      {
        $model->delete();
      }
      else
      {
       return ['error'=>$type.' not found!']; 
      }
      return ['success'=>$type.' deleted'];
    }

    public function getAllWithoutPaginate(string $type)
    {
      $this->defineModel($type);
      return $this->model::all();
    }

    public function searchBrands($input)
    {
      return  Brand::select("id","name")
      ->where('name', 'LIKE', '%' .$input. '%')
      ->orWhere('description', 'LIKE', '%' .$input. '%')
      ->get();
    }

    public function searchModals($input)
    {
      return  Modal::select("id","name")
      ->where('name', 'LIKE', '%' .$input. '%')
      ->orWhere('description', 'LIKE', '%' .$input. '%')
      ->get();
    }
    

    public function searchCategories($input)
    {
      return  Category::select("id","name")
      ->where('name', 'LIKE', '%' .$input. '%')
      ->orWhere('description', 'LIKE', '%' .$input. '%')
      ->get();
    }

    public function getBrand($text){
      return Brand::where('name',$text)->first('id');
    }
    public function getModal($text){
      return Modal::where('name',$text)->first('id');
    }
   
    public function getCategory($text){
      return Category::where('name',$text)->first('id');
    }
}

<?php

namespace App\Modules\GoodDetails;
use App\Http\Resources\CommonResource;
use App\Modules\GoodDetails\GoodDetailRepositoryInterface;

class GoodDetailService 
{

    public function __construct(protected GoodDetailRepositoryInterface $goodDetailRepository)
    {
    }

    public function getAll(string $type)
    {
        return CommonResource::collection($this->goodDetailRepository->getAll($type));
    }

    public function getById(string $type,$id)
    {
        return new CommonResource($this->goodDetailRepository->getById($type,$id));
    }

    public function create(string $type,array $data)
    {  
        return  new CommonResource( $this->goodDetailRepository->create($type,$data));    
    }

    public function update(string $type,$id,array $data)
    {  
        return new CommonResource($this->goodDetailRepository->update($type,$id,$data));     
    }

    public function delete(string $type,$id)
    {
        return $this->goodDetailRepository->delete($type,$id);
    }

    public function getAllWithoutPaginate(string $type)
    {
        return $this->goodDetailRepository->getAllWithoutPaginate($type);
    }
    
    public function searchgoodDetail($input)
    {
      $brands= CommonResource::collection($this->goodDetailRepository->searchBrands($input));  
      $brands->map(function($brand)  
      {
       $brand['table']='brand'; 
      });
      $modals=CommonResource::collection( $this->goodDetailRepository->searchModals($input));  
      $modals->map(function($modal)  
      {
       $modal['table']='modal'; 
      });
      $categories= CommonResource::collection($this->goodDetailRepository->searchCategories($input));  
      $categories->map(function($category)  
      {
       $category['table']='category'; 
      });
      $fnl = collect($brands->merge($modals))->merge($categories);
      return $fnl;
    //   return ['brands'=>$brands,'modals'=>$modals,'categories'=>$categories];

    }
}
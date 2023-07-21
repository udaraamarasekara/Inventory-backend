<?php

namespace App\Modules\Dealers;

use App\Models\Dealer;



use App\Modules\Dealers\DealerRepositoryInterface;

class DealerRepository implements DealerRepositoryInterface
{
   

    public function getAll()
    {
        return  Dealer::paginate(10);
    }

    public function getById($id)
    {   
        return  Dealer::find($id);
    }

    public function create(array $data)
    {
        return  Dealer::create($data);
    }

    public function update($id, array $data)
    {
        $dealer = $this->getById($id);
        if($dealer)
        {
            $dealer->update($data);
        }
        else
        {
         return ['error'=>'Dealer not found!']; 
        }
        return $dealer;
        
    }

    public function delete($id)
    {
        $dealer = $this->getById($id);
        $dealer->delete();
    }

    public function getByUserId($id)
    {
        return Dealer::where('user_id',$id)->first()['id']; 
    }


   
}

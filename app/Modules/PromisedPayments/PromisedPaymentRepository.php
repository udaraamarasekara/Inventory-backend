<?php

namespace App\Modules\PromisedPayments;

use App\Models\PromisedPayment;
use App\Modules\PromisedPayments\PromisedPaymentRepositoryInterface;

class PromisedPaymentRepository implements PromisedPaymentRepositoryInterface
{
    public function getAll()
    {
        return PromisedPayment::paginate(10);
    }

    public function getById($id)
    {
        return PromisedPayment::find($id);
    }

    public function create(array $data)
    {
        return PromisedPayment::create($data);
    }

    public function update($id, array $data)
    {
        $promisedPayment = $this->getById($id);
        if ($promisedPayment) {
            $promisedPayment->update($data);
        } else {
            return ['error' => 'Data not found!'];
        }
        return $promisedPayment;
    }

    public function delete($id)
    {
        $promisedPayment = $this->getById($id);
        if ($promisedPayment) {
            $promisedPayment->delete();
        } else {
            return ['error' => 'Data not found!'];
        }
        return ['success' => 'Data deleted'];
    }

    public function updateByDealId(array $data)
    {
     return PromisedPayment::where('deal_id',$data['deal_id'])->update($data);
    }
    
    public function deleteIfAny($dealId)
    {
      $promisedPayment= PromisedPayment::where('deal_id',$dealId);
      if($promisedPayment)
      {
        $promisedPayment->delete();
      }
    }
    public function getAllWithoutPaginate()
    {
        return PromisedPayment::all();
    }
    
}
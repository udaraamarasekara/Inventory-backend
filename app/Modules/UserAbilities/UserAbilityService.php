<?php

namespace App\Modules\UserAbilities;
use App\Http\Resources\CommonResource;
use App\Modules\UserAbilities\UserAbilityRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Modules\UserFeaturesService;
use App\Modules\Utilities\HelpingFunctions;
class UserAbilityService 
{

    public function __construct(protected UserAbilityRepositoryInterface $userAbilityRepository, protected UserFeaturesService $userFeatureService,protected HelpingFunctions $helpingFunctions)
    {
    }

    public function delete($id)
    {
        try
        {
            DB::beginTransaction();
            $user= $this->userAbilityRepository->delete($id);

            DB::commit();
            return $user;
        }
        catch(\Exception $e)
        {
          DB::rollBack(); 
        }
    
    }

    public function getById($id)
    {
        return new CommonResource($this->userAbilityRepository->getById($id));
    }
    
    public function create(array $data)
    {
        try
        {
            DB::beginTransaction();
            $user= new CommonResource($this->userAbilityRepository->create($data));

            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return $e;
        }
        return $user;  
    }

    public function update($id,array $data)
    {
        try
        {
            DB::beginTransaction();
            $user= new CommonResource($this->userAbilityRepository->update($id,$data));
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
        }
        return $user;    
    }

    public function setAbilitiesForProfession($profession_id,array $data)
    {
      $abilities=[];  
      if(in_array('addGrn',$data))
      {
       $abilities=$this->userFeatureService->addGrn();
      }
      if(in_array('addSale',$data))
      {
       $tmpAry=$this->userFeatureService->addSale();
       $abilities=[...$abilities,$tmpAry]; 
      }
        $abilities = collect($abilities)->where('ability',true);
        foreach($abilities as $ability)
        {
            $ability['profession_id'] = $profession_id;
            $this->create($ability);
        }
    }

}
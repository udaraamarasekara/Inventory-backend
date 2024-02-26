<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Good;
use App\Models\Modal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Modules\Goods\GoodService;
use App\Modules\GoodDetails\GoodDetailService;
use Illuminate\Validation\ValidationException;
class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected GoodService $goodService,protected GoodDetailService $goodDetailService)
    {
    }

    public function index()
    { 
        return  $this->goodService->getAll();
    }

    public function newGoodSearch(String $type, String $inputText)
    { 
        return $this->goodService->searchAll($type,$inputText);  
    }


    public function getRelevantGoodDetailIds(array $data)
    {
       return $this->goodDetailService->getRelevantGoodDetailIds($data);     
    }

    public function getAllWithinPeriod(Request $request)
    {   
        try 
        {
            $validatedData = $request->validate([
                'from' => 'date',
                'to' => 'date',
                'page'=>'integer|nullable'
            ]);
            return $this->goodService->getAllWithinPeriod($validatedData); 
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], $e->status);
        } 
 
    }

    public function newSale(Request $request)
    {
        $finalData=[];
        $validatorGood=$this->validateGoodData($request);
        if ($validatorGood->fails()) {
         return response()->json($validatorGood->errors());
       }
       $validatorPayment=$this->validatePaymentData($request);
       if ($validatorPayment->fails()) {
        return response()->json($validatorPayment->errors());
      }

        foreach($validatorGood->validated()['data'] as $dataSet)
        {
           try{  
            $ids =$this->getRelevantGoodDetailIds($dataSet);
            if($ids['brand_id']==null)
            {
              $type='brand';
              $data['name']=$dataSet['brand'];
              $data['description']='none';  
              $ids['brand_id']= $this->addGoodDetail($data,$type)->id;  
            }
            if($ids['modal_id']==null)
            {
              $type='modal';
              $data['name']=$dataSet['modal'];
              $data['description']='none';  
              $ids['modal_id']= $this->addGoodDetail($data,$type)->id;  
            }
            if($ids['category_id']==null)
            {
              $type='category';
              $data['name']=$dataSet['category'];
              $data['description']='none';  
              $ids['category_id']= $this->addGoodDetail($data,$type)->id;  
            }
            $dataSet['brand_id']=$ids['brand_id'];
            $dataSet['modal_id']=$ids['modal_id'];
            $dataSet['category_id']=$ids['category_id'];
            unset($dataSet['brand']);
            unset($dataSet['modal']);
            unset($dataSet['category']);
            $finalData['data'][]=$dataSet;
            
           }
           catch(\Exception $e)
           {
            dd($e);
           }
        }
        $finalData['deal_type']=1; 
        $finalData['amount']=$validatorPayment->validated()['amount'];
        $this->store($finalData);
    }

    public function newGrn(Request $request)
    {$finalData=[];
        $validatorGood=$this->validateGoodData($request);
        if ($validatorGood->fails()) {
         return response()->json($validatorGood->errors());
       }
       $validatorPayment=$this->validatePaymentData($request);
       if ($validatorPayment->fails()) {
        return response()->json($validatorPayment->errors());
      }

        foreach($validatorGood->validated()['data'] as $dataSet)
        {
           try{  
            $ids =$this->getRelevantGoodDetailIds($dataSet);
            if($ids['brand_id']==null)
            {
              $type='brand';
              $data['name']=$dataSet['brand'];
              $data['description']='none';  
              $ids['brand_id']= $this->addGoodDetail($data,$type)->id;  
            }
            if($ids['modal_id']==null)
            {
              $type='modal';
              $data['name']=$dataSet['modal'];
              $data['description']='none';  
              $ids['modal_id']= $this->addGoodDetail($data,$type)->id;  
            }
            if($ids['category_id']==null)
            {
              $type='category';
              $data['name']=$dataSet['category'];
              $data['description']='none';  
              $ids['category_id']= $this->addGoodDetail($data,$type)->id;  
            }
            $dataSet['brand_id']=$ids['brand_id'];
            $dataSet['modal_id']=$ids['modal_id'];
            $dataSet['category_id']=$ids['category_id'];
            unset($dataSet['brand']);
            unset($dataSet['modal']);
            unset($dataSet['category']);
            $finalData['data'][]=$dataSet;
            
           }
           catch(\Exception $e)
           {
            dd($e);
           }
        }
        $finalData['deal_type']=2; 
        $finalData['amount']=$validatorPayment->validated()['amount'];
        $this->store($finalData);
    }

    public function goodsCount()
    {  
        return  $this->goodService->goodsCount(); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function productTransactionCount()
    {
        return $this->goodService->productTransactionCount();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $data)
    {
        $this->authorize('create',Good::class);

        return $this->goodService->create($data);
            
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      return $this->goodService->getById($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   $this->authorize('edit',Good::class);
        $validator=$this->validateData($request);
        if ($validator->fails()) {
         return response()->json($validator->errors());
       }
        return $this->goodService->update($id,$validator->validated());
            
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $this->authorize('delete',Good::class);
        $validator = Validator::make(['id' => $id], [
            'id' => 'exists:goods,deal_id,deleted_at,NULL',
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors());  
        }
        return $this->goodService->delete($id);
    }

    public function profitLost(Request $request)
    {
        try 
        {
            $validatedData = $request->validate([
                'from' => 'date',
                'to' => 'date',
            ]);
            return $this->goodService->calProfitLost($validatedData); 
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], $e->status);
        } 
    }
    public function addGoodDetail(array $data,string $type)
    {
        if(in_array($type,['brand','modal','category']))
        { if($type==='brand'){ $this->authorize('create',Brand::class);}elseif($type==='modal'){$this->authorize('create',Modal::class);} else{$this->authorize('create',Category::class);}
            try 
            {
                $validatedData =validator::make($data,[
                    'name' => 'required|max:255',
                    'description' => 'required|max:255',
                ]);
                if ($validatedData->fails()) {
                    return response()->json($validatedData->errors());
                  }
                return $this->goodDetailService->create($type,$data);
            } catch (ValidationException $e) {
                return response()->json([
                    'errors' => $e->errors(),
                ], $e->status);
            }    
        }
        return response()->json(["type"=>"The selected type is invalid"]);     
    }

    public function viewGoodDetails(string $type)
    {
        if(in_array($type,['brand','modal','category']))
        {
            return $this->goodDetailService->getAll($type);
        }
        return response()->json(["type"=>"The selected type is invalid"]);     
    }

    public function updateGoodDetail(Request $request,string $type,string $id)
    {
        if(in_array($type,['brand','modal','category']))
        {if($type==='brand'){ $this->authorize('edit',Brand::class);}elseif($type==='modal'){$this->authorize('edit',Modal::class);} else{$this->authorize('edit',Category::class);}
            try 
            {
                $validatedData = $request->validate([
                    'name' => 'max:255',
                    'description' => 'max:255',
                ]);
                return $this->goodDetailService->update($type,$id,$validatedData);
            } catch (ValidationException $e) {
                return response()->json([
                    'errors' => $e->errors(),
                ], $e->status);
            } 
           
        }
        return response()->json(["type"=>"The selected type is invalid"]);     
    }
    public function deleteGoodDetail(string $type,string $id)
    {
        if(in_array($type,['brand','modal','category']))
        {
           if( $type==='brand' ){$this->authorize('delete',Brand::class);}elseif($type==='modal'){$this->authorize('delete',Modal::class); }else{$this->authorize('delete',Category::class);} 
            return $this->goodDetailService->delete($type,$id);
        }
        return response()->json(["type"=>"The selected type is invalid"]);     
    }
    public function allSales(Request $request)
    {
        try 
        {
            $validatedData = $request->validate([
                'from' => 'date|required',
                'to' => 'date|required',
                'page'=>'integer|nullable'
            ]);
            return $this->goodService->allSales($validatedData);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], $e->status);
        } 
    }


    public function allTimeSales(Request $request)
    {
        $validatedData = $request->validate([
            'page' => 'integer|nullable'
        ]);

      return $this->goodService->allTimeSales($validatedData);  
    }

    public function allTimeGrns(Request $request)
    {
        $validatedData = $request->validate([
            'page' => 'integer|nullable'
        ]);  
      return $this->goodService->allTimeGrns($validatedData);  
    }

    public function allGoodDetailSales(Request $request)
    {
        try 
        {
            $validatedData = $request->validate([
                'from' => 'date|required',
                'to' => 'date|required',
                'goodDetail'=>'string|required|max:800',
                'id'=>'required|integer'
            ]);
            if(in_array($validatedData['goodDetail'],['brand','modal','category']))
            {
                return $this->goodService->allGoodDetailSales($validatedData);
            }
            return response()->json(["goodDetail"=>"The selected type is invalid"]);     
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], $e->status);
        }   
    }
    public function allTimeGoodDetailSales(Request $request)
    {
        try 
        {
            $validatedData = $request->validate([
                'goodDetail'=>'string|required|max:800',
                'id'=>'required|integer'
            ]);
            if(in_array($validatedData['goodDetail'],['brand','modal','category']))
            {
                return $this->goodService->allTimeGoodDetailSales($validatedData);
            }
            return response()->json(["goodDetail"=>"The selected type is invalid"]);     
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], $e->status);
        }   
    }
    public function allTimeGoodDetailGrns(Request $request)
    {
        try 
        {
            $validatedData = $request->validate([
                'goodDetail'=>'string|required|max:800',
                'id'=>'required|integer'
            ]);
            if(in_array($validatedData['goodDetail'],['brand','modal','category']))
            {
                return $this->goodService->allTimeGoodDetailGrns($validatedData);
            }
            return response()->json(["goodDetail"=>"The selected type is invalid"]);     
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], $e->status);
        }   
    }
    public function allGoodDetailGrns(Request $request)
    {
        try 
        {
            $validatedData = $request->validate([
                'from' => 'date|required',
                'to' => 'date|required',
                'goodDetail'=>'string|required|max:800',
                'id'=>'required|integer'
            ]);
            if(in_array($validatedData['goodDetail'],['brand','modal','category']))
            {
                return $this->goodService->allGoodDetailGrns($validatedData);
            }
            return response()->json(["goodDetail"=>"The selected type is invalid"]);     
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], $e->status);
        }   
    }
    public function allGrns(Request $request)
    {
        try 
        {
            $validatedData = $request->validate([
                'from' => 'date|required',
                'to' => 'date|required',
                'page'=>'integer|nullable'
            ]);
            return $this->goodService->allGrns($validatedData);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], $e->status);
        } 
    }
    public function mostProfitedGoodDetail(Request $request)
    {
        try 
        {
            $validatedData = $request->validate([
                'from' => 'date|required',
                'to' => 'date|required',
                'goodDetail'=>'string|required|max:800',
            ]);
            if(in_array($validatedData['goodDetail'],['brand','modal','category']))
            {
                return $this->goodService->mostProfitedGoodDetail($validatedData);
            }
            return response()->json(["goodDetail"=>"The selected type is invalid"]);     
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], $e->status);
        }   
    }
    public function validateGoodData(Request $request)
    {
        $data = [ 'data' => $request->data ];
       return Validator::make($data, [
        'data.*.item_code' => 'required|string',
        'data.*.description' => 'required|max:800|String',
        'data.*.brand'=>'required|string|max:20',
        'data.*.modal'=>'required|string|max:20',
        'data.*.category'=>'required|string|max:20',
        'data.*.dealer_id'=>'nullable|exists:dealers,id|integer',
        'data.*.received_price_per_unit'=>'required|numeric|regex:/^\d{0,6}(\.\d{1,2})?$/',
        'data.*.sale_price_per_unit'=>'required|numeric|regex:/^\d{0,6}(\.\d{1,2})?$/',
        'data.*.job_number'=>'required|string|max:20',
        'data.*.unit'=>'required|string|max:20',
        'data.*.quantity'=>'required|numeric|regex:/^\d{0,6}(\.\d{1,2})?$/',
        
        ]);
    }

    public function validatePaymentData(Request $request)
    {
       return Validator::make($request->payment, [
        'amount'=>'required|numeric|regex:/^\d{0,6}(\.\d{1,2})?$/',
        'promised_amount'=>'nullable|numeric|regex:/^\d{0,6}(\.\d{1,2})?$/',
        'promised_deadline'=>'nullable|date|date_format:Y-m-d'

        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
    public function store(Request $request)
    {
        $validator=$this->validateData($request);
        if ($validator->fails()) {
         return response()->json($validator->errors());
       }
        return $this->goodService->create($validator->validated());
            
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
    {
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
    public function addGoodDetail(Request $request,string $type)
    {
        if(in_array($type,['brand','modal','category']))
        {
            try 
            {
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                    'description' => 'required|max:255',
                ]);
                return $this->goodDetailService->create($type,$validatedData);
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
        {
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
      return $this->goodService->allTimeSales();  
    }

    public function allTimeGrns(Request $request)
    {
      return $this->goodService->allTimeGrns();  
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
    public function validateData(Request $request)
    {
       return Validator::make($request->all(), [
        'item_code' => 'required|string',
        'description' => 'required|max:800|String',
        'brand_id'=>'required|exists:brands,id|integer',
        'modal_id'=>'required|exists:modals,id|integer',
        'category_id'=>'required|exists:categories,id|integer',
        'dealable_type'=>'required|string',
        'dealable_id'=>'required|integer',
        'deal_type'=>'required|in:1,2',
        'dealer_id'=>'nullable|exists:dealers,id|integer',
        'received_price_per_unit'=>'required|numeric|regex:/^\d{0,6}(\.\d{1,2})?$/',
        'sale_price_per_unit'=>'required|numeric|regex:/^\d{0,6}(\.\d{1,2})?$/',
        'expired_date'=>'required|date_format:Y-m-d|date',
        'unit'=>'required|string|max:20',
        'quantity'=>'required|numeric|regex:/^\d{0,6}(\.\d{1,2})?$/',
        'amount'=>'required|numeric|regex:/^\d{0,6}(\.\d{1,2})?$/',
        'promised_amount'=>'nullable|numeric|regex:/^\d{0,6}(\.\d{1,2})?$/',
        'promised_deadline'=>'nullable|date|date_format:Y-m-d'

        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
//use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest as Request;
use Auth;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'done'=>true,
            'message'=>'',
            'data' => Product::orderBy('id','desc')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $return = [
            'done'=>false,
            'message' => 'Something wrong!',
            'data' => []
        ];

        $input = $request->all();        
        $input['image'] = $this->imageUpload($input['image']);
        $input['created_by'] = Auth::user()->id;
        $input['created_at'] = (Carbon::now())->toDateTimeString();

        $storeData = Product::create($input);
        if($storeData){
            $return = [
                'done'=>true,
             'message' => 'Data store successful',
                'data' => $storeData
            ];            
        }
        return response()->json($return);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        $return = [
            'done'=>false,
            'message' => 'data not found',
            'data' => []
        ];  
        $getData = Product::find($product);
        if($getData){
            $return = [
                'done'=>true,
                'message' => 'data found',
                'data' => $getData
            ];            
        }
        return response()->json($return);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($product)
    {
        $return = [
            'done'=>false,
            'message' => 'data not found',
            'data' => []
        ];  
        $getData = Product::find($product);
        if($getData){
            $return = [
                'done'=>true,
                'message' => 'data found',
                'data' => $getData
            ];            
        }
        return response()->json($return);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product)
    {
        $return = [
            'done'=>false,
            'message' => 'Something wrong!',
            'data' => []
        ];

        $input = $request->all(); 
        unset($input['_method']);  

        if(isset($input['image']) && $input['image']){
            $input['image'] = $this->imageUpload($input['image']);
        }
        $input['updated_by'] = Auth::user()->id;
        $input['updated_at'] = (Carbon::now())->toDateTimeString();        
        $storeData = Product::where('id',$product)->update($input);//$product->update($input);
        if($storeData){
            $return = [
                'done'=>true,
                'message' => 'Data update successful',
                'data' => Product::find($product)
            ];            
        }
        return response()->json($return);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $return = [
            'done'=>false,
            'message' => 'Something wrong!'
        ];

        $data = Product::find($product);
        if($data){
            $success = $data->delete();        
            $return = [
                'done'=>true,
                'message' => 'Data delete successful'
            ];            
        }
        return response()->json($return);
    }

    private function imageUpload($image){
        $imageName = 'pdt_'.time().'.'.$image->extension(); 
        $image->move(public_path('files/products'), $imageName);
        return 'files/products/'.$imageName;
    }
}

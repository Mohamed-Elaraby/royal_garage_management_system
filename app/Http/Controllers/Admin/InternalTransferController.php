<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TransferProductDatatable;
use App\Http\Requests\TransferProductRequset;
use App\Interfaces\product\ChangeQuantity;
use App\Interfaces\product\ProductQuantity;
use App\Models\Branch;
use App\Models\InternalTransfer;
use App\Models\Product;
use App\Models\SubCategories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\Collection;

class InternalTransferController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:update-internalTransfer')->only(['index', 'edit']);
    }

    public function index(TransferProductDatatable $transferProductDatatable)
    {
        return $transferProductDatatable -> render('admin.internalTransfer.index');
    }

    public function transfer_product($product_id)
    {
        $product = Product::findOrFail($product_id);
        $branche = Branch::where('id', $product -> branch -> id) -> pluck('display_name', 'id');
//        $subCategory = SubCategories:: pluck('name', 'id')->toArray();
//        dd($subCategory);
        $branches_without_one = Branch::where('id', '!=', $product -> branch_id) -> pluck('display_name', 'id')->toArray();

        return view('admin.internalTransfer.transfer', compact('product', 'branche', 'branches_without_one'));
    }

    public function transfer_product_store(TransferProductRequset $request)
    {
//        dd($request -> all());
        InternalTransfer::create($request->except('user_id') + ['user_id' => auth()->user()->id]);
        return redirect() -> route('admin.products.index') -> with('success', 'تم ارسال سند التحويل وفى انتظار الموافقة علية');
    }

    public function change_product_status(Request $request)
    {
        if ($request -> ajax())
        {

            $transfer_id    = $request -> id;
            $status         = $request -> status;

            $transfer = InternalTransfer::findOrFail($transfer_id);
            $from_branch    = $transfer -> from_branch;
            $to_branch      = $transfer -> to_branch;
            $code           = $transfer -> code;
            $quantity       = $transfer -> quantity;

            if ($transfer)
            {

                $transfer -> update(['status' => $status]);

                if ($status == 'accepted')
                {
                    $q = new ChangeQuantity();
                    $q -> decreaseQuantity($from_branch, $code, $quantity);

                    $product_exist = Product::where(['code'=> $code, 'branch_id'=>$to_branch])->first();

                    if ($product_exist)
                    {
                        $q -> increaseQuantity($to_branch, $code, $quantity);
                        $transfer -> update(['sub_category_id' => $request ->sub_category_id]);
                        $product_exist -> update(['sub_category_id' => $request ->sub_category_id]);
                    }else
                    {
                       Product::create(collect($transfer)->toArray()+['branch_id' => $to_branch]);
                       return 'done';
                    }



                }elseif ($status == 'rejected')
                {
                    $transfer -> delete();
                }
            }

            return response() -> json('success', 200);
        }
    }

}

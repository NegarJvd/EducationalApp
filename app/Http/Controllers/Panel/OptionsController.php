<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Order;
use App\Models\ShippingCost;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class OptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $permissions = [
            'options',
            'shipping_cost_option_show',
            'shipping_cost_option_store',
            'shipping_cost_option_update',
            'shipping_cost_option_delete',
            'maximum_number_of_orders',
        ];

        foreach ($permissions as $permission) {
            $array = Permission::where('name', $permission)->get();
            if(count($array) == 0){
                Permission::create(['name' => $permission]);
            }
        }


        $this->middleware('permission:options|shipping_cost_option_show|shipping_cost_option_store|shipping_cost_option_update|shipping_cost_option_delete|maximum_number_of_orders');
        $this->middleware('permission:shipping_cost_option_show', ['only' => ['shipping_cost_option_show']]);
        $this->middleware('permission:shipping_cost_option_store', ['only' => ['shipping_cost_option_store']]);
        $this->middleware('permission:shipping_cost_option_update', ['only' => ['shipping_cost_option_update']]);
        $this->middleware('permission:shipping_cost_option_delete', ['only' => ['shipping_cost_option_delete']]);
        $this->middleware('permission:options', ['only' => ['options']]);
        $this->middleware('permission:maximum_number_of_orders', ['only' => ['maximum_number_of_orders']]);
    }

    public function shipping_cost_option_show(Request $request){
        $shipping_costs = ShippingCost::orderBy('created_at', 'asc')->get();

        if($request->wantsJson()){
            return $this->success($shipping_costs, "لیست هزینه های پیک");
        }

        return view('panel.options.shipping_cost_option', compact('shipping_costs'));
    }

    public function shipping_cost_option_store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'coordinates' => ['required', 'string'],
        ]);

        $shipping_cost = ShippingCost::create($request->only('name', 'shipping_cost', 'coordinates'));

        return $this->success($shipping_cost, "منطقه با موفقیت ذخیره شد.");
    }

    public function shipping_cost_option_update(Request $request, $id){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'shipping_cost' => ['required', 'numeric', 'min:0'],
        ]);

        $shipping_cost = ShippingCost::find($id);

        if(!$shipping_cost) return $this->customError("یافت نشد.", 404);

        $shipping_cost->update($request->only('name', 'shipping_cost'));

        return $this->success($shipping_cost, "منطقه با موفقیت ویرایش شد.");
    }

    public function shipping_cost_option_destroy($id){
        $shipping_cost = ShippingCost::find($id);

        if(!$shipping_cost) return $this->customError("یافت نشد.", 404);

        $shipping_cost->delete();
        return $this->success("منطقه با موفقیت حذف شد.");
    }

    public function options(){
        $today_orders_statistics = Order::today_orders_statistics();

        $maximum_number_of_orders = $today_orders_statistics['maximum_number_of_orders'];
        $products_count = $today_orders_statistics['today_products_count'];
        $orders_percent = $today_orders_statistics['percent'];

        $options = [
            'maximum_number_of_orders' => [
                'value' =>  $maximum_number_of_orders,
                'orders_count' => $products_count,
                'orders_percent' => $orders_percent
            ],
        ];

        return view('panel/options/options', compact('options'));
    }

    public function maximum_number_of_orders(Request $request){
        $products_count = Order::today_orders_statistics()['today_products_count'];
        if($products_count > $request->get('maximum_number_of_orders')) return redirect()->back()->with("error", "حداکثر تعداد سفارشات امروز نمی تواند عددی کمتر از تعداد سفارشات ثبت شده امروز باشد.");

        $maximum_number_of_orders = Option::where('key', 'maximum_number_of_orders')->first();

        if (!$maximum_number_of_orders)
        {
            $maximum_number_of_orders = new Option(['key' => 'maximum_number_of_orders']);
        }

        $maximum_number_of_orders->value = $request->get('maximum_number_of_orders');
        $maximum_number_of_orders->save();

        return redirect()->back()->with("success", "حداکثر تعداد سفارشات در روز ثبت شد.");
    }

}

<?php

namespace App\Modules\Order\Controllers\Backend;

use Illuminate\Http\Request;
use App\Libraries\Encryption;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\DataTables\Backend\Order\OrderDataTable;
use App\Modules\Order\Models\Order;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render("Order::backend.order.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $data['orderStatus'] = ['processing' => 'Processing','completed'  => 'Completed','cancelled'  => 'Cancelled'];
      $decodedId = Encryption::decodeId($id);
      $data['order'] = Order::find($decodedId);
      return view("Order::backend.order.modal.order-status-modal", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      try {
          $validation = Validator::make($request->all(), [
              'order_status' => 'required|in:processing,completed,cancelled',
          ]);

          if ($validation->fails()) {
              return response()->json([
                  'success' => false,
                  'error' => $validation->errors()
              ]);
          }

          $decodedId           = Encryption::decodeId($id);
          $order               =  Order::find($decodedId);
          $order->order_status = $request->input('order_status');
          $order->save();

          return response()->json([
              'success' => true,
              'status'  => 'Order Status updated successfully.',
              'link'    => route('admin.orders.index')
          ]);
      } catch (\Exception $e) {
          return response()->json([
              'error' => true,
              'status' => $e->getMessage()
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\DataTables\Backend\Order;

use App\Libraries\Encryption;
use App\Modules\Order\Models\Order;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class OrderDataTable extends DataTable
{

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('action', function ($data) {
                $actionBtn = '<a href="/admin/orders/'.Encryption::encodeId($data->id).'" class="btn btn-xs btn-info" title="Show"><i class="fa fa-eye"></i> View</a> ';
                 $actionBtn .= '<a href="'.route('admin.orders.edit',Encryption::encodeId($data->id)).'" data-toggle="modal" data-target="#AppModal" class="AppModal btn btn-xs btn-success" title="Order Status"><i class="fa fa-edit"></i> Order Status</a> ';
                $actionBtn .= ' <a href="/admin/orders/'.Encryption::encodeId($data->id).'/delete/" class="btn btn-xs btn-danger action-delete" title="Delete"><i class="fa fa-trash"></i> Delete</a>';
                return $actionBtn;
            })
            ->editColumn('expired_date', function ($data) {
               return ($data->expired_date) ?  Carbon::parse($data->expired_date)->format('d F,Y'): '-';
           })
           ->addColumn('order_status',function($data){
                if($data->order_status == 'processing')
                    return "<label class='badge badge-info'>Processing</label>";
                else if($data->order_status == 'completed')
                    return "<label class='badge badge-success'>Completed</label>";
                else
                    return "<label class='badge badge-danger'>cancelled</label>";
            })
           ->addColumn('status',function($data){
                if($data->status == 1){
                    return "<label class='badge badge-success'>Seen</label>";
                }
                return "<label class='badge badge-danger'>Unseen</label>";
            })

            ->rawColumns(['status','action','order_status'])
            ->make(true);

    }

    /**
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     * @internal param \App\Models\AgentBalanceTransactionHistory $model
     */
    public function query()
    {
        $query = Order::getOrderList();
        $data = $query->select([
            'orders.*'
        ]);
        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom'         => 'Blfrtip',
                'responsive'  => true,
                'autoWidth'   => false,
                'paging'      => true,
                "pagingType"  => "full_numbers",
                'searching'   => true,
                'info'        => true,
                'searchDelay' => 350,
                "serverSide"  => true,
                'order'       => [[1, 'asc']],
                'buttons'     => ['excel','csv', 'print', 'reset', 'reload'],
                'pageLength'  => 10,
                'lengthMenu'  => [[10, 20, 25, 50, 100, 500, -1], [10, 20, 25, 50, 100, 500, 'All']],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'order_number'           => ['data' => 'order_number', 'name' => 'order_number', 'orderable' => true, 'searchable' => true],
            'tax_amount'             => ['data' => 'tax_amount', 'name' => 'tax_amount', 'orderable' => true, 'searchable' => true],
            'discount_amount'        => ['data' => 'discount_amount', 'name' => 'discount_amount', 'orderable' => true, 'searchable' => true],
            'total_amount'           => ['data' => 'total_amount', 'name' => 'total_amount', 'orderable' => true, 'searchable' => true],
            'paid_amount'            => ['data' => 'paid_amount', 'name' => 'paid_amount', 'orderable' => true, 'searchable' => true],
            'payment_status'         => ['data' => 'payment_status', 'name' => 'payment_status', 'orderable' => true, 'searchable' => true],
            'order_status'           => ['data' => 'order_status', 'name' => 'order_status', 'orderable' => true, 'searchable' => true],
            'status'                 => ['data' => 'status', 'name' => 'status', 'orderable' => true, 'searchable' => true],
            'action'                 => ['searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'order_list_' . date('Y_m_d_H_i_s');
    }
}

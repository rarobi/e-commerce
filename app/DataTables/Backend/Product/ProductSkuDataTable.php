<?php

namespace App\DataTables\Backend\Product;

use App\Libraries\Encryption;
use App\Modules\Product\Models\ProductSku;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;

class ProductSkuDataTable extends DataTable
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
                $actionBtn = '<a href="/admin/product/skus/' . Encryption::encodeId($data->id) . '/edit/" class="btn btn-sm btn-primary" title="Edit Product Sku"><i class="fa fa-edit"></i> Edit</a> ';
                $actionBtn .= '<a href="/admin/product/skus/' . Encryption::encodeId($data->id) . '/delete/" class="btn btn-sm btn-danger action-delete" title="Delete Product Sku"><i class="fa fa-trash"></i> Delete</a> ';
                return $actionBtn;
            })
            ->addColumn('status', function ($data) {
                return ($data->status == 1) ? "<label class='badge badge-success'> Active </label>" : "<label class='badge badge-danger'> Inactive </label>";
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     * @internal param \App\Models\AgentBalanceTransactionHistory $model
     */
    public function query()
    {
        $query = ProductSku::getProductSkuList();
        $data = $query->select([
            'product_skus.*'
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
                'dom' => 'Blfrtip',
                'responsive' => true,
                'autoWidth' => false,
                'paging' => true,
                "pagingType" => "full_numbers",
                'searching' => true,
                'info' => true,
                'searchDelay' => 350,
                "serverSide" => true,
                'order' => [[1, 'asc']],
                'buttons' => ['excel', 'csv', 'print', 'reset', 'reload'],
                'pageLength' => 10,
                'lengthMenu' => [[10, 20, 25, 50, 100, 500, -1], [10, 20, 25, 50, 100, 500, 'All']],
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
            'Name'     => ['data' => 'name', 'name' => 'name', 'orderable' => true, 'searchable' => true],
            'status'   => ['data' => 'status', 'name' => 'status', 'orderable' => true, 'searchable' => true],
            'action'   => ['searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'product_skus_list_' . date('Y_m_d_H_i_s');
    }
}

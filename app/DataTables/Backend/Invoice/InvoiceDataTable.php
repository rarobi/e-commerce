<?php

namespace App\DataTables\Backend\Invoice;

use App\Libraries\Encryption;
use App\Modules\Invoice\Models\Invoice;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;

class InvoiceDataTable extends DataTable
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
                $actionBtn = '<a href="/admin/invoices/' . Encryption::encodeId($data->id) . '/edit/" class="btn btn-sm btn-primary" title="Edit Invoice"><i class="fa fa-edit"></i> Edit</a> ';
                $actionBtn .= '<a href="/admin/invoices/' . Encryption::encodeId($data->id) . '/delete/" class="btn btn-sm btn-danger action-delete" title="Delete Invoice"><i class="fa fa-trash"></i> Delete</a> ';
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
        $query = Invoice::getInvoiceList();
        $data = $query->select([
            'invoices.*'
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
            'order_id'       => ['data' => 'order_id', 'name' => 'order_id', 'orderable' => true, 'searchable' => true],
            'invoice_number' => ['data' => 'invoice_number', 'name' => 'invoice_number', 'orderable' => true, 'searchable' => true],
            'invoice_path'   => ['data' => 'invoice_path', 'name' => 'invoice_path', 'orderable' => true, 'searchable' => true],
            'status'         => ['data' => 'status', 'name' => 'status', 'orderable' => true, 'searchable' => true],
            'action'         => ['searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'invoices_list_' . date('Y_m_d_H_i_s');
    }
}

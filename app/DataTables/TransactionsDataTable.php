<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
Use App\Models\Transaction;

class TransactionsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn("date",function($q){
                return $q->date->format("d-m-Y");
            })
            ->editColumn("amount",function($q){
                return $q->amount/100;
            })->filter(function ($query) {
                if (request()->has('date_start') && request()->has('date_end')) {
                    $query->whereBetween('date', [request('date_start'), request('date_end')]);
                }
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Printer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transaction $transaction)
    {
        $query = $transaction->newQuery();
        switch(request('status_filter')){
            case "1":{
                $query->where('finished',0);
                break;
            }
            case "2":{
                $query->where('finished',1);
                break;
            }
        }
        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('transaction-table')
            ->columns($this->getColumns())
            ->ajax(['url' => '', 'data' => 'function (d) { CustomDataTable.ajax(d) }'])
            ->parameters(getButtons());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('date')->title(__('Datum'))->render("render.date(data, type, full)"),
            Column::make('iban')->title(__('Iban')),
            Column::make('name')->title(__('Naam')),
            Column::make('amount')->title(__('Bedrag')),
            Column::make('description')->title(__('Omschrijving')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Transactions_'.date('YmdHis');
    }
}

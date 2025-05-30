<?php

namespace App\DataTables;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CustomersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('no', 'No')
            ->setRowId('id')
            ->addColumn('action', function ($data)
            {
                return '
                <form action="' . route('customer.destroy', $data->id) . '" method="POST" id="dept-table">
                <a href = "' . route('customer.show', $data->id) . '" class="btn btn-primary">' . '<i class="fas fa-pencil"></i>' . '</a>
                ' . csrf_field() . '
                ' . method_field("DELETE") . '
                 <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                </form>
                ';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Customer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Customer $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('customers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->language([
                'search' => 'Pencarian',
                'paginate' => [
                    'previous' => 'Sebelumnya',
                    'next' => 'Selanjutnya',
                    'show' => 'Menampilkan'
                ],
                'info' => [
                    "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                ],
                'lengthMenu' => "Menampilkan _MENU_ data",
                'infoEmpty' => "Menampilkan 0 sampai 0 dari 0 data",
                'zeroRecords' => "Data tidak ditemukan",
                "infoFiltered" =>   "(menampikan dari _MAX_ total data)",
            ])
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::computed('no')->render('meta.row + meta.settings._iDisplayStart + 1;'),
            Column::make('email')->title('Email'),
            Column::make('fullname')->title('Nama Lengkap'),
            Column::computed('action')->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Customers_' . date('YmdHis');
    }
}

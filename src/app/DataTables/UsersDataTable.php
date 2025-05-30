<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class UsersDataTable extends DataTable
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
                <form action="' . route('user.destroy', $data->id) . '" method="POST" id="dept-table">
                <a href = "' . route('user.show', $data->id) . '" class="btn btn-primary">' . '<i class="fas fa-pencil"></i>' . '</a>
                ' . csrf_field() . '
                ' . method_field("DELETE") . '
                 <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                </form>
                ';
            })
            ->filterColumn('nik', function ($query, $keyword)
            {
                $sql = "nik like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('fullnm', function ($query, $keyword)
            {
                $sql = "fullname like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('admin', function ($query, $keyword)
            {
                $sql = "(case when users.admin is true then 'Ya' else 'Tidak' end) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->select('users.id', 'nik', 'email', 'fullname as fullnm', DB::raw('(case when users.admin is true then "Ya" else "Tidak" end) as admin'));
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('departments-table')
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
            Column::make('nik')->title('NIK'),
            Column::make('email')->title('Email'),
            Column::make('fullnm')->title('Nama'),
            Column::make('admin')->title('Admin'),
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
        return 'Users_' . date('YmdHis');
    }
}

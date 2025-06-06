<?php

namespace App\DataTables;

use App\Models\News;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class NewsDataTable extends DataTable
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
                <form action="' . route('news.destroy', $data->id) . '" method="POST" id="dept-table">
                <a href = "' . route('news.show', $data->id) . '" class="btn btn-primary">' . '<i class="fas fa-pencil"></i>' . '</a>
                ' . csrf_field() . '
                ' . method_field("DELETE") . '
                 <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                </form>
                ';
            })
            ->filterColumn('title', function ($query, $keyword)
            {
                $sql = "title like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('content', function ($query, $keyword)
            {
                $sql = "content like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\News $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(News $model): QueryBuilder
    {
        return $model->newQuery()
            ->select([
                'id',
                'title',
                'content',
            ]);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('divisions-table')
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
            Column::make('title')->title('Judul Infomasi'),
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
        return 'News_' . date('YmdHis');
    }
}

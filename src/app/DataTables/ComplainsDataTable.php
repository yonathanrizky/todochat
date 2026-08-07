<?php
namespace App\DataTables;
use App\Models\Complain;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ComplainsDataTable extends DataTable
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
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d-m-Y');
            })
            ->editColumn('status', function ($data) {
                return $data->status
                    ? '<span class="badge badge-success">Selesai</span>'
                    : '<span class="badge badge-warning">Diproses</span>';
            })
            ->addColumn('action', function ($data) {
                
                if ($data->status) {
                    return '
                    <form action="' . route('complain.destroy', $data->id) . '" method="POST" id="dept-table">
                    <a href = "' . route('complain.show', $data->id) . '" class="btn btn-primary btn-sm mr-1">' . '<i class="fas fa-eye"></i>' . '</a>
                    ' . csrf_field() . '
                    ' . method_field("DELETE") . '
                     <button type="submit" class="btn btn-danger btn-sm mr-1"><i class="fas fa-trash"></i></button>
                    </form>
                    ';    
                }
                else {
                    $btn = '<a href="' . route('complain.show', $data->id) . '" class="btn btn-primary btn-sm mr-1"><i class="fas fa-eye"></i></a>';    
                }

                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->filterColumn('description', function ($query, $keyword) {
                $sql = "description like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('ticket_num', function ($query, $keyword) {
                $sql = "ticket_num like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('status', function ($query, $keyword) {
                $keyword = strtolower(trim($keyword));

                if (str_contains('selesai', $keyword)) {
                    $query->orWhere('status', true);
                }

                if (str_contains('diproses', $keyword)) {
                    $query->orWhere('status', false);
                }
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Complain $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Complain $model): QueryBuilder
    {
        $customer_id = Auth::guard('web')->user()->id;
        return $model->newQuery()
            ->where('customer_id', $customer_id)
            ->select([
                'id',
                'description',
                'ticket_num',
                'status',
                'created_at'
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
            Column::make('ticket_num')->title('Nomor Tiket'),
            Column::make('created_at')->title('Tanggal'),
            Column::make('status')->title('Status'),
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
        return 'Complains_' . date('YmdHis');
    }
}

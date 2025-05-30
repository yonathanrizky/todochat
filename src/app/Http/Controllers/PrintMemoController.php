<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use \Mpdf\Mpdf as PDF;
use App\Models\Pegawai;
use App\Models\MemoType;
use App\Models\MemoSender;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PrintMemoController extends Controller
{
    public function show($id)
    {
        $memo = Memo::find($id);
        $memoType = MemoType::find($memo->memo_type_id);
        $pegawaiId = Auth::user()->pegawai_id;
        $pegawaiCode = Auth::user()->pegawai_code;

        $memo->format_romawi = $this->bulan_romawi($memo->created_at->format('F'));

        $noData = [
            'comment' => '',
            'fullname' => '',
            'department_name' => '',
            'position' => '',
            'code' => '',
            'status' => '',
            'signature' => ''
        ];
        $noData = (object) $noData;

        /* bypass langsung direksi */
        $direksi = DB::select("
        select e.id, p.code
        from employees e
        join positions p on p.id = e.position_id
        where p.code = '1'
        ")[0];

        $wheresJoin = "approved_by_level_1 = ms.user_id";
        if (!$memo->department_id && $direksi->code == '1' && $memo->pic == $direksi->id)
            $wheresJoin = "approved_by_level_3 = ms.user_id";

        $c = DB::select("select ms.comment, e.fullname, d.description as department_name, p.description as position,
        p.code,
        case
            when p.code = '1' and m.pic = $direksi->id and p.code = '{$direksi->code}' and m.department_id is null then concat(mst.description, ' - 1')
            when p.code = '1' then concat(mst.description, ' - 2')
            when p.code = '2' then concat(mst.description, ' - 1')
            else mst.description
        end as status, e.signature
        from memo_approvals ap
        join memo_comments ms on ms.memo_id = ap.memo_id and $wheresJoin
        join employees e on e.id = ms.user_id
        join departments d on d.id = e.department_id
        join positions p on p.id = e.position_id
        join memo_status mst on mst.code = ms.status
        join memos m on m.id = ms.memo_id
        where ap.memo_id = {$id} and ms.status = 7");
        $comments[0] = isset($c[0]) ? $c[0] : $noData;

        // if (!$memo->department_id && $direksi->code != '1')
        if ($direksi->id != $memo->pic)
        {
            $comments[1] = DB::select("select ms.comment, e.fullname, d.description as department_name, p.description as position,
            p.code,
            case
                when p.code = '1' then concat(mst.description, ' - 2')
                when p.code = '2' then concat(mst.description, ' - 1')
                else mst.description
            end as status, e.signature
            from memo_approvals ap
            join memo_comments ms on ms.memo_id = ap.memo_id and approved_by_level_2 = ms.user_id
            join employees e on e.id = ms.user_id
            join departments d on d.id = e.department_id
            join positions p on p.id = e.position_id
            join memo_status mst on mst.code = ms.status
            where ap.memo_id = {$id} and ms.status = 7")[0];
        }

        if ($memoType->approval)
        {
            $comments[2] = DB::select("select ms.comment, e.fullname, d.description as department_name, p.description as position,
            p.code,
            case
                when p.code = '1' then concat(mst.description, ' - 2')
                when p.code = '2' then concat(mst.description, ' - 1')
                else mst.description
            end as status, e.signature
            from memo_approvals ap
            join memo_comments ms on ms.memo_id = ap.memo_id and approved_by_level_3 = ms.user_id
            join employees e on e.id = ms.user_id
            join departments d on d.id = e.department_id
            join positions p on p.id = e.position_id
            join memo_status mst on mst.code = ms.status
            where ap.memo_id = {$id} and ms.status = 7")[0];
        }

        $senders = MemoSender::where('memo_id', $id)->get()[0];
        $sender = DB::select("
            select m.fullname, d.description as description, p.description as position
            from employees m
            join departments d on d.id = m.department_id
            join positions p on p.id = m.position_id
            where m.id = {$senders->from}
            ")[0];

        $memo->code = $memoType->code;
        $memo->approval = $memoType->approval;

        if ($memo->department_id)
        {
            $receiver = DB::select("
            select fullname, description
            from employees m
            join departments p on p.id = m.department_id
            where m.id = {$memo->pic}
            ")[0];
        }
        else
        {
            $receiver = DB::select("
            select fullname, description
            from employees m
            join departments p on p.id = m.department_id
            where m.id = {$senders->to}
            ")[0];
        }

        // Setup a filename 
        $documentFileName = $memo->title . ".pdf";

        // Create the mPDF document
        $document = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '10',
            'margin_bottom' => '10',
            'margin_footer' => '20',
            'setAutoBottomMargin' => 'stretch'
        ]);
        $document->curlAllowUnsafeSslRequests = true;
        $document->showImageErrors = true;
        $document->debug = true;

        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $documentFileName . '"'
        ];

        // return view('pages.memo.print.show', ['type_menu' => 'memo_status', 'memo' => $memo, 'sender' => $sender, 'receiver' => $receiver, 'comments' => $comments, 'sender' => $sender,]);

        $document->WriteHTML(view('pages.memo.print.show', ['type_menu' => 'memo_status', 'memo' => $memo, 'sender' => $sender, 'receiver' => $receiver, 'comments' => $comments, 'sender' => $sender,]));
        // $document->SetHTMLFooter(view('pages.memo.print.footer', ['comments' => $comments, 'sender' => $sender,]));
        // Save PDF on your public storage 
        Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));

        // Get file back from storage with the give header informations
        return Storage::disk('public')->download($documentFileName, 'Request', $header); //
    }

    public function bulan_romawi($bulan)
    {
        switch ($bulan)
        {
            case 'January':
            case 'Januari':
                return 'I';
            case 'February':
            case 'Februari':
                return 'II';
            case 'March':
            case 'Maret':
                return 'III';
            case 'April':
                return 'IV';
            case 'May':
            case 'Mei':
                return 'V';
            case 'June':
            case 'Juni':
                return 'VI';
            case 'July':
            case 'Juli':
                return 'VII';
            case 'August':
            case 'Agustus':
                return 'VIII';
            case 'September':
                return 'IX';
            case 'October':
            case 'Oktober':
                return 'X';
            case 'November':
                return 'XI';
            case 'December':
            case 'Desember':
                return 'XII';
            default:
                return '';
        }
    }

    public function bulanIndonesia($tanggalInggris)
    {
        $bulanInggris = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];
        return str_replace(array_keys($bulanInggris), array_values($bulanInggris), $tanggalInggris);
    }
}

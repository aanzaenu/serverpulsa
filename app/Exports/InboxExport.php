<?php

namespace App\Exports;

use App\Inbox;
use App\User;
use App\Terminal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class InboxExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    function __construct($op, $from, $to, $terminals) {
        $this->op = $op;
        $this->from = $from;
        $this->to = $to;
        $this->terminals = $terminals;
    }
    public function collection()
    {
        $model = Inbox::whereNotNull('code');
        if(!empty($this->op))
        {
            $model->where('op', $this->op);
        }
        if(!empty($this->terminals))
        {
            $model->where('terminals', $this->terminals);
        }
        if(!empty($this->from) && !empty($this->to))
        {
            $from = $this->from.' 00:00:01';
            $to = $this->to.' 23:23:59';
            $d_from = strtotime($from);
            $d_to = strtotime($to);
            $sfrom = date('Y-m-d H:i:s', $d_from);
            $sto = date('Y-m-d H:i:s', $d_to);

            $model->where('tanggal', '>=', $sfrom);
            $model->where('tanggal', '<=', $sto);
        }
        $model->orderBy('code', 'DESC');

        $lists = $model->get();
        foreach($lists as $key=> $val)
        {
            $nomer = $key+1;
            $lists[$key]->nomer = $nomer;
            if($val->status == 0)
            {
                $lists[$key]->status = 'Belum Diproses';
            }else{
                $lists[$key]->status = 'Done';
            }
            if(User::find($val->op))
            {
                $lists[$key]->op = User::find($val->op)->name;
            }else{
                $lists[$key]->op = '-';
            }
            $lists[$key]->created_at = '';
            $lists[$key]->updated_at = '';
            $lists[$key]->terminals = '-';
            if(Terminal::where('terminal_id', $val->terminals)->first())
            {
                $lists[$key]->terminals = Terminal::where('terminal_id', $val->terminals)->first()->name;
            }
            
        }
        return $lists;
    }
    public function map($user): array
    {
        return [
            $user->nomer,
            $user->code,
            $user->sender,
            $user->message,
            $user->op,
            $user->status,
            $user->terminals,
            $user->tanggal,
        ];
    }
    public function headings(): array
    {

        return [
            '#',
            'Kode',
            'Pengirim',
            'Pesan',
            'Operator',
            'Status',
            'Terminal',
            'Tanggal'
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 20,
            'D' => 80,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'G' => 30,
        ];
    }
}

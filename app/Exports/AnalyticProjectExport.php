<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnalyticProjectExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $projects;
    protected $dates;
    protected $projectScores;

    public function __construct($projects, $dates, $projectScores)
    {
        $this->projects = $projects;
        $this->dates = $dates;
        $this->projectScores = $projectScores;
    }

    public function view(): View
    {
        return view('admin-evaluations.exports.analytic-project', [
            'projects' => $this->projects,
            'dates' => $this->dates,
            'projectScores' => $this->projectScores
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}

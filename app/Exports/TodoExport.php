<?php

namespace App\Exports;

use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TodoExport implements
    FromCollection,
    ShouldAutoSize,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithStyles,
    WithEvents,
    WithTitle
{
    use Exportable, RegistersEventListeners;

    protected AnonymousResourceCollection $todos;

    public function __construct(AnonymousResourceCollection $collection) {
        $this->todos = $collection;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function collection(): AnonymousResourceCollection
    {
        return $this->todos;
    }

    public function title(): string
    {
        return 'Rekap Data Todo';
    }

    public function headings(): array
    {
        return [
            'Title',
            'Assignee',
            'Due Date',
            'Time Tracked (menit)',
            'Status',
            'Priority',
        ];
    }

    public function map($todo): array
    {
        return [
            $todo->title,
            $todo->assignee,
            Date::dateTimeToExcel($todo->due_date),
            $todo->time_tracked,
            $todo->status->value,
            $todo->priority->value,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * Apply general sheet styling (header, borders, etc.)
     */
    public function styles(Worksheet $sheet): void
    {
        $highestRow = $sheet->getHighestDataRow();

        // Style untuk header row
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => Color::COLOR_WHITE],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => Color::COLOR_BLACK],
            ],
        ]);

        // Border untuk semua cell
        $sheet->getStyle("A1:F{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }

    /**
     * Gunakan AfterSheet event untuk membuat summary row
     */
    public static function afterSheet(AfterSheet $event): void
    {
        // Get row index
        $sheet = $event->sheet->getDelegate();
        $highestRow = $sheet->getHighestDataRow();
        $summaryRow = $highestRow + 1;

        // Freeze header row
        $sheet->freezePane('A2');

        // Summary label
        $sheet->setCellValue("A{$summaryRow}", 'SUMMARY:');

        // Total time tracked
        $sheet->setCellValue("C{$summaryRow}", 'Total Time Tracked:');
        $sheet->setCellValue("D{$summaryRow}", "=SUM(D2:D{$highestRow})");

        // Total Todo's
        $sheet->setCellValue("E{$summaryRow}", 'Total Todos:');
        $sheet->setCellValue("F{$summaryRow}", "=COUNTA(A2:A{$highestRow})");

        // Style untuk summary row
        $sheet->getStyle("A{$summaryRow}:F{$summaryRow}")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}

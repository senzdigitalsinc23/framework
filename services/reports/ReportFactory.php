<?php
namespace Services\Reports;

use Services\Reports\PdfReport;
use Services\Reports\ReportInterface;

class ReportFactory {
    public static function create(string $type): ReportInterface {
        return match (strtolower($type)) {
            'pdf'   => new PdfReport(),
            'word'  => new WordReport(),
            'excel' => new ExcelReport(),
            default => throw new \InvalidArgumentException("Unsupported report type: $type")
        };
    }
}

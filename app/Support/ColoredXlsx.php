<?php

namespace App\Support;

use RuntimeException;
use ZipArchive;

class ColoredXlsx
{
    /**
     * @param  list<list<array{v: string, s?: int}>>  $rows  0-indexed rows/cells
     * @param  list<string>  $merges  e.g. A1:L1
     * @param  array<int, float>  $colWidths  1-indexed column widths
     */
    public function build(string $sheetName, array $rows, array $merges = [], array $colWidths = []): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('PHP ZipArchive is required to generate Excel files.');
        }

        $sheetName = $this->safeSheetName($sheetName);
        $tmp = tempnam(sys_get_temp_dir(), 'bnsx');
        if ($tmp === false) {
            throw new RuntimeException('Unable to create a temporary Excel file.');
        }

        $zip = new ZipArchive();
        if ($zip->open($tmp, ZipArchive::OVERWRITE) !== true) {
            @unlink($tmp);
            throw new RuntimeException('Unable to write the Excel file.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypes());
        $zip->addFromString('_rels/.rels', $this->rootRels());
        $zip->addFromString('xl/workbook.xml', $this->workbook($sheetName));
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRels());
        $zip->addFromString('xl/styles.xml', $this->styles());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->sheet($rows, $merges, $colWidths));
        $zip->close();

        $binary = file_get_contents($tmp);
        @unlink($tmp);

        if ($binary === false) {
            throw new RuntimeException('Unable to read the generated Excel file.');
        }

        return $binary;
    }

    /** Style: navy title */
    public const S_BRAND = 1;

    /** Style: red subtitle */
    public const S_SUBTITLE = 2;

    /** Style: peach meta */
    public const S_META = 3;

    /** Style: navy header */
    public const S_HEAD = 4;

    /** Style: blue session */
    public const S_SESSION = 5;

    /** Style: green payment done */
    public const S_PAID = 6;

    /** Style: orange not paid */
    public const S_UNPAID = 7;

    /** Style: green row */
    public const S_ROW_PAID = 8;

    /** Style: orange row */
    public const S_ROW_UNPAID = 9;

    /** Style: amount */
    public const S_AMOUNT = 10;

    /** Style: stat label */
    public const S_STAT_LABEL = 11;

    /** Style: stat value */
    public const S_STAT_VALUE = 12;

    /** Style: text (mobile / reg no) */
    public const S_TEXT = 13;

    /** Style: text on paid row */
    public const S_TEXT_PAID = 14;

    /** Style: text on unpaid row */
    public const S_TEXT_UNPAID = 15;

    public function cell(string $value, int $style = 0): array
    {
        return ['v' => $value, 's' => $style];
    }

    private function contentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'</Types>';
    }

    private function rootRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';
    }

    private function workbook(string $sheetName): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            .'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="'.$this->xml($sheetName).'" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>';
    }

    private function workbookRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            .'</Relationships>';
    }

    private function styles(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<numFmts count="1"><numFmt numFmtId="164" formatCode="@"/></numFmts>'
            .'<fonts count="5">'
            .'<font><sz val="11"/><color theme="1"/><name val="Calibri"/></font>'
            .'<font><b/><sz val="16"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            .'<font><b/><sz val="13"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            .'<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            .'<font><b/><sz val="11"/><color rgb="FF047857"/><name val="Calibri"/></font>'
            .'</fonts>'
            .'<fills count="9">'
            .'<fill><patternFill patternType="none"/></fill>'
            .'<fill><patternFill patternType="gray125"/></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FF0A1D37"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFFF5544"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFFFF4F0"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFDBEAFE"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FF16A34A"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFF97316"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFECFDF5"/></patternFill></fill>'
            .'</fills>'
            .'<borders count="2">'
            .'<border><left/><right/><top/><bottom/><diagonal/></border>'
            .'<border>'
            .'<left style="thin"><color rgb="FFCBD5E1"/></left>'
            .'<right style="thin"><color rgb="FFCBD5E1"/></right>'
            .'<top style="thin"><color rgb="FFCBD5E1"/></top>'
            .'<bottom style="thin"><color rgb="FFCBD5E1"/></bottom>'
            .'<diagonal/>'
            .'</border>'
            .'</borders>'
            .'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            .'<cellXfs count="16">'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyBorder="1"/>'
            .'<xf numFmtId="0" fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="2" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="4" borderId="1" applyFill="1" applyBorder="1"/>'
            .'<xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="5" borderId="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="3" fillId="6" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="3" fillId="7" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="8" borderId="1" applyFill="1" applyBorder="1"/>'
            .'<xf numFmtId="0" fontId="0" fillId="4" borderId="1" applyFill="1" applyBorder="1"/>'
            .'<xf numFmtId="0" fontId="4" fillId="8" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/>'
            .'<xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="3" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="164" fontId="0" fillId="0" borderId="1" applyNumberFormat="1" applyBorder="1"/>'
            .'<xf numFmtId="164" fontId="0" fillId="8" borderId="1" applyNumberFormat="1" applyFill="1" applyBorder="1"/>'
            .'<xf numFmtId="164" fontId="0" fillId="4" borderId="1" applyNumberFormat="1" applyFill="1" applyBorder="1"/>'
            .'</cellXfs>'
            .'</styleSheet>';
    }

    /**
     * @param  list<list<array{v: string, s?: int}>>  $rows
     * @param  list<string>  $merges
     * @param  array<int, float>  $colWidths
     */
    private function sheet(array $rows, array $merges, array $colWidths): string
    {
        $maxCol = 1;
        $maxRow = max(1, count($rows));
        foreach ($rows as $row) {
            $maxCol = max($maxCol, count($row));
        }

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<dimension ref="A1:'.$this->col($maxCol).$maxRow.'"/>'
            .'<sheetViews><sheetView workbookViewId="0"><pane ySplit="6" topLeftCell="A7" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            .'<sheetFormatPr defaultRowHeight="18"/>';

        if ($colWidths !== []) {
            $xml .= '<cols>';
            foreach ($colWidths as $index => $width) {
                $xml .= '<col min="'.$index.'" max="'.$index.'" width="'.$width.'" customWidth="1"/>';
            }
            $xml .= '</cols>';
        }

        $xml .= '<sheetData>';
        foreach ($rows as $r => $cells) {
            $rowNum = $r + 1;
            $height = $rowNum <= 2 ? '28' : '20';
            $xml .= '<row r="'.$rowNum.'" ht="'.$height.'" customHeight="1">';
            foreach ($cells as $c => $cell) {
                $value = (string) ($cell['v'] ?? '');
                $style = (int) ($cell['s'] ?? 0);
                $ref = $this->col($c + 1).$rowNum;
                $xml .= '<c r="'.$ref.'" t="inlineStr" s="'.$style.'"><is><t xml:space="preserve">'.$this->xml($value).'</t></is></c>';
            }
            $xml .= '</row>';
        }
        $xml .= '</sheetData>';

        if ($merges !== []) {
            $xml .= '<mergeCells count="'.count($merges).'">';
            foreach ($merges as $ref) {
                $xml .= '<mergeCell ref="'.$this->xml($ref).'"/>';
            }
            $xml .= '</mergeCells>';
        }

        $xml .= '</worksheet>';

        return $xml;
    }

    private function col(int $index): string
    {
        $name = '';
        while ($index > 0) {
            $index--;
            $name = chr(65 + ($index % 26)).$name;
            $index = intdiv($index, 26);
        }

        return $name;
    }

    private function xml(string $value): string
    {
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $value) ?? $value;

        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function safeSheetName(string $name): string
    {
        $name = trim(str_replace(['\\', '/', '*', '?', ':', '[', ']'], ' ', $name));
        if ($name === '') {
            $name = 'Sheet1';
        }

        return mb_substr($name, 0, 31);
    }
}

$ssXml = [xml](Get-Content 'C:\Users\HP\.gemini\antigravity\brain\0e3a8e5b-a45c-4fed-bb4a-b51f8bb8e968\scratch\excel_dump\xl\sharedStrings.xml')
$strings = @()
foreach ($si in $ssXml.sst.si) {
    if ($si.t) { $strings += $si.t }
    elseif ($si.r) { $strings += ($si.r.t -join '') }
    else { $strings += '' }
}

function DumpSheet($sheetNum, $sheetName) {
    Write-Host "=== SHEET: $sheetName (sheet$sheetNum.xml) ==="
    $path = "C:\Users\HP\.gemini\antigravity\brain\0e3a8e5b-a45c-4fed-bb4a-b51f8bb8e968\scratch\excel_dump\xl\worksheets\sheet$sheetNum.xml"
    if (-not (Test-Path $path)) { return }
    $sheetXml = [xml](Get-Content $path)
    $rows = $sheetXml.worksheet.sheetData.row
    foreach ($r in $rows | Select-Object -First 30) {
        $cellTexts = @()
        foreach ($c in $r.c) {
            $val = $c.v
            if ($c.t -eq 's') { 
                $val = $strings[[int]$val] 
            }
            $cellTexts += "$($c.r): $val"
        }
        Write-Host "Row $($r.r): $($cellTexts -join ' | ')"
    }
}

DumpSheet 6 "PRECIOS DISC"
DumpSheet 3 "BDCURSOS"
DumpSheet 1 "INSCRIPCION"
DumpSheet 4 "IMPRIMIR CARA 1"
DumpSheet 5 "IMPRIMIR CARA 2"


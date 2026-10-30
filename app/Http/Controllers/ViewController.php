<?php

namespace App\Http\Controllers\Dominios\Administracion;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ViewController extends Controller
{
    public function redir()
    {
        return view('layouts.principal');
    }
    public function exportarExcel(Request $request)
    {
        $headers = $request->input('headers');
        $rows = $request->input('rows');
        $name = $request->input('name') ?? 'Hoja1';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($name);

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col.'1', $h['text']);
            $sheet->getStyle($col.'1')->getFont()->setBold(true);
            $col++;
        }

        $rowIndex = 2;
        foreach ($rows as $row) {
            $col = 'A';
            foreach ($headers as $h) {
                $value = $row[$h['value']] ?? '';
                if (($h['type'] ?? '') === 'string') {
                    $sheet->setCellValueExplicit(
                        $col.$rowIndex,
                        (string)$value,
                        DataType::TYPE_STRING
                    );
                    $sheet->getStyle($col.$rowIndex)->setQuotePrefix(true);
                } else {
                    $sheet->setCellValue($col.$rowIndex, $value);
                }
                $col++;
            }
            $rowIndex++;
        }

        foreach (range('A', chr(64 + count($headers))) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $filename = $name.'_'.now()->format('Ymd_His').'.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }
    public function tris()
    {
        $scores = User::where('score','<>',null)
            ->orderBy('score', 'DESC')
            ->select([
                'id',
                'name',
                'score'
            ])
            ->withTrashed()
            ->get()
            ->take(10);
        if(!Auth::user()){
            $pb = [
                'id' => null,
                'name' => null,
                'score' => null
            ];
        }else{
            $pb = User::where('id',Auth::user()->id)
                ->orderBy('score', 'DESC')
                ->select([
                    'id',
                    'name',
                    'score'
                ])
                ->first();
        }
        return view('layouts.tris', ['scores' => $scores, 'pb' => $pb]);
    }
    public function store(Request $request)
    {
        $id = 2502;
        if(Auth::user() != null){
            $id = Auth::user()->id;
        }

        $request->validate([
            'score' => 'required|integer|min:0',
        ]);
        
        $user = User::find($id);

        
        // Solo actualizar si el nuevo puntaje es mayor
        if ($request->score > $user->score) {
            $user->score = $request->score;
            $user->save();
        }
        
        return response()->json([
            'success' => true,
            'newScore' => $user->score,
            'message' => 'Puntuación actualizada correctamente'
        ]);
    }
}

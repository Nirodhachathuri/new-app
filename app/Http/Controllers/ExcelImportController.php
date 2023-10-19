<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Excel;


class ExcelImportController extends Controller
{
    public function index(){
        return view('excel-upload');
    }

    public function import(Request $request){
        
        try {
            
            $validator = Validator::make($request->all(), [
                'excel_file' => 'required|mimes:xlsx,csv',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'File validation failed. Please check the file type and try again.');
            }

        
            // If validation passes, continue with the rest of your code
            $file = $request->file('excel_file');
            // The rest of your code...
            
            if ($file) {
                $data = Excel::toCollection(null, $file);
    
                $flattenedArray = [];
    
                foreach($data as $nestedArray){
                    $flattenedArray = array_merge($flattenedArray, $nestedArray->toArray());
                }
    
                $headers = $flattenedArray[0];
    
                $jsonArray = [];
    
                for($i = 1; $i < count($flattenedArray); $i++){
                    $row = $flattenedArray[$i];
                    $jsonObject = [];
    
                    foreach($headers as $index => $header){
                        $jsonObject[$header] = $row[$index];
                    }
    
                    $jsonArray[] = $jsonObject;
                }
    
                $json = json_encode($jsonArray);
    
                // Save the JSON data to a data.json file in the default folder
                $dataPath = storage_path('/../public/data.json');
                file_put_contents($dataPath, $json);

                return redirect()->route('process-data')->with('success', 'Excel file exported successfully.');
    
            } else {
                return back()->with('error', 'File upload failed.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
}

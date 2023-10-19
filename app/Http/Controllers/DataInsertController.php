<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;


class DataInsertController extends Controller
{
    public function insertData()
    {
        // Define the font path
        $fontPath = public_path('times.ttf');

        // Load the template image
        $templatePath = Storage::disk('public')->path('template.jpeg');
        $template = Image::make($templatePath);

        // Define the directory where the images will be saved
        $outputDirectory = public_path('generated_images');

        if (!is_dir($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        // Read the JSON data

        $jsonPath = Storage::disk('public')->path('data.json');
        $jsonData = json_decode(file_get_contents($jsonPath), true);

        if (!is_array($jsonData)) {
            return 'JSON data could not be loaded or is not in the correct format.';
        }

        $counter = 1;

        // Loop to create and save 500 images
        foreach ($jsonData as $item) {
            if ($counter > 100) {
                break; // Stop after generating 500 images
            }

            $image = clone $template; // Clone the template for each image

            $data = '';

            foreach ($item as $key => $value) {
                // $key is the key, and $value is the corresponding value
                // You can use $key and $value as needed
                $data .= "$key: $value\n";
            }
                    // Customize the data for this image with line breaks
            // $data = "Name: " . $item['Name'] . "\nAge: " . $item['Description'] . "\nPrice: " . $item['Price'];

            // Split the data into lines
            $lines = explode("\n", $data);

            

            $imagePath = Storage::disk('public')->path('template.jpeg');

            // Open the template image
            $img = Image::make($imagePath);

            // Define the initial position
            $x = 400;
            $y = 600;

            // Set the line height (adjust as needed)
            $lineHeight = 34;

            // Insert each line of text
            foreach ($lines as $line) {
                // dd($line);
                $image->text($line, $x, $y, function($font) use ($fontPath) {
                    $font->file($fontPath);
                    $font->size(24);
                    $font->color('#000000');
                   
                });

                // Move to the next line
                $y += $lineHeight;
            }

            // Save the image with a unique filename
            $image->save($outputDirectory . '/image_' . $counter . '.jpeg');
            
            $counter++;
        }


            // Zip the generated images
        $zipFileName = 'generated_images.zip';
        $zipFilePath = public_path($zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $imageFiles = glob($outputDirectory . '/*.jpeg');
            
            foreach ($imageFiles as $imageFile) {
                $zip->addFile($imageFile, basename($imageFile));
            }

            $zip->close();
        }

        // Send the zip file for download
        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);

    }
}

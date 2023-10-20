<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;


class ImageController extends Controller
{
    public function showUploadForm()
    {
        return view('upload');
    }

    public function showUploadImage()
    {
        return view('show');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'template.' . $image->getClientOriginalExtension(); // Ensure the saved image has the correct extension
            $path = $image->storage('', $imageName, 'public'); // Save with the specified name and in the public disk
            // Store the file in the public disk (or other configured disk).
            return redirect()->route('excel-upload')->with('success', 'Image uploaded successfully.');
 
        }


        // return redirect()->back()->with('success', 'Image uploaded successfully.');
        return redirect()->route('image.upload.form')->with('error', 'Image upload failed.');
    }
}

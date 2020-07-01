<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessRequest;
use App\Image;

/**
 * Class ProcessController
 * @package App\Http\Controllers
 */
class ProcessController extends Controller
{
    /**
     * @param ProcessRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\InvalidBase64Data
     */
    public function process(ProcessRequest $request)
    {
        $status = Image::storeImage($request->get('type'), $request->get('image'));

        session()->flash('flash-msg', [
            'success' => $status,
            'message' => $status ? 'Image saved successfully' : 'Image not saved',
        ]);

        return back();
    }
}

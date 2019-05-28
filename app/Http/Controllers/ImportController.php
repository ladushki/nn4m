<?php

namespace App\Http\Controllers;

use App\Forms\UploadForm;
use ImportErrorLogger;
use App\Services\StoreImportService;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class ImportController extends Controller
{

    public function index(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(UploadForm::class, [
            'method' => 'POST',
            'url'    => route('import.upload'),
            'label'  => 'Upload xml file',
            'files'  => true,
        ]);

        return view('import/index', compact('form'));
    }

    public function upload(FormBuilder $formBuilder, Request $request, StoreImportService $importService)
    {
        $form = $formBuilder->create(UploadForm::class);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $destinationPath = 'uploads';
        $file  = $request->file('xml');

        $file->move($destinationPath, $file->getClientOriginalName());
        $filename = public_path('uploads/' . $file->getClientOriginalName());

        $result = $importService->load($filename)->run();

        $logId = ($result && $result->log) ? $result->log->id : 0;

        return \Redirect::route('import.result', $logId)->with('message', 'The document is processed');
    }

    public function result($id = null)
    {
        if (empty($id)) {
            $log = ImportErrorLogger::getLatestLog();
        } else {
            $log = ImportErrorLogger::getLogById($id);
        }

        return view('import.result', compact('log'));
    }
}

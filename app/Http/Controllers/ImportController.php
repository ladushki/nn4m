<?php

namespace App\Http\Controllers;

use App\Forms\UploadForm;
use App\ImportLog;
use App\Repositories\ImportErrorsRepository;
use App\Services\StoreImportService;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class ImportController extends Controller
{
    public $logRepository;
    public $importService;

    public function __construct(StoreImportService $importService, ImportErrorsRepository $logRepository)
    {
        $this->importService = $importService;
        $this->logRepository = $logRepository;
    }

    public function index(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\App\Forms\UploadForm::class, [
            'method' => 'POST',
            'url'    => route('import.upload'),
            'label'  => 'Upload xml file',
            'files'  => true,
        ]);

        return view('import/index', compact('form'));
    }

    public function upload(FormBuilder $formBuilder, Request $request)
    {
        $form = $formBuilder->create(UploadForm::class);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $file = $request->file('xml');

        //Display File Name
        $fileDetails = [
            'filename' => $file->getClientOriginalName(),
            'ext'      => $file->getClientOriginalExtension(),
            'real'     => $file->getRealPath(),
            'size'     => $file->getSize(),
            'mine'     => $file->getMimeType(),
        ];


        $destinationPath = 'uploads';
        $file->move($destinationPath, $file->getClientOriginalName());
        $filename = public_path('uploads/' . $file->getClientOriginalName());

        $status = $this->importService->load($filename)->import();

        $log = $this->logRepository->createLogEntry($status);

        return \Redirect::route('import.result', $log->id)->with('message', 'The document is proccessed');


    }

    public function result($id)
    {
        $log = ImportLog::with('errors')->find($id);

        return view('import.result', compact('log'));
    }
}

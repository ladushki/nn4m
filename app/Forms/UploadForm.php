<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Field;
use Kris\LaravelFormBuilder\Form;

class UploadForm extends Form
{
    public function buildForm()
    {
        $this->add(
            'xml',
            Field::FILE,
            ['rules' => 'required|mimetypes:text/xml,application/xml']
        );
        $this->add('submit', Field::BUTTON_SUBMIT);
    }
}

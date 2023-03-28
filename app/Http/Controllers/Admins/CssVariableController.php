<?php

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admins\CssVariableRequest;
use App\Models\Admins\CssVariable;
use RushApp\Core\Controllers\BaseCrudController;

class CssVariableController extends BaseCrudController
{
  protected string $modelClassController = CssVariable::class;
  protected string|null $storeRequestClass = CssVariableRequest::class;
}

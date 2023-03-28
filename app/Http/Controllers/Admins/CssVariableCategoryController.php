<?php

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admins\CssVariableCategoryRequest;
use App\Models\Admins\CssVariableCategories\CssVariableCategory;
use RushApp\Core\Controllers\BaseCrudController;

class CssVariableCategoryController extends BaseCrudController
{
  protected string $modelClassController = CssVariableCategory::class;
  protected string|null $storeRequestClass = CssVariableCategoryRequest::class;
}

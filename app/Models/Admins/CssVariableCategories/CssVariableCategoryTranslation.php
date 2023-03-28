<?php

namespace App\Models\Admins\CssVariableCategories;

use RushApp\Core\Models\BaseModel;

class CssVariableCategoryTranslation extends BaseModel
{
  protected $fillable = ['name', 'css_variable_category_id', 'language_id'];

  /** @var string[] - for WHERE_LIKE */
  protected array $searchable = ['name'];
}

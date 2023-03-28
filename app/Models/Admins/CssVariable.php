<?php

namespace App\Models\Admins;

use RushApp\Core\Models\BaseModel;

class CssVariable extends BaseModel
{
  protected $fillable = ['css_variable_category_id', 'variable_name', 'variable_value'];

  /** @var string[] - for WHERE_LIKE */
  protected array $searchable = ['css_variable_category_id', 'variable_name', 'variable_value', 'variable_default_value'];
}

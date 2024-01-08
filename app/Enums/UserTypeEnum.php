<?php
  
namespace App\Enums;
use App\Traits\{EnumOptions,EnumValues};
 enum UserTypeEnum:int {    
    use EnumValues;
    use EnumOptions;
    case Admin = 1;
    case SchemeMember = 0;
    case Promoter = 2;
    case Employee = 3;
 }
<?php
  
namespace App\Enums;
use App\Traits\{EnumOptions,EnumValues};
 enum RoleEnum:string {
    use EnumValues;
    use EnumOptions;
    case Admin = 'Admin';
    case SchemeMember = 'Scheme Member';
    case Promoter = 'Promoter';
    case Employee = 'Employee';
}
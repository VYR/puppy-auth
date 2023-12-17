<?php
  
namespace App\Enums;
 enum UserTypeEnum:int {
    case Admin = 1;
    case User = 0;
    case Dealer = 2;
}
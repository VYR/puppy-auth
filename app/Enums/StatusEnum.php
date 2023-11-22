<?php
  
namespace App\Enums;
 
enum StatusEnum:string {
    case Pending = 'pending';
    case Active = 'active';
    case Inactive = 'inactive';
    case Rejected = 'rejected';
}
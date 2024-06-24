<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PacketLog extends Model
{
    use HasFactory;
    protected $fillable = ["action","protocol","source_ip","source_port","destination_ip","destination_port","options"] ;
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Automation extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['conditions'=>'array','actions'=>'array','enabled'=>'boolean']; }
}

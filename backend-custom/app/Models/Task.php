<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Task extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['custom_fields'=>'array','due_at'=>'datetime','started_at'=>'datetime','completed_at'=>'datetime','sla_deadline_at'=>'datetime']; }
}

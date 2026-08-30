<?php
namespace Database\Seeders;
use App\Models\Organization;
use App\Models\User;
use App\Models\Client;
use App\Models\Process;
use App\Models\Team;
use App\Models\Queue;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $org = Organization::create(['name'=>'Demo BPO','slug'=>'demo-bpo','timezone'=>'Asia/Kolkata']);
        $admin = User::create(['organization_id'=>$org->id,'name'=>'Demo Admin','email'=>'admin@demo-bpo.test','password'=>Hash::make('Password123!'),'role'=>'organization_admin','is_active'=>true]);
        $agent = User::create(['organization_id'=>$org->id,'name'=>'Rahul Sharma','email'=>'rahul@demo-bpo.test','password'=>Hash::make('Password123!'),'role'=>'agent','is_active'=>true]);
        $client = Client::create(['organization_id'=>$org->id,'name'=>'Amazon','code'=>'AMZ','email'=>'ops@amazon.test','status'=>'active']);
        $process = Process::create(['organization_id'=>$org->id,'client_id'=>$client->id,'name'=>'Customer Support','code'=>'CS','status'=>'active']);
        $team = Team::create(['organization_id'=>$org->id,'name'=>'Team Alpha','description'=>'Customer support team','status'=>'active']);
        $queue = Queue::create(['organization_id'=>$org->id,'team_id'=>$team->id,'name'=>'Refund Queue','status'=>'active']);
        foreach(range(1,10) as $i) Task::create(['organization_id'=>$org->id,'client_id'=>$client->id,'process_id'=>$process->id,'team_id'=>$team->id,'queue_id'=>$queue->id,'assignee_id'=>$agent->id,'task_number'=>'TK-DEMO'.$i,'title'=>'Demo operational task '.$i,'status'=>$i<5?'completed':'in_progress','priority'=>$i%3===0?'high':'medium','sla_status'=>$i===10?'at_risk':'on_track','due_at'=>now()->addHours($i)]);
    }
}

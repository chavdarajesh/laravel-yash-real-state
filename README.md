
create ticket activity log table 
all update are stored in this 



create task attechment table in that store attenchemnt name with task id 

create clicnt table

admin can create client 
and clicknt can see the prject and task 

clicnt need to selct in project table 

create a timesheet table


time tracker 


work session 
check in 
check out 

work hr 



// app/Http/Controllers/WorkTimeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkTime;
use Carbon\Carbon;

class WorkTimeController extends Controller
{
    public function getTotalWorkTimeForToday()
    {
        $today = Carbon::today();

        $workTimes = WorkTime::whereDate('date', $today)->get();

        $totalMinutes = 0;

        foreach ($workTimes as $workTime) {
            $startTime = Carbon::parse($workTime->start_time);
            $endTime = Carbon::parse($workTime->end_time);
            $totalMinutes += $endTime->diffInMinutes($startTime);
        }

        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;

        return response()->json([
            'total_hours' => $totalHours,
            'total_minutes' => $remainingMinutes
        ]);
    }
}








=============================================================================

create event per min table to store event per min data 


create check in check out page in web 

select proejct select task 

and then able to check in 

and 

need to input some text when it's check out 

for time sheet 



https://preview.themeforest.net/item/timetracker-time-tracking-management-bootstrap5-admin-template/full_screen_preview/36247822


https://angular.envytheme.com/blin/time-tracking


https://themewagon.github.io/brainwave-io/v1.0.0/#!


https://themewagon.github.io/SaasCandy/#

https://themewagon.github.io/revo/index.html#purchase

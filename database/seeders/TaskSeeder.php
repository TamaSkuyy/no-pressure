<?php

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = [
            [
                'task' => 'Complete project',
                'due_date' => '2023-07-15',
                'completed' => 'no',
            ],
            [
                'task' => 'Review documentation',
                'due_date' => '2023-07-10',
                'completed' => 'yes',
            ],
            [
                'task' => 'Test functionality',
                'due_date' => '2023-07-12',
                'completed' => 'yes',
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::create($taskData);
        }
    }
}

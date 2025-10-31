<?php

namespace Database\Seeders;

use App\Enums\QuestionTypeEnum;
use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (QuestionTypeEnum::ALL as $type){
            QuestionType::create(['name'=>$type]);
        }
    }
}

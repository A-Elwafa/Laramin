<?php

use Illuminate\Database\Seeder;
// use faker\Factory as Faker;
use Simoja\Laramin\Models\DataType;
use Simoja\Laramin\Models\DataInfo;

class LaraminDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

         //Get The Models To put In Database
        $models = collect([
                    'Post' => Post::class,
                    'Tag' => Tag::class,
                    'Category' => Category::class
                     ]);

        //Each Model Got His Attribues
        $columns = collect([
               'Post' => [
                    'title' => [
                        'required|max:150',
                        'text'
                        true
                    ],
                    'slug' => [
                        'required|unique:posts',
                        'text'
                        true
                    ],
                    'content' => [
                        'required',
                        'text_area'
                        false
                    ],
                    'description' => [
                        'required|max:250',
                        'text_area'
                        false
                    ],
                    'featured' => [
                        'required|max:154',
                        'checkbox',
                        false
                    ],
                    'image' => [
                        'required|mimes:jpeg,bmp,png',
                        'image',
                        true
                    ],
                    'status' => [
                        'required',
                        'status',
                        true
                    ]
                ],
               'Tag' => [
                    'name' => [
                        'required|max:156',
                        'text',
                        true
                    ],
                    'slug' => [
                        'required|max:100|unique:tags',
                        'text',
                        true
                    ]
                ],
               'Category' => [
                    'name' => [
                        'required|max:156',
                        'text'
                        true
                    ],
                    'slug' => [
                        'required|max:100|unique:categories',
                        'text'
                        true
                    ]
                ]
               ]);

        $this->command->info('Laramin Data Seeder');

        $models->each(function ($model, $index) use ($columns) {
            $this->command->info('Creating Data Type to '.$index);

            $res = DataType::create([
                        'name' => $index,
                        'menu' => true,
                        'model' =>  $index,
                        'slug' => strtolower($index),
                    ]);
            $id = $res->id;
            $columns->filter(function ($item, $key) use ($index, $id) {
                return $key == $index;
            })->Flatmap(function ($item, $key) use ($id) {
                return $item;
            })->each(function ($item, $key) use ($id) {
                $this->command->info('Creating Data Info '.$key.' for '.DataType::find($id)->name);

                DataInfo::create([
                        'data_types_id' => $id,
                        'column' => $key,
                        'type' => $item[1],
                        'validation' => json_encode($item[0]),
                        'display' => $item[2]
                    ]);
            });
        });
    }
}

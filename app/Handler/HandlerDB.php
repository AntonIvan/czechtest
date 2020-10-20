<?php


namespace App\Handler;


class HandlerDB
{
    private $table = "news";

    function save($array) {
        if(!\DB::table($this->table)->where('name', $array['name'])->first()) {
            \DB::table($this->table)->insert(
                [
                    'name' => $array['name'],
                    'text' => $array['text'],
                    'image' => $array['image']
                ]
            );
        }
    }

    function readAll() {
        return \DB::table($this->table)->get();
    }

    function readOne($id) {
        return \DB::table($this->table)->where('id', $id)->first();
    }
}

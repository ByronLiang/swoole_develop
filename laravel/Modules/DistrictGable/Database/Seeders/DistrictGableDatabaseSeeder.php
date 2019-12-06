<?php

namespace Modules\DistrictGable\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\DistrictGable\Entities\District;

class DistrictGableDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        $province = json_decode(file_get_contents(__DIR__.'/json/province.json'));

        $municipalities_id = [
            '110000', //北京
            '120000', //天津
            '500000', //重庆
            '310000', //上海
            '810000', //香港
            '820000', //澳门
        ];

        foreach ($province as $k => $v) {
            $is_city = (bool) in_array($v->id, $municipalities_id);

            District::firstOrCreate([
                'id' => $v->id,
                'name' => $v->name,
                'full_name' => $v->fullname,
                'pinyin' => implode(' ', $v->pinyin),
                'is_city' => $is_city,
                'latitude' => $v->location->lat,
                'longitude' => $v->location->lng,
            ]);
        }

        $city = json_decode(file_get_contents(__DIR__.'/json/city.json'));
        foreach ($city as $k => $v) {
            $is_city = !in_array(substr($v->id, 0, 2).'0000', $municipalities_id);

            District::firstOrCreate([
                'id' => $v->id,
                'name' => $v->name,
                'full_name' => $v->fullname,
                'pinyin' => implode(' ', $v->pinyin),
                'is_city' => $is_city,
                'pid' => substr($v->id, 0, 2).'0000',
                'latitude' => $v->location->lat,
                'longitude' => $v->location->lng,
            ]);
        }

        $area = json_decode(file_get_contents(__DIR__.'/json/area.json'));
        foreach ($area as $k => $v) {
            District::firstOrCreate([
                'id' => $v->id,
                'full_name' => $v->fullname,
                'pid' => substr($v->id, 0, 4).'00',
                'latitude' => $v->location->lat,
                'longitude' => $v->location->lng,
            ]);
        }
    }
}

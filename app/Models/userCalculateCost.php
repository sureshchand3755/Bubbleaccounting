<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userCalculateCost extends Model
{
    use HasFactory;
    protected $table='user_calculate_cost';
    protected $primaryKey='cost_id';
    protected $guarded = ['cost_id'];

    public static function getDetail($select = [], $where = []) {
        if(count($select) > 0) {
            return self::select($select)->where($where)->first();
        }else{
            return self::where($where)->first();
        }
    }
    public static function getDetails($select = [], $where = []) {
        if(count($select) > 0) {
            return self::select($select)->where($where)->get();
        }else{
            return self::where($where)->get();
        }
    }
    public static function insertDetails($data) {
        return self::create($data)->cost_id;
    }
    public static function updateDetails($where, $data) {
        return self::where($where)->update($data);
    }
}

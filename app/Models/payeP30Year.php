<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payeP30Year extends Model
{
    use HasFactory;
    protected $table='paye_p30_year';
    protected $primaryKey='year_id';
    protected $guarded = ['year_id']; 

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
        return self::create($data)->year_id;
    }
    public static function updateDetails($where, $data) {
        return self::where($where)->update($data);
    }
}

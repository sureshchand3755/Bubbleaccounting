<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class EmployerUsers extends Model
{
    use HasFactory;
    protected $table='employer_users';
    protected $primaryKey='id';
    protected $guarded = ['id']; 

    public static function getDetail($select = [], $where = []) {
        if(count($select) > 0) {
            if(Session::has('user_practice_code')) {
                return self::select($select)->where('practice_code',Session::get('user_practice_code'))->where($where)->first();
            }
            elseif(Session::has('payroll_practice_code')) {
                return self::select($select)->where('practice_code',Session::get('payroll_practice_code'))->where($where)->first();
            }
            else{
                return self::select($select)->where($where)->first();
            }
        }else{
            if(Session::has('user_practice_code')) {
                return self::where('practice_code',Session::get('user_practice_code'))->where($where)->first();
            }
            elseif(Session::has('payroll_practice_code')) {
                return self::where('practice_code',Session::get('payroll_practice_code'))->where($where)->first();
            }
            else{
                return self::where($where)->first();
            }
        }
    }
    public static function getDetails($select = [], $where = []) {
        if(count($select) > 0) {
            if(Session::has('user_practice_code')) {
                return self::select($select)->where('practice_code',Session::get('user_practice_code'))->where($where)->first();
            }
            elseif(Session::has('payroll_practice_code')) {
                return self::select($select)->where('practice_code',Session::get('payroll_practice_code'))->where($where)->first();
            }
            else{
                return self::select($select)->where($where)->first();
            }
        }else{
            if(Session::has('user_practice_code')) {
                return self::where('practice_code',Session::get('user_practice_code'))->where($where)->first();
            }
            elseif(Session::has('payroll_practice_code')) {
                return self::where('practice_code',Session::get('payroll_practice_code'))->where($where)->first();
            }
            else{
                return self::where($where)->first();
            }
        }
    }
    public static function insertDetails($data) {
        return self::create($data)->id;
    }
    public static function updateDetails($where, $data) {
        return self::where($where)->update($data);
    }
}

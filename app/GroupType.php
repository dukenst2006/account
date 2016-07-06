<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class GroupType extends Model
{
    const HOMESCHOOL = 1;
    const CHURCH = 2;
    const OTHER = 3;

    protected $guarded = ['id'];
}

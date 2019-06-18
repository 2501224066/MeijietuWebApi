<?php


namespace App\Models\Indent;


use Illuminate\Database\Eloquent\Model;

class IndentItem extends Model
{
    protected $table = 'indent_item';

    protected $primaryKey = 'item_id';

    protected $guarded = [];

    public $timestamps = false;
}
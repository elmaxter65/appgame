<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class Token extends Model
{

    use Sortable;
    

    protected $fillable = [
        'token', 'active','user_id'
    ];


}

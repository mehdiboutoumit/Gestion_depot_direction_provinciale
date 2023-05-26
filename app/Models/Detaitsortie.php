<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detaitsortie extends Model
{
    use HasFactory;
    protected $table = 'Detailsortie';
    protected $primaryKey='iddetailsortie';
    protected $fillable =['codeproduit','nomproduit','quantite','sortie_id'];

}

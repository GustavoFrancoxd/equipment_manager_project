<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'modelo', 'marca', 'nombre', 'tipo_equipo_id', 
        'fecha_fabricacion', 'potencia_kw', 'velocidad_rpm',
        'proveedor', 'informacion_adicional', 'vigencia_anos',
        'requiere_calibracion'
    ];

    public function tipoEquipo()
    {
        return $this->belongsTo(TipoEquipo::class);
    }
}
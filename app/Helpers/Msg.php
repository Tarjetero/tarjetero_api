<?php


namespace App\Helpers;


class Msg
{
    const VALIDATIONS = [
        'required'       => 'El parametro :attribute es requerido.',
        'present'        => 'El parametro :attribute debe estar presente.',
        'integer'        => 'El parametro :attribute debe ser un entero.',
        'email'          => ':attribute no es un correo válido.',
        'digits_between' => ':attribute debe tener entre :min y :max dígitos.',
        'max'            => [
            'numeric' => ':attribute no debe ser mayor a :max.',
            'file'    => ':attribute no debe ser mayor que :max kilobytes.',
            'string'  => ':attribute no debe ser mayor que :max caracteres.',
            'array'   => ':attribute no debe tener más de :max elementos.'
        ],
        'mimes'          => ':attribute debe ser un archivo con formato: :values.',
        'mimetypes'      => ':attribute debe ser un archivo con formato: :values.',
        'min'            => [
            'numeric' => 'El tamaño de :attribute debe ser de al menos :min.',
            'file'    => 'El tamaño de :attribute debe ser de al menos :min kilobytes.',
            'string'  => ':attribute debe contener al menos :min caracteres.',
            'array'   => ':attribute debe tener al menos :min elementos.'
        ],
        'numeric'        => ':attribute debe ser numérico.',
        'uuid'           => 'El campo :attribute debe ser un UUID válido.',
        'required_if'    => 'El campo :attribute es obligatorio cuando :other es :value.',
        'json'           => 'El campo :attribute debe tener una cadena JSON válida.',
        'array'          => ':attribute debe ser un conjunto.',
        'gt'             => [
            'numeric' => 'El campo :attribute debe ser mayor que :value.',
            'file'    => 'El campo :attribute debe tener más de :value kilobytes.',
            'string'  => 'El campo :attribute debe tener más de :value caracteres.',
            'array'   => 'El campo :attribute debe tener más de :value elementos.'
        ],
        'date_format' => 'El campo :attribute debe tener formato yyyy-mm-dd HH:mm:ss.'
    ];
}

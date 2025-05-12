<?php

namespace puma\libreria;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ArchivoService
{ public function validarGramaticaInventada(string $texto): array
    {
    
        $texto = trim($texto);
        $palabras = preg_split('/\s+/', $texto); // Divide el texto en palabras por espacios

        $palabrasCorrectas = [];
        $palabrasIncorrectas = [];

        foreach ($palabras as $palabra) {
            if (!empty($palabra) && preg_match('/^[A-Z][a-z]*$/', $palabra)) {
                $palabrasCorrectas[] = $palabra;
            } elseif (!empty($palabra)) {
                $palabrasIncorrectas[] = $palabra;
            }
        }

        return [
            'valido' => empty($palabrasIncorrectas),
            'correctas' => $palabrasCorrectas,
            'incorrectas' => $palabrasIncorrectas,
        ];
        
    }

  
    public function validarSoloNumeros(string $texto): array
    {
      
        $texto = trim($texto);
        $numerosEncontrados = [];
        $esSoloNumeros = true;

        if (!empty($texto)) {
          
            preg_match_all('/\d+/', $texto, $matches);
            $numerosEncontrados = $matches[0];

           
            if (preg_match('/[^\d\s]/', $texto)) {
                $esSoloNumeros = false;
            } elseif (preg_match('/[^\d]/', str_replace(' ', '', $texto))) {
                // Verificamos si hay no dígitos después de eliminar espacios
                $esSoloNumeros = false;
            } else if (empty(str_replace(' ', '', $texto)) && !empty($texto)) {
                $esSoloNumeros = false; // Si solo hay espacios y no está vacío
            } else if (!empty($numerosEncontrados) && strlen(str_replace($numerosEncontrados, '', str_replace(' ', '', $texto))) > 0) {
                $esSoloNumeros = false; // Si quedan caracteres no numéricos después de quitar los números y espacios
            } else if (empty($texto)) {
                $esSoloNumeros = true; // Considerar vacío como solo números (o puedes cambiarlo a false si prefieres)
            }
        } else {
            $esSoloNumeros = true; // Considerar vacío como solo números
        }

        return [
            'es_solo_numeros' => $esSoloNumeros,
            'numeros_encontrados' => $numerosEncontrados,
        ];  
    }
}
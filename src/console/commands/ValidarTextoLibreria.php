<?php
// php artisan libreria:Validar-Texto " " --tipo=gramatica
//php artisan libreria:Validar-Texto  --archivo=ruta.txt

namespace puma\libreria\Console\Commands;

use puma\libreria\ArchivoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ValidarTextoLibreria extends Command
{
  
    protected $signature = 'libreria:Validar-Texto {texto?} {--archivo=}{--tipo=}';
    

    protected $description = 'Valida un texto o el contenido de un archivo utilizando las funciones de la librería.';

    protected $archivoService;

    public function __construct(ArchivoService $archivoService)
    {
        parent::__construct();
        $this->archivoService = $archivoService;
    }

   
    public function handle()
    {
        $texto = $this->argument('texto');
        $tipoValidacion = $this->option('tipo');
        $archivoPath = $this->option('archivo');

        if ($archivoPath) {
            if (!File::exists($archivoPath)) {
                $this->error("El archivo especificado en --archivo no existe: {$archivoPath}");
                return 1;
            }
            $texto = File::get($archivoPath);
            $this->info("Contenido del archivo '{$archivoPath}':");
            $this->line($texto);
        }

        if (!$texto && !$archivoPath) {
            $this->error("Por favor, proporciona un texto como argumento o especifica la ruta de un archivo con la opción --archivo.");
            return 1;
        }

        if ($tipoValidacion === 'numeros') {
            $resultadoNumeros = $this->archivoService->validarSoloNumeros($texto);
            if ($resultadoNumeros['es_solo_numeros']) {
                $this->info("El texto (o contenido del archivo) contiene solo números.");
                if (!empty($resultadoNumeros['numeros_encontrados'])) {
                    $this->line("Números encontrados: " . implode(', ', $resultadoNumeros['numeros_encontrados']));
                }
            } else {
                $this->error("El texto (o contenido del archivo) no contiene solo números.");
                if (!empty($resultadoNumeros['numeros_encontrados'])) {
                    $this->line("Números encontrados: " . implode(', ', $resultadoNumeros['numeros_encontrados']));
                }
            }
        
        } elseif ($tipoValidacion === 'gramatica') {
            $resultadoValidacion = $this->archivoService->validarGramaticaInventada($texto);
            if ($resultadoValidacion['valido']) {
                $this->info("Todas las palabras cumplen la regla.");
                if (!empty($resultadoValidacion['correctas'])) {
                    $this->line("Palabras correctas: " . implode(', ', $resultadoValidacion['correctas']));
                }
            } else {
                $this->error("Algunas palabras no cumplen la regla:");
                if (!empty($resultadoValidacion['incorrectas'])) {
                    $this->line("Palabras incorrectas: " . implode(', ', $resultadoValidacion['incorrectas']));
                }
                if (!empty($resultadoValidacion['correctas'])) {
                    $this->line("Palabras correctas: " . implode(', ', $resultadoValidacion['correctas']));
                }
            }
        } else {
            $this->error("Por favor, especifica el tipo de validación usando la opción --tipo (numeros o gramatica).");
            return 1;
        }

        return 0;
    }
    
}
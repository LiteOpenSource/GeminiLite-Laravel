<?php

namespace Liteopensource\GeminiLiteLaravel\Src\Traits;

trait GeminiModelValidations
{
    /**
     * Valida el parámetro topK para un modelo Gemini.
     *
     * @param string $model Nombre del modelo.
     * @param int|null $topK Valor de topK.
     * @throws \InvalidArgumentException Si el valor de topK no es válido.
     */
    protected function validateTopK(string $model, ?int $topK): void
    {
        // Definir los rangos válidos para cada modelo
        $modelRanges = [
            'gemini-2.0-flash-exp' => ['topK' => [1, 40]],
            'gemini-exp-1206' => ['topK' => [1, 40]],
            'gemini-2.0-flash-thinking-exp-01-21' => ['topK' => [1, 40]],
            'learnlm-1.5-pro-experimental' => ['topK' => [1, 40]],
            'gemini-1.5-flash' => ['topK' => [1, 40]],
            'gemini-1.5-flash-8b' => ['topK' => [1, 40]],
        ];

        if ($topK === null) {
            return; // Si topK es nulo, no hay validación que hacer
        }

        if ($model === 'gemini-1.5-pro') {
            throw new \InvalidArgumentException("The model {$model} does not support the topK parameter.");
        }

        if (isset($modelRanges[$model])) {
            $min = $modelRanges[$model]['topK'][0];
            $max = $modelRanges[$model]['topK'][1];
            if ($topK < $min || $topK > $max) {
                throw new \InvalidArgumentException("The topK value for the model {$model} must be between {$min} and {$max}.");
            }
        }
    }

    /**
     * Valida el parámetro topP para un modelo Gemini.
     *
     * @param string $model Nombre del modelo.
     * @param float|null $topP Valor de topP.
     * @throws \InvalidArgumentException Si el valor de topP no es válido.
     */
    protected function validateTopP(string $model, ?float $topP): void
    {
        // Definir los rangos válidos para cada modelo
         $modelRanges = [
            'gemini-2.0-flash-exp' => ['topP' => [0.0, 1.0]],
            'gemini-exp-1206' => ['topP' => [0.0, 1.0]],
            'gemini-2.0-flash-thinking-exp-01-21' => ['topP' => [0.0, 1.0]],
            'learnlm-1.5-pro-experimental' => ['topP' => [0.0, 1.0]],
            'gemini-1.5-pro' => ['topP' => [0.0, 1.0]],
            'gemini-1.5-flash' => ['topP' => [0.0, 1.0]],
            'gemini-1.5-flash-8b' => ['topP' => [0.0, 1.0]],
        ];

        if ($topP === null) {
            return; // Si topP es nulo, no hay validación que hacer
        }

        if (isset($modelRanges[$model]['topP'])) {
            $min = $modelRanges[$model]['topP'][0];
            $max = $modelRanges[$model]['topP'][1];
            if ($topP < $min || $topP > $max) {
                 throw new \InvalidArgumentException("The topP value for the model {$model} must be between {$min} and {$max}.");
            }
        }
    }

    /**
     * Valida el parámetro temperature para un modelo Gemini.
     *
     * @param string $model Nombre del modelo.
     * @param float|null $temperature Valor de temperature.
     * @throws \InvalidArgumentException Si el valor de temperature no es válido.
     */
    protected function validateTemperature(string $model, ?float $temperature): void
    {
        // Definir los rangos válidos para cada modelo
        $modelRanges = [
            'gemini-2.0-flash-exp' => ['temperature' => [0.0, 2.0]],
            'gemini-exp-1206' => ['temperature' => [0.0, 2.0]],
            'gemini-2.0-flash-thinking-exp-01-21' => ['temperature' => [0.0, 2.0]],
            'learnlm-1.5-pro-experimental' => ['temperature' => [0.0, 2.0]],
            'gemini-1.5-pro' => ['temperature' => [0.0, 2.0]],
            'gemini-1.5-flash' => ['temperature' => [0.0, 2.0]],
            'gemini-1.5-flash-8b' => ['temperature' => [0.0, 2.0]],
        ];

        if ($temperature === null) {
            return; // Si temperature es nulo, no hay validación que hacer
        }

        if (isset($modelRanges[$model]['temperature'])) {
            $min = $modelRanges[$model]['temperature'][0];
            $max = $modelRanges[$model]['temperature'][1];
            if ($temperature < $min || $temperature > $max) {
                throw new \InvalidArgumentException("The temperature value for the model {$model} must be between {$min} and {$max}.");
            }
        }
    }
}

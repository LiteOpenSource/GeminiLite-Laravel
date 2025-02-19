<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface EmbeddingServiceInterface
{
    /**
     * Genera un vector de embedding para un texto dado.
     *
     * @param string $text El texto del cual generar el embedding
     * @param array $options Opciones adicionales como taskType, title, outputDimensionality
     * @return array Vector de embedding generado
     */
    public function embedText(string $text, array $options = []): array;

    /**
     * Genera vectores de embedding para múltiples textos en una sola llamada.
     *
     * @param array $texts Array de textos para generar embeddings
     * @param array $options Opciones adicionales como taskType, title, outputDimensionality
     * @return array Array de vectores de embedding generados
     */
    public function embedBatch(array $texts, array $options = []): array;
}
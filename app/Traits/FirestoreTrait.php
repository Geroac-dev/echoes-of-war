<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait FirestoreTrait
{
    private function getBaseUrl($collection)
    {
        $projectId = env('FIREBASE_PROJECT_ID', 'echos-of-war');
        return "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/{$collection}";
    }

    // Obtener todos los documentos de una colección
    public function fbGet($collection)
    {
        $response = Http::get($this->getBaseUrl($collection));
        
        // --- AGREGA ESTO TEMPORALMENTE PARA DEPURAR ---
        if (!$response->successful()) {
            dd($response->status(), $response->json());
        }
        // ----------------------------------------------

        $documents = $response->json()['documents'] ?? [];
        $result = [];

        foreach ($documents as $doc) {
            $id = basename($doc['name']);
            $fields = $doc['fields'] ?? [];
            $item = ['id' => $id];
            
            foreach ($fields as $key => $value) {
                // Extrae el valor sin importar si es string, integer, etc.
                $item[$key] = array_values($value)[0] ?? null;
            }
            $result[] = $item;
        }

        return $result;
    }

    // Crear un documento nuevo
    public function fbCreate($collection, array $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            if (is_int($value)) {
                $fields[$key] = ['integerValue' => (string)$value];
            } else {
                $fields[$key] = ['stringValue' => (string)$value];
            }
        }

        $response = Http::post($this->getBaseUrl($collection), ['fields' => $fields]);
        return $response->successful();
    }

    // Buscar por ID
    public function fbFind($collection, $id)
    {
        $response = Http::get("{$this->getBaseUrl($collection)}/{$id}");
        if (!$response->successful()) return null;

        $doc = $response->json();
        $fields = $doc['fields'] ?? [];
        $item = ['id' => $id];

        foreach ($fields as $key => $value) {
            $item[$key] = array_values($value)[0] ?? null;
        }

        return $item;
    }

    // Eliminar por ID
    public function fbDelete($collection, $id)
    {
        $response = Http::delete("{$this->getBaseUrl($collection)}/{$id}");
        return $response->successful();
    }
    public function fbUpdate($collection, $id, array $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            if (is_int($value)) {
                $fields[$key] = ['integerValue' => (string)$value];
            } else {
                $fields[$key] = ['stringValue' => (string)($value ?? '')];
            }
        }

        $url = "{$this->getBaseUrl($collection)}/{$id}";
        $response = Http::patch($url, ['fields' => $fields]);
        return $response->successful();
    }

    // Crear un documento y regresar su ID generado por Firestore
    public function fbCreateWithId($collection, array $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            if (is_int($value)) {
                $fields[$key] = ['integerValue' => (string)$value];
            } else {
                $fields[$key] = ['stringValue' => (string)($value ?? '')];
            }
        }

        $response = Http::post($this->getBaseUrl($collection), ['fields' => $fields]);
        if ($response->successful()) {
            $name = $response->json()['name'] ?? '';
            return basename($name); // Extrae el ID único del documento creado
        }
        return null;
    }
}
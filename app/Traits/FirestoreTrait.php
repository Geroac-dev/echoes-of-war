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

    public function fbGet($collection)
    {
        $response = Http::get($this->getBaseUrl($collection));
        
        if (!$response->successful()) {
            dd($response->status(), $response->json());
        }

        $documents = $response->json()['documents'] ?? [];
        $result = [];

        foreach ($documents as $doc) {
            $id = basename($doc['name']);
            $fields = $doc['fields'] ?? [];
            $item = ['id' => $id];
            
            foreach ($fields as $key => $value) {
                $item[$key] = array_values($value)[0] ?? null;
            }
            $result[] = $item;
        }

        return $result;
    }

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

    public function fbDelete($collection, $id)
    {
        $response = Http::delete("{$this->getBaseUrl($collection)}/{$id}");
        return $response->successful();
    }
    public function fbUpdate($collection, $id, array $data)
    {
        $fields = [];
        $updateMaskQuery = "";

        foreach ($data as $key => $value) {
            if (is_int($value)) {
                $fields[$key] = ['integerValue' => (string)$value];
            } elseif (is_bool($value)) {
                $fields[$key] = ['booleanValue' => $value];
            } else {
                $fields[$key] = ['stringValue' => (string)($value ?? '')];
            }
            
            $updateMaskQuery .= "&updateMask.fieldPaths=" . $key;
        }

        $updateMaskQuery = '?' . ltrim($updateMaskQuery, '&');

        $baseUrl = "{$this->getBaseUrl($collection)}/{$id}" . $updateMaskQuery;

        $response = Http::patch($baseUrl, ['fields' => $fields]);

        return $response->successful();
    }

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
            return basename($name);
        }
        return null;
    }
}
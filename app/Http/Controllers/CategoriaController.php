<?php

namespace App\Http\Controllers;

use App\Traits\FirestoreTrait;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    use FirestoreTrait;

    public function getCategorias()
    {
        try {
            $categorias = $this->fbGet('categorias');
            $contenidos = $this->fbGet('contenidos');

            usort($categorias, fn($a, $b) => ($a['orden'] ?? 0) <=> ($b['orden'] ?? 0));

            foreach ($categorias as &$cat) {
                $cat['contenidos'] = array_values(array_filter($contenidos, fn($c) => ($c['categoria_id'] ?? null) === $cat['id']));
            }

            return response()->json(['status' => '200', 'categorias' => $categorias]);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function saveCategoria(Request $request)
    {
        try {
            $this->fbCreate('categorias', [
                "nombre" => $request->input("nombre"),
                "name" => $request->input("name"),
                "descripcion" => $request->input("descripcion"),
                "description" => $request->input("description"),
                "orden" => (int) $request->input("orden"),
            ]);
            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function editCategoria(Request $request)
    {
        try {
            $id = $request->input("id");
            $this->fbUpdate('categorias', $id, [
                "nombre" => $request->input("nombre"),
                "name" => $request->input("name"),
                "descripcion" => $request->input("descripcion"),
                "description" => $request->input("description"),
                "orden" => (int) $request->input("orden"),
            ]);
            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function deleteCategoria(Request $request)
    {
        try {
            $this->fbDelete('categorias', $request->input("id"));
            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function getContenido(Request $request)
    {
        try {
            $slug = $request->input("slug");
            $contenidos = $this->fbGet('contenidos');
            
            $contenido = null;
            foreach ($contenidos as $c) {
                if (isset($c['slug']) && $c['slug'] === $slug) {
                    $contenido = $c;
                    break;
                }
            }

            return response()->json(['status' => '200', "contenido" => $contenido]);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function saveContenido(Request $request)
    {
        try {
            $imagen = null;
            if ($request->hasFile('file')) {
                $imagen = $request->file('file')->store('contenidos', 'public');
            }

            $this->fbCreate('contenidos', [
                "categoria_id" => $request->input("categoria_id"),
                "nombre" => $request->input("nombre"),
                "name" => $request->input("name"),
                "descripcion" => $request->input("descripcion"),
                "description" => $request->input("description"),
                "orden" => (int) $request->input("orden"),
                "slug" => $request->input("slug"),
                "imagen" => $imagen
            ]);

            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function editContenido(Request $request)
    {
        try {
            $id = $request->input("id");
            $data = [
                "nombre" => $request->input("nombre"),
                "name" => $request->input("name"),
                "descripcion" => $request->input("descripcion"),
                "description" => $request->input("description"),
                "orden" => (int) $request->input("orden"),
                "slug" => $request->input("slug"),
            ];

            if ($request->hasFile('file')) {
                $data["imagen"] = $request->file('file')->store('contenidos', 'public');
            }

            $this->fbUpdate('contenidos', $id, $data);
            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function deleteContenido(Request $request)
    {
        try {
            $this->fbDelete('contenidos', $request->input("id"));
            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function getSecciones(Request $request)
    {
        try {
            $contenidoId = $request->input("contenido_id");
            $secciones = $this->fbGet('secciones');
            $imagenes = $this->fbGet('imagenes');

            $filtradas = array_values(array_filter($secciones, fn($s) => ($s['contenido_id'] ?? null) === $contenidoId));

            $izquierda = [];
            $derecha = [];
            $centro = [];

            foreach ($filtradas as $sec) {
                $sec['imagenes'] = array_values(array_filter($imagenes, fn($img) => ($img['seccion_id'] ?? null) === $sec['id']));

                if (($sec['posicion'] ?? 0) == 1) $izquierda[] = $sec;
                elseif (($sec['posicion'] ?? 0) == 2) $derecha[] = $sec;
                elseif (($sec['posicion'] ?? 0) == 3) $centro[] = $sec;
            }

            usort($izquierda, fn($a, $b) => ($a['orden'] ?? 0) <=> ($b['orden'] ?? 0));
            usort($derecha, fn($a, $b) => ($a['orden'] ?? 0) <=> ($b['orden'] ?? 0));
            usort($centro, fn($a, $b) => ($a['orden'] ?? 0) <=> ($b['orden'] ?? 0));

            return response()->json([
                'status' => '200',
                "izquierda" => $izquierda,
                "derecha" => $derecha,
                "centro" => $centro
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function saveSeccion(Request $request)
    {
        try {
            $seccionId = $this->fbCreateWithId('secciones', [
                "contenido_id" => $request->input("contenido_id"),
                "titulo" => $request->input("titulo"),
                "title" => $request->input("title"),
                "texto" => $request->input("texto"),
                "text" => $request->input("text"),
                "posicion" => (int) $request->input("posicion"),
                "orden" => (int) $request->input("orden"),
            ]);

            if ($files = $request->file('files')) {
                foreach($files as $file){
                    $imagenPath = $file->store('secciones', 'public');
                    $this->fbCreate('imagenes', [
                        'seccion_id' => $seccionId,
                        'ruta' => $imagenPath,
                    ]);
                }
            }

            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }
    public function editSeccion(Request $request)
    {
        try {
            $id = $request->input("id");
            
            $data = [
                "contenido_id" => $request->input("contenido_id"),
                "titulo" => $request->input("titulo"),
                "title" => $request->input("title"),
                "texto" => $request->input("texto"),
                "text" => $request->input("text"),
                "posicion" => (int) $request->input("posicion"),
                "orden" => (int) $request->input("orden"),
            ];

            if ($files = $request->file('files')) {
                foreach($files as $file){
                    $imagenPath = $file->store('secciones', 'public');
                    $this->fbCreate('imagenes', [
                        'seccion_id' => $id,
                        'ruta' => $imagenPath,
                    ]);
                }
            }

            $this->fbUpdate('secciones', $id, $data);
            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }
    public function deleteSeccion(Request $request)
    {
        try {
            $this->fbDelete('secciones', $request->input("id"));
            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }
}

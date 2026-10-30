<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Traits\FirestoreTrait;

class CategoriaController extends Controller
{
    use FirestoreTrait;

    public function getCategorias()
    {
        try {
            // Traemos las categorías y los contenidos para simular el ->with('contenidos')
            $categorias = $this->fbGet('categorias');
            $contenidos = $this->fbGet('contenidos');

            // Ordenar por 'orden' ASC en PHP
            usort($categorias, fn($a, $b) => ($a['orden'] ?? 0) <=> ($b['orden'] ?? 0));

            // Simulamos la relación with('contenidos') anidándolos manualmente
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
                "descripcion" => $request->input("descripcion"),
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
            // Para editar en Firestore vía API REST, hacemos un PATCH o recreamos el documento
            $this->fbUpdate('categorias', $id, [
                "nombre" => $request->input("nombre"),
                "descripcion" => $request->input("descripcion"),
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
                "descripcion" => $request->input("descripcion"),
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
                "descripcion" => $request->input("descripcion"),
                "orden" => (int) $request->input("orden"),
                "slug" => $request->input("slug"),
            ];

            if ($request->hasFile('file')) {
                // Opcional: manejar borrado de imagen anterior si gustas
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

            // Filtramos por contenido_id
            $filtradas = array_values(array_filter($secciones, fn($s) => ($s['contenido_id'] ?? null) === $contenidoId));

            $izquierda = [];
            $derecha = [];
            $centro = [];

            foreach ($filtradas as $sec) {
                // Adjuntar imágenes asociadas a esta sección
                $sec['imagenes'] = array_values(array_filter($imagenes, fn($img) => ($img['seccion_id'] ?? null) === $sec['id']));

                if (($sec['posicion'] ?? 0) == 1) $izquierda[] = $sec;
                elseif (($sec['posicion'] ?? 0) == 2) $derecha[] = $sec;
                elseif (($sec['posicion'] ?? 0) == 3) $centro[] = $sec;
            }

            // Ordenar cada posición por orden ASC
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
            // Guardamos la sección y obtenemos su ID simulado de Firestore
            // (Nota: fbCreate necesita devolver el ID generado si guardas imágenes asociadas)
            // Aquí puedes ajustar tu Trait para que fbCreate regrese el ID del documento creado.
            $seccionId = $this->fbCreateWithId('secciones', [
                "contenido_id" => $request->input("contenido_id"),
                "titulo" => $request->input("titulo"),
                "texto" => $request->input("texto"),
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
}

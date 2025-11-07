<?php

namespace App\Http\Controllers;

use App\Services\ToadFilmService;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    private ToadFilmService $filmService;

    public function __construct(ToadFilmService $filmService)
    {
        $this->middleware('auth');
        $this->filmService = $filmService;
    }

    public function index()
    {
        $films = $this->filmService->getAllFilms();

        return view('films.index', [
            'films' => $films ?? []
        ]);
    }

    public function show($id)
    {
        $film = $this->filmService->getFilmById($id);

        if (!$film) {
            abort(404, 'Film non trouvé');
        }

        return view('films.show', [
            'film' => $film
        ]);
    }
    /**
 * Affiche le formulaire pour modifier un film existant.
 */
    public function edit($id)
    {
    // Récupère le film via le service
        $film = $this->filmService->getFilmById($id);

    // Si le film n'existe pas, retourne une erreur 404
        if (!$film) {
            abort(404, 'Film non trouvé');
        }

    // Retourne la vue avec les données du film
        return view('films.edit', [
        'film' => $film
        ]);
    }

/**
 * Met à jour un film existant avec les données soumises par le formulaire.
 */
public function update(Request $request, $id)
{
    // Validation des champs du formulaire
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'language' => 'required|string|max:50',
        'release_year' => 'required|integer',
        'length' => 'nullable|integer',
        'rating' => 'nullable|string|max:10',
    ]);

    // Appelle le service pour mettre à jour le film
    $updated = $this->filmService->updateFilm($id, $validated);

    // Si la mise à jour échoue
    if (!$updated) {
        return redirect()->back()->with('error', 'Impossible de mettre à jour le film.');
    }

    // Redirection vers la liste avec message de succès
    return redirect()->route('films.index')->with('success', 'Film mis à jour avec succès !');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'language' => 'required|string|max:50',
        'release_year' => 'required|integer',
        'length' => 'nullable|integer',
        'rating' => 'nullable|string|max:10',
    ]);

    // Transformer pour l'API (camelCase)
    $data = [
        'title' => $validated['title'],
        'description' => $validated['description'],
        'releaseYear' => $validated['release_year'],
        'length' => $validated['length'],
        'rating' => $validated['rating'],
    ];

    $created = $this->filmService->createFilm($data); 

    if (!$created) {
        return redirect()->back()->with('error', 'Impossible de créer le film.');
    }

    return redirect()->route('films.index')->with('success', 'Film ajouté avec succès !');
    }
    /**
 * Affiche le formulaire pour créer un nouveau film.
 */
public function create()
    {
    return view('films.create'); 
    }
public function destroy($id)
{
    $deleted = $this->filmService->deleteFilm($id);

    if (!$deleted) {
        return redirect()->back()->with('error', 'Impossible de supprimer le film.');
    }

    return redirect()->route('films.index')->with('success', 'Film supprimé avec succès !');
    }

}
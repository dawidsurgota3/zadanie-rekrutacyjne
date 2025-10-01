<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiBadRequestException;
use App\Http\Requests\PetListRequest;
use App\Http\Requests\PetRequest;
use App\Services\PetService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PetController extends Controller
{
    /**
     * @param PetService $petService
     */
    public function __construct(
        private PetService $petService
    )
    {
    }

    /**
     * @return Response
     * @throws ApiBadRequestException
     */
    public function index(PetListRequest $request): Response
    {
        return Inertia::render('pets', [
            'pets' => $this->petService->getPets($request->status)
        ]);
    }

    /**
     * @param PetRequest $request
     * @return RedirectResponse
     * @throws ApiBadRequestException
     */
    public function store(PetRequest $request): RedirectResponse
    {
        $this->petService->createPet($request->validated());

        return back()->with(['message' => 'Pet created!']);
    }

    /**
     * @param PetRequest $request
     * @param int $id
     * @return RedirectResponse
     * @throws ApiBadRequestException
     */
    public function update(PetRequest $request, int $id): RedirectResponse
    {
        $this->petService->updatePetById($id, $request->validated());

        return back()->with(['message' => 'Pet updated!']);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     * @throws ApiBadRequestException
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->petService->deletePetById($id);

        return back()->with(['message' => 'Pet deleted!']);
    }
}

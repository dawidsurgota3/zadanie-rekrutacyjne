<?php

namespace App\Services;

use App\Dtos\PetDto;
use App\Exceptions\ApiBadRequestException;
use App\Mappers\PetMapper;
use App\PetStatus;
use App\RequestMethod;
use App\Services\Api\ApiService;
use App\Services\Api\ApiServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class PetService
{
    /**
     * @param ApiServiceInterface $apiService
     */
    private ApiServiceInterface $apiService;

    public function __construct()
    {
        $this->apiService = new ApiService(
            Config::get("externalservice.pet_service.url")
        );
    }

    /**
     * @throws ApiBadRequestException
     */
    public function getPets(string $status): Collection
    {
        return PetMapper::mapList(
            $this->apiService->request('/pet/findByStatus', RequestMethod::GET, [
                'status' => $status
            ])
        );
    }

    /**
     * @param int $id
     * @return PetDto
     * @throws ApiBadRequestException
     */
    public function getPetById(int $id): PetDto
    {
        return PetMapper::map(
            $this->apiService->request("/pet/$id", RequestMethod::GET)
        );
    }

    /**
     * @param int $id
     * @return void
     * @throws ApiBadRequestException
     */
    public function deletePetById(int $id): void
    {
        $this->apiService->request("/pet/$id", RequestMethod::DELETE);
    }

    /**
     * @param array $data
     * @return PetDto
     * @throws ApiBadRequestException
     */
    public function createPet(array $data): PetDto
    {
        return PetMapper::map(
            $this->apiService->request('/pet', RequestMethod::POST, body: $data)
        );
    }

    /**
     * @param int $id
     * @param array $data
     * @return PetDto
     * @throws ApiBadRequestException
     */
    public function updatePetById(int $id, array $data): PetDto
    {
        return PetMapper::map(
            $this->apiService->request('/pet', RequestMethod::PUT, body: $data + ['id' => $id])
        );
    }
}

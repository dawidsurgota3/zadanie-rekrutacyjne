import { PetCategory } from '@/types/pets/pet-category';
import { PetTag } from '@/types/pets/pet-tag';
import { PetStatus } from '@/types/pets/pet-status';

export type PetResponse = {
    id: number;
    category: PetCategory | null;
    name: string;
    photoUrls: string[];
    tags: PetTag[];
    status: PetStatus;
};

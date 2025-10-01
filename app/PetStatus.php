<?php

namespace App;

enum PetStatus: string
{
    case AVAILABLE = 'available';
    case SOLD = 'sold';
    case PENDING = 'pending';
}

<?php

namespace App\Interfaces;

use App\Entity\Client;

interface OrderManagerInterface
{
    public function processingOrder(Client $client, ?StaffInterface $staff = null);
}
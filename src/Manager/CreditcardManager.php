<?php

namespace App\Manager;


use App\Entity\Creditcard;
use App\Repository\CreditcardRepository;

class CreditcardManager
{
    private $creditcardRepository;

    public function __construct(CreditcardRepository $creditcardRepository)
    {
        $this->creditcardRepository = $creditcardRepository;
    }

    /**
     * @return CreditcardRepository
     */
    public function getCreditcardRepository()
    {
        return $this->creditcardRepository->findAll();
    }

    public function getCountCreditcards(){
        $creditcards = $this->getCreditcardRepository();
        return sizeof($creditcards);
    }
}
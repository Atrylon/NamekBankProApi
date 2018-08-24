<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 24/08/2018
 * Time: 10:59
 */

namespace App\Controller;

use App\Entity\Creditcard;
use App\Repository\CreditcardRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class CreditcardsController extends FOSRestController
{
    private $creditcardRepository;
    private $em;

    public function __construct(CreditcardRepository $creditcardRepository, EntityManagerInterface $em)
    {
        $this->creditcardRepository = $creditcardRepository;
        $this->em = $em;
    }

    /**
     * @Rest\View(serializerGroups={"creditcard"})
     */
    public function getCreditcardsAction(){
        if($this->getUser()){
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $creditcards = $this->creditcardRepository->findAll();
                return $this->view($creditcards);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non logué', 401);
    }

    /**
     * @Rest\View(serializerGroups={"creditcard"})
     */
    public function getCreditcardAction($id){
        $creditcard = $this->creditcardRepository->find($id);
        return $this->view($creditcard);
    }

    /**
     * @Rest\Post("/creditcards")
     * @ParamConverter("creditcard", converter="fos_rest.request_body")
     * @Rest\View(serializerGroups={"credicard"})
     */
    public function postCreditcardsAction(Creditcard $creditcard){
        if($this->getUser()){
            $master = $this->getUser();
            $creditcard->setCompany($master->getCompany());
            $this->em->persist($creditcard);
            $this->em->flush();
            return $this->view($creditcard);
        }
        return $this->view('Non logué', 401);
    }

    /**
     * @param $id
     * @Rest\View(serializerGroups={"creditcard"})
     */
    public function putCreditcardAction(Request $request, $id){
        if($this->getUser()){
            $creditcard = $this->creditcardRepository->find($id);
            $name = $request->get('name');
            $creditcardNumber = $request->get('creditcardNumber');
            $creditcardType = $request->get('creditcardType');

            if ($this->getUser() === $creditcard->getCompany()->getMaster() or in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                if (isset($name)) {
                    $creditcard->setName($name);
                }
                if (isset($creditcardNumber)) {
                    $creditcard->setCreditCardNumber($creditcardNumber);
                }
                if (isset($creditcardType)) {
                    $creditcard->setCreditCardType($creditcardType);
                }
                $this->em->persist($creditcard);
                $this->em->flush();
                return $this->view($creditcard);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 403);
    }

    /**
     * @param $id
     * @Rest\View(serializerGroups={"creditcard"})
     */
    public function deleteCreditcardAction($id){
        if($this->getUser()){
            $creditcard = $this->creditcardRepository->find($id);

            if ($this->getUser() === $creditcard->getCompany()->getMaster() or in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {

                $this->em->remove($creditcard);
                $this->em->flush();
                return $this->view('Deleted!', 204);

            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 403);
    }
}
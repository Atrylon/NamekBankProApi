<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 24/08/2018
 * Time: 10:59
 */

namespace App\Controller;

use App\Entity\Creditcard;
use App\Repository\CompanyRepository;
use App\Repository\CreditcardRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CreditcardsController extends FOSRestController
{
    private $creditcardRepository;
    private $companyRepository;
    private $em;
    private $validationErrors;

    public function __construct(CreditcardRepository $creditcardRepository, CompanyRepository $companyRepository,
                                EntityManagerInterface $em)
    {
        $this->companyRepository = $companyRepository;
        $this->creditcardRepository = $creditcardRepository;
        $this->em = $em;
    }

    //List all Creditcards
    /**
     * @Rest\View(serializerGroups={"creditcard"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the creditcards list"
     * )
     * @SWG\Tag(name="creditcard")
     */
    public function getCreditcardsAction(){
        if($this->getUser()){
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $creditcards = $this->creditcardRepository->findAll();
                return $this->view($creditcards, 200);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non logué', 401);
    }

    //List one Creditcard based on Id
    /**
     * @Rest\View(serializerGroups={"creditcard"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the creditcard based on it Id"
     * )
     * @SWG\Tag(name="creditcard")
     */
    public function getCreditcardAction($id){
        $creditcard = $this->creditcardRepository->find($id);
        return $this->view($creditcard, 200);
    }

    /**
     * @Rest\View(serializerGroups={"creditcard"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the creditcard list of a company based on it Id"
     * )
     * @SWG\Tag(name="creditcard")
     */
    public function getCompanyCreditcardsAction(int $id){
        if($this->getUser()) {
            $company = $this->companyRepository->find($id);

            if ($this->getUser() === $company->getMaster() or in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $creditcard = $company->getCreditcards();
                return $this->view($creditcard, 200);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non logué', 401);
    }


    //Create one Creditcard from json file
    /**
     * @Rest\Post("/creditcards")
     * @ParamConverter("creditcard", converter="fos_rest.request_body")
     * @Rest\View(serializerGroups={"credicard"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a creditcard from a json file"
     * )
     * @SWG\Tag(name="creditcard")
     */
    public function postCreditcardsAction(Creditcard $creditcard, ConstraintViolationListInterface $validationErrors){
        if($this->getUser()){
            $master = $this->getUser();
            $creditcard->setCompany($master->getCompany());

            if ($validationErrors->count() > 0) {
                $error = [];
                /** @var  ConstraintViolation $constraintViolation */
                foreach ($validationErrors as $constraintViolation) {
                    $message = $constraintViolation->getMessage();
                    $propertyPath = $constraintViolation->getPropertyPath();
                    array_push($error, $message, $propertyPath);
                }
                return json_encode($error);
            } else {
                $this->em->persist($creditcard);
                $this->em->flush();
                return $this->view($creditcard, 201);
            }
        }
        return $this->view('Non logué', 401);
    }

    //Modify one CreditCard from json file based on Id
    /**
     * @param $id
     * @Rest\View(serializerGroups={"creditcard"})
     * @SWG\Response(
     *     response=200,
     *     description="Modify the creditcard data based on it Id"
     * )
     * @SWG\Tag(name="creditcard")
     */
    public function putCreditcardAction(Request $request, $id, ValidatorInterface $validator){
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

                $validationErrors = $validator->validate($creditcard);
                $this->em->persist($creditcard);
                $error = [];
                foreach ($validationErrors as $constraintViolation) {
                    $message = $constraintViolation->getMessage();
                    $propertyPath = $constraintViolation->getPropertyPath();
                    array_push($error, $message, $propertyPath);
                }
                if (sizeof($error) > 0) {
                    return json_encode($error);
                }
                $this->em->flush();
                return $this->view($creditcard, 200);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 401);
    }

    //Deleted one Creditcard based on Id
    /**
     * @param $id
     * @Rest\View(serializerGroups={"creditcard"})
     * @SWG\Response(
     *     response=200,
     *     description="Delete the creditcard based on it Id"
     * )
     * @SWG\Tag(name="creditcard")
     */
    public function deleteCreditcardAction($id){
        if($this->getUser()){
            $creditcard = $this->creditcardRepository->find($id);

            if ($this->getUser() === $creditcard->getCompany()->getMaster() or in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {

                $this->em->remove($creditcard);
                $this->em->flush();
                //Bug des tests lors du renvoi de code 204 => envoi 204
                return $this->view('Deleted!', 200);

            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 401);
    }
}
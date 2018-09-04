<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 24/08/2018
 * Time: 10:52
 */
namespace App\Controller;

use App\Entity\Master;
use App\Repository\MasterRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MastersController extends FOSRestController
{
    private $masterRepository;
    private $em;
    private $validationErrors;

    public function __construct(MasterRepository $masterRepository, EntityManagerInterface $em)
    {
        $this->masterRepository = $masterRepository;
        $this->em = $em;
    }

    // List all Masters
    /**
     * @Rest\View(serializerGroups={"master"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the masters list"
     * )
     * @SWG\Tag(name="master")
     */
    function getMastersAction(){
        if($this->getUser()) {
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $masters = $this->masterRepository->findAll();
                return $this->view($masters, 200);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non logué', 401);
    }

    //List One master based on Id
    /**
     * @Rest\View(serializerGroups={"master"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the master based on his Id"
     * )
     * @SWG\Tag(name="master")
     */
    public function getMasterAction ($id){
        $master = $this->masterRepository->find($id);
        return $this->view($master, 200);
    }

    //Create One master from json file
    /**
     * @Rest\Post("/masters")
     * @ParamConverter("master", converter="fos_rest.request_body")
     * @Rest\View(serializerGroups={"master"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a master from a json file"
     * )
     * @SWG\Tag(name="master")
     */
    public function postMastersAction(Master $master, ConstraintViolationListInterface $validationErrors){
        if ($validationErrors->count() > 0 ){
            $error = [];
            /** @var  ConstraintViolation $constraintViolation */
            foreach ($validationErrors as $constraintViolation) {
                $message = $constraintViolation->getMessage();
                $propertyPath = $constraintViolation->getPropertyPath();
                array_push($error, $message, $propertyPath);

            }
            return json_encode($error);

        }
        else{
            $this->em->persist($master);
            $this->em->flush();
            return $this->view($master, 201);
        }
    }

    //Modify One master from json file based on Id
    /**
     * @param $id
     * @Rest\View(serializerGroups={"master"})
     * @SWG\Response(
     *     response=200,
     *     description="Modify the master data based on his Id"
     * )
     * @SWG\Tag(name="master")
     */
    public function putMasterAction(Request $request, int $id, ValidatorInterface $validator){
        if($this->getUser()){
            $master = $this->masterRepository->find($id);
            $firstname = $request->get('firstname');
            $lastname = $request->get('lastname');
            $email = $request->get('email');

            if ($this->getUser() === $master or in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                if (isset($firstname)) {
                    $master->setFirstname($firstname);
                }
                if (isset($lastname)) {
                    $master->setLastname($lastname);
                }
                if (isset($email)) {
                    $master->setEmail($email);
                }
                $validationErrors = $validator->validate($master);
                $this->em->persist($master);
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
                return $this->view($master, 200);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 401);
    }

    //Delete one master based on Id
    /**
     * @param $id
     * @Rest\View(serializerGroups={"master"})
     * @SWG\Response(
     *     response=200,
     *     description="Delete the master based on his Id"
     * )
     * @SWG\Tag(name="master")
     */
    public function deleteMasterAction($id)
    {
        if($this->getUser()){

            $master = $this->masterRepository->find($id);

            if ($this->getUser() === $master or in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {

                $company = $master->getCompany();
                if ($company) {
                    $company->setMaster(null);
                }
                $this->em->remove($master);
                $this->em->flush();
                return $this->view('Deleted!', 200);

            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 401);
    }

}
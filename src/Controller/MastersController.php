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
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

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

    /**
     * @Rest\View(serializerGroups={"master"})
     */
    function getMastersAction(){
        if($this->getUser()) {
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $masters = $this->masterRepository->findAll();
                return $this->view($masters);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non logué', 401);
    }

    /**
     * @Rest\View(serializerGroups={"master"})
     */
    public function getMasterAction ($id){
        $master = $this->masterRepository->find($id);
        return $this->view($master);
    }

    /**
     * @Rest\Post("/masters")
     * @ParamConverter("master", converter="fos_rest.request_body")
     * @Rest\View(serializerGroups={"master"})
     */
    public function postMastersAction(Master $master){
        $this->em->persist($master);
        $this->em->flush();
        return $this->view($master);
    }

    /**
     * @param $id
     * @Rest\View(serializerGroups={"master"})
     */
    public function putMasterAction(Request $request, int $id){
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
                $this->em->persist($master);
                $this->em->flush();
                return $this->view($master);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 403);
    }

    /**
     * @param $id
     * @Rest\View(serializerGroups={"master"})
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
                return $this->view('Deleted!', 204);

            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 403);
    }

}
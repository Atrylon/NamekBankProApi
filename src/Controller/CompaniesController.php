<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 24/08/2018
 * Time: 10:57
 */

namespace App\Controller;


use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class CompaniesController extends FOSRestController
{
    private $companyRepository;
    private $em;

    public function __construct(CompanyRepository $companyRepository, EntityManagerInterface $em)
    {
        $this->companyRepository = $companyRepository;
        $this->em = $em;
    }

    /**
     * @Rest\View(serializerGroups={"company"})
     */
    public function getCompaniesAction(){
        if($this->getUser()){
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $companies = $this->companyRepository->findAll();
                return $this->view($companies);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non logué', 401);
    }

    /**
     * @Rest\View(serializerGroups={"company"})
     */
    public function getCompanyAction($id){
        $company = $this->companyRepository->find($id);
        return $this->view($company);
    }

    /**
     * @Rest\Post("/companies")
     * @ParamConverter("company", converter="fos_rest.request_body")
     * @Rest\View(serializerGroups={"company"})
     */
    public function postCompaniesAction(Company $company){
        if($this->getUser()){
            $company->setMaster($this->getUser());
        }
        $this->em->persist($company);
        $this->em->flush();
        return $this->view($company);
    }

    /**
     * @Rest\View(serializerGroups={"company"})
     */
    public function putCompanyAction(Request $request, $id){
        if($this->getUser()){

            $company = $this->companyRepository->find($id);

            $name = $request->get('name');
            $slogan = $request->get('slogan');
            $phoneNumber = $request->get('phoneNumber');
            $adress = $request->get('adress');
            $websiteUrl = $request->get('webSiteUrl');
            $pictureUrl = $request->get('pictureUrl');

            if ($this->getUser() === $company->getMaster() or in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                if (isset($name)) {
                    $company->setName($name);
                }
                if (isset($slogan)) {
                    $company->setSlogan($slogan);
                }
                if (isset($phoneNumber)) {
                    $company->setPhoneNumber($phoneNumber);
                }
                if (isset($adress)) {
                    $company->setAddress($adress);
                }
                if (isset($websiteUrl)) {
                    $company->setWebSiteUrl($websiteUrl);
                }
                if (isset($pictureUrl)) {
                    $company->setPictureUrl($pictureUrl);
                }
                $this->em->persist($company);
                $this->em->flush();
                return $this->view($company);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 401);

    }

    /**
     * @Rest\View(serializerGroups={"company"})
     */
    public function deleteCompanyAction($id){
        if($this->getUser()){

            $company = $this->companyRepository->find($id);

            if ($this->getUser() === $this->getUser() or in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {

                $this->em->remove($company);
                $this->em->flush();
                return $this->view('Deleted!', 204);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 403);
    }
}
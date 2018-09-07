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
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CompaniesController extends FOSRestController
{
    private $companyRepository;
    private $em;
    private $validationErrors;

    public function __construct(CompanyRepository $companyRepository, EntityManagerInterface $em)
    {
        $this->companyRepository = $companyRepository;
        $this->em = $em;
    }

    //List all companies
    /**
     * @Rest\View(serializerGroups={"company"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the companies list"
     * )
     * @SWG\Tag(name="company")
     */
    public function getCompaniesAction(){
        if($this->getUser()){
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $companies = $this->companyRepository->findAll();
                return $this->view($companies, 200);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non logué', 401);
    }

    //List one company based on Id
    /**
     * @Rest\View(serializerGroups={"company"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the company based on it Id"
     * )
     * @SWG\Tag(name="company")
     */
    public function getCompanyAction($id){
        $company = $this->companyRepository->find($id);
        return $this->view($company, 200);
    }

    //Create a company form a json file
    /**
     * @Rest\Post("/companies")
     * @ParamConverter("company", converter="fos_rest.request_body")
     * @Rest\View(serializerGroups={"company"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a company from a json file"
     * )
     * @SWG\Tag(name="company")
     */
    public function postCompaniesAction(Company $company, ConstraintViolationListInterface $validationErrors){
        if($this->getUser()){
            $company->setMaster($this->getUser());
        }
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
            $this->em->persist($company);
            $this->em->flush();
            return $this->view($company, 201);
        }

    }

    //Modify a company from json file based on id
    /**
     * @Rest\View(serializerGroups={"company"})
     * @SWG\Response(
     *     response=200,
     *     description="Modify the company data based on it Id"
     * )
     * @SWG\Tag(name="company")
     */
    public function putCompanyAction(Request $request, $id, ValidatorInterface $validator){
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

                $validationErrors = $validator->validate($company);
                $this->em->persist($company);
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
                return $this->view($company, 200);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 401);

    }

    //Delete one company based on Id
    /**
     * @Rest\View(serializerGroups={"company"})
     * @SWG\Response(
     *     response=200,
     *     description="Delete the company based on it Id"
     * )
     * @SWG\Tag(name="company")
     */
    public function deleteCompanyAction($id){
        if($this->getUser()){

            $company = $this->companyRepository->find($id);

            if ($this->getUser() === $company->getMaster() or in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {

                $this->em->remove($company);
                $this->em->flush();
                return $this->view('Deleted!', 200);
            }
            return $this->view('Vous n\'avez pas les droits', 403);
        }
        return $this->view('Non loggué', 401);
    }
}
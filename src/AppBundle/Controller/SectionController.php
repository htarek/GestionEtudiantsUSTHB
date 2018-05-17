<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Section;
use AppBundle\Entity\Groupe;
use AppBundle\Entity\Specialite;
use AppBundle\Entity\Module;
use AppBundle\Entity\Examen;
use AppBundle\Entity\Message_section;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use \Datetime;


/**
 * Section controller.
 *
 * @Route("Section")
 */
class SectionController extends Controller
{
    /**
     * Lists all section entities.
     *
     * @Route("/", name="section_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
        $em = $this->getDoctrine()->getManager();

        $sections = $em->getRepository('AppBundle:Section')->findAll();
        $specialites = $em->getRepository('AppBundle:Specialite')->findAll();
        $modules = $em->getRepository('AppBundle:Module')->findAll();

        return $this->render('@App/section/sections_index.html.twig', array(
            'sections' => $sections,
            'specialites' => $specialites,
            'modules' => $modules,
        ));
    }

    

    /**
     * Finds and displays a section entity.
     *
     * @Route("/{id}", name="section_show")
     * @Method("GET")
     */
    public function showAction(Section $section)
    {

        if($this->getUser()){
        $em = $this->getDoctrine()->getManager();

        $prof = $em->getRepository('AppBundle:User')->findOneBy(array('id' => 1));
        $eleves = $em->getRepository('AppBundle:Etudiant')->findAll();
        $specialites = $em->getRepository('AppBundle:Specialite')->findAll();
        $modules = $em->getRepository('AppBundle:Module')->findAll();

        return $this->render('@App/section/section_show.html.twig', array(
            'section' => $section,
            'prof' => $prof,
            'eleves' => $eleves,
            'specialites' => $specialites,
            'modules' => $modules,
        ));
    }

   return $this->redirectToRoute('login');
}

/**
     * 
     *
     * @Route("/add", name="section_add")
     * @Method("POST")
     */
    public function addAction(Request $request)
    {
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
    $em = $this->getDoctrine()->getManager();
    $section = new Section;
    $specialite = $em->getRepository("AppBundle:Specialite")->findOneBy(array('id' => $request->request->get('specialite')));
    $section->setSpecialite($specialite);
    $section->setAnnee($request->request->get('annee'));
    $section->setName($request->request->get('name'));

    $section->setEmail($request->request->get('email'));
    $module = $em->getRepository("AppBundle:Module")->findOneBy(array('id' => $request->request->get('module')));
    $section->setModule($module);
    $datetest=$request->request->get('end_date');
    if(strcmp($datetest, "")!=0){
	    $date = DateTime::createFromFormat('Y-m-d', $request->request->get('end_date'));
	    $section->setDateFin($date);
	}

    $em->persist($section);

    if ( $request->request->get('groupes') > 1){
         for($i = 1; $i<= $request->request->get('groupes'); $i++){
            $groupe = new Groupe;
            $groupe->setNumero($i);
            $groupe->setSection($section);
            $em->persist($groupe);
        }
    }else{
        $groupe = new Groupe;
        $groupe->setNumero(1);
        $groupe->setSection($section);
        $em->persist($groupe);
    }



    $em->flush();

        $this->addFlash("Succès", "La section a bien été ajoutée");
        
        return $this->redirectToRoute('section_index');
   }

    /**
     *
     * @Route("/editSection/{id}", name="edit_section")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request,$id){
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }

        if ($request->isMethod('POST')){

     $url = $this->generateUrl(
    'section_show',
    array('id' => $id));


    $em = $this->getDoctrine()->getManager();
     $section=$em->getRepository("AppBundle:Section")->findOneBy(array('id' => $id));
     $specialite = $em->getRepository("AppBundle:Specialite")->findOneBy(array('id' => $request->request->get('specialite')));
    $section->setSpecialite($specialite);
    $section->setAnnee($request->request->get('annee'));
    $section->setName($request->request->get('name'));

    $section->setEmail($request->request->get('email'));
    $module = $em->getRepository("AppBundle:Module")->findOneBy(array('id' => $request->request->get('module')));
    $section->setModule($module);
    $datetest=$request->request->get('end_date');
    if(strcmp($datetest, "")!=0){
    $date = DateTime::createFromFormat('Y-m-d', $request->request->get('end_date'));
    $section->setDateFin($date);}else{
    $section->setDateFin(null);    
    }
    $em->flush();
     $this->addFlash("Succès", "La section a bien été modifiée");
     return $this->redirect($url);

}





   
}
/**
     *
     * @Route("/deleteSection/{id}", name="supp_section")
     * @Method({"GET", "POST"})
     */
    public function deleteAction($id){
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
        $em = $this->getDoctrine()->getManager();
        $section=$em->getRepository("AppBundle:Section")->findOneBy(array('id' => $id));
        $em->remove($section);
        $em->flush();
        $this->addFlash("Succès", "La section a bien été supprimée");
        return $this->redirectToRoute('section_index');
        }

    /**
     * 
     *
     * @Route("/addExamen/{id}", name="examen_add")
     * @Method({"POST", "GET"})
     */
    public function addExamenAction(Request $request,$id)
    {
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
        $em = $this->getDoctrine()->getManager();
        $section = $em->getRepository('AppBundle:Section')->findOneBy(array('id' => $id));
        $exam = new Examen;
        $exam->setName($request->request->get('name'));
        $exam->setDescription($request->request->get('description'));
        $exam->setSection($section);
        $exam->setNote($request->request->get('note'));
        $date = DateTime::createFromFormat('Y-m-d', $request->request->get('date'));
        $exam->setDateExamen($date);
        $exam->setEtat($request->request->get('etat'));

        $em->persist($exam);
        $em->flush();

        $url = $this->generateUrl(
        'section_show',
        array('id' => $id));

        $this->addFlash("Succès", "L'examen a bien été ajouté");
        return $this->redirect($url);
      }

    /**
     * 
     *
     * @Route("/addEtudiant/{id}", name="section_add_etudiant")
     * @Method({"POST", "GET"})
     */
    public function addEtudiantAction(Request $request,$id)
    {
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
    $em = $this->getDoctrine()->getManager();
    $groupe = $em->getRepository('AppBundle:Groupe')->findOneBy(array('id' => $request->request->get('groupe')));
    $etudiant = $em->getRepository('AppBundle:Etudiant')->findOneBy(array('id' => $request->request->get('eleve')));
    $etudiant->setGroupe($groupe);

     $url = $this->generateUrl(
        'section_show',
        array('id' => $id));
     $em->flush();

        $this->addFlash("Succès", "L'étudiant a bien été ajouté à la section");
        return $this->redirect($url);


    }

    /**
     * 
     *
     * @Route("/addMessageSection/{id}", name="section_add_message")
     * @Method({"POST", "GET"})
     */
    public function addMessageAction(Request $request,$id)
    {
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
    $em = $this->getDoctrine()->getManager();
    $section = $em->getRepository('AppBundle:Section')->findOneBy(array('id' => $id));
    $msg = new Message_section;
    $msg->setContent($request->request->get('content'));
    $msg->setSection($section);
    if($this->getUser()->getId()!=1){
    $etudiant = $em->getRepository('AppBundle:Etudiant')->findOneBy(array('user' => $this->getUser()->getId()));
    $msg->setEtudiant($etudiant);
    }
    $em->persist($msg);
    $em->flush();
    $url = $this->generateUrl(
    'section_show',
    array('id' => $id));
    $this->addFlash("Succès", "Message envoyé");
        if($this->getUser()->getId()==1){
       return $this->redirect($url);}
      return $this->redirectToRoute('homepage');


    }

     /**
       *
       * @Route("/deleteExamSection/{id}", name="supp_exam")
       * @Method({"GET", "POST"})
       */
      public function deleteExamAction($id){
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
          $em = $this->getDoctrine()->getManager();

          $exam=$em->getRepository("AppBundle:Examen")->findOneBy(array('id' => $id));
          $em->remove($exam);
          $em->flush();
          $this->addFlash("Succès", "L'examen a bien été supprimé");
          $url = $this->generateUrl(
         'section_show',
          array('id' => $exam->getSection()->getId()));
            return $this->redirect($url);
          }


        /**
       *
       * @Route("/changeExamState/{id}", name="exam_state")
       * @Method({"GET", "POST"})
       */
      public function ExamStateAction($id){
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
          $em = $this->getDoctrine()->getManager();
          $exam=$em->getRepository("AppBundle:Examen")->findOneBy(array('id' => $id));
          $exam->setEtat(!$exam->getEtat());
          $em->flush();
          $this->addFlash("Succès", "L'état de l'examen a bien été modifié");
          $url = $this->generateUrl(
         'section_show',
          array('id' => $exam->getSection()->getId()));
          
            return $this->redirect($url);
          
          }
        

        /**
       *
       * @Route("/EditExams/{id}", name="exams_edit")
       * @Method({"GET", "POST"})
       */
      public function examsEditAction($id, Request $request){
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
        $em = $this->getDoctrine()->getManager();
        $section=$em->getRepository("AppBundle:Section")->findOneBy(array('id' => $id));
        $exams=$section->getExamens();
        $section->setSommeCoeffs($request->request->get('sommeCoeffs'));

        foreach($exams as $exam){
            $exam->setCoefficient($request->request->get('coeff-'.$exam->getId()));
            $exam->setNote($request->request->get('note-'.$exam->getId()));
            $exam->setName($request->request->get('name-'.$exam->getId()));
            $em->persist($exam);
        }

        $em->flush();
          $this->addFlash("Succès", "les examens ont bien été modifiés");
          $url = $this->generateUrl(
         'section_show',
          array('id' => $exam->getSection()->getId()));
          
            return $this->redirect($url);
          



      }




}
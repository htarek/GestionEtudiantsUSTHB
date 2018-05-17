<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Etudiant;
use AppBundle\Entity\Section;
use AppBundle\Entity\Groupe;
use AppBundle\Entity\Module;
use AppBundle\Entity\Specialite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ( $this->getUser() ){

             $em = $this->getDoctrine()->getManager();

            if( $this->getUser()->hasRole('ROLE_ETUDIANT')){
              $sections = $em->getRepository("AppBundle:Section")->findAll();
              $etudiants = $em->getRepository("AppBundle:Etudiant")->findAll();
              $prof = $em->getRepository('AppBundle:User')->findOneBy(array('id' => 1));
              $eleve = $em->getRepository("AppBundle:Etudiant")->findOneBy(array('user' => $this->getUser()->getId()));

              return $this->render('@App/user/homepage.html.twig', array(
                  'sections' => $sections,
                  'etudiants' => $etudiants,
                  'prof' => $prof,
                  'eleve' => $eleve,
              ));
            }
            else{

              $date = new \DateTime();
              $date->format('Y-m-d');
              $date = new \DateTime($date->format('Y-m-d'));

              $todelete = $em->getRepository("AppBundle:Section")->findBy(array('dateFin' =>$date));
              if(!empty($todelete)){
                foreach ($todelete as $sec) {
                  $em->remove($sec);
                }
                        $this->addFlash("Notice", "Une ou plusieurs sections ont été automatiquement supprimées");
                        $em->flush();


              }


              $sections = $em->getRepository("AppBundle:Section")->findAll();
              $etudiants = $em->getRepository("AppBundle:Etudiant")->findAll();

              return $this->render('@App/user/homepage.html.twig', array(
                  'sections' => $sections,
                  'etudiants' => $etudiants
              ));
            }
        }
        return $this->redirectToRoute('login');
    }


    

    /**
    * 
    * @Route("/login", name="login")
    * 
    */
    public function loginAction(Request $request)
    {   
       if ( $this->getUser() ){
           return $this->redirectToRoute('homepage');
       }

        $em = $this->getDoctrine()->getManager();

        $sections = $em->getRepository("AppBundle:Section")->findAll();

       if ( $request->isMethod('POST') ) {



        if (isset($_POST['_usernamebis'])){


            $user = new User();
            $user->setUsername( $request->request->get('_usernamebis')) ;
            $user->setEmail( $request->request->get('_usernamebis')) ;
            $user->setNom( $request->request->get('nom')) ;
            $user->setPrenom( $request->request->get('prenom')) ;
            $user->setPassword(password_hash($request->request->get('_passwordbis'), PASSWORD_DEFAULT));
            $user->setRoles(array("ROLE_ETUDIANT"));
            $user->setSalt("");
            $user->setEnabled(false);

            $etudiant = new Etudiant();
            $etudiant->setMatricule($request->request->get('matricule'));

            $groupe=$em->getRepository("AppBundle:Groupe")->findOneBy(array('id' => $request->request->get('groupe')));
            $etudiant->setGroupe($groupe);

            


            $fieldsvalidation=true;

            if(strcmp($request->request->get('_usernamebis'), "")==0) {$fieldsvalidation=false;}
            if(strcmp($request->request->get('_passwordbis'), "")==0) {$fieldsvalidation=false;}
            if(strcmp($request->request->get('_passwordvalidate'), "")==0) {$fieldsvalidation=false;}

            if($fieldsvalidation==false){$error_new="Tous les champs doivent être remplis.";
            return $this->render('@App/user/login.html.twig', array(
                'sections' => $sections,
                'error_new'  => $error_new  , 'last_username' =>"", ));}


            if (filter_var($request->request->get('_usernamebis'), FILTER_VALIDATE_EMAIL)==false) {$error_new="Format d'email invalide.";
            return $this->render('@App/user/login.html.twig', array(
                'sections' => $sections,
                'error_new'  => $error_new  , 'last_username' =>"", ));
        }


        $passwordvalidation = strcmp($request->request->get('_passwordbis'), $request->request->get('_passwordvalidate'));
        $fieldsvalidation=true;

        if($passwordvalidation != 0 ) {

           $error_new="Les mots de passes entrés ne sont pas identiques.";
           return $this->render('@App/user/login.html.twig', array(
                'sections' => $sections,
               'error_new'  => $error_new  , 'last_username' =>"", ));}
           

           



           $em->persist($user);

            $etudiant->setUser($user);

           $em->persist($etudiant);
           $em->flush();

           return $this->render('@App/user/login.html.twig', array(
                'sections' => $sections,
               'succes'  => "Votre compte a été crée avec succes ! vous pouvez desormais vous connecter."  ,  ));    
       }

       else{




        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("AppBundle:User")->findOneBy(array('username' => $_POST['_username']));

        $sections = $em->getRepository("AppBundle:Section")->findAll();

        if ( $user ) { 
            if  (password_verify($_POST['_password'], $user->getPassword())){
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);

                $this->get('session')->set('_security_main', serialize($token));

                $event = new InteractiveLoginEvent($request, $token);
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
                return $this->redirectToRoute('homepage', array());
            } else {
               return $this->render('@App/user/login.html.twig', array(
                'sections' => $sections,
                  'error' => "Informations incorrectes." , 'last_username' =>"", )); 
           }
       } else {
           return $this->render('@App/user/login.html.twig', array(
            'sections' => $sections,
             'error' =>"Informations incorrectes." ,  )); 
       }



   }
}

return $this->render('@App/user/login.html.twig', array(
            'sections' => $sections
        ));
}

    /**
    * 
    *
    * @Route("/inscription", name="inscription")
    */
    public function inscriptionAction(Request $request){

      if ($request->isMethod('POST')){
        $user = new User();
        $user->setUsername( $request->request->get('_usernamebis')) ;
        $user->setEmail( $request->request->get('_usernamebis')) ;
        $user->setPassword( $request->request->get('_passwordbis')) ;
        $user->setRoles(array("ROLE_ETUDIANT"));
        $user->setSalt("");
        $user->setEnabled(true);

        $passwordvalidation = strcmp($request->request->get('_passwordbis'), $request->request->get('_passwordvalidate'));
        $fieldsvalidation=true;

        if(strcmp($request->request->get('_usernamebis'), "")==0) {$fieldsvalidation=false;}
        if(strcmp($request->request->get('_passwordbis'), "")==0) {$fieldsvalidation=false;}
        if(strcmp($request->request->get('_passwordvalidate'), "")==0) {$fieldsvalidation=false;}



        if($passwordvalidation != 0 ) {

           $error_new="Les mots de passe entrés ne sont pas identiques.";}

           if($fieldsvalidation==false){$error_new="Tous les champs doivent être remplis.";}

           if($passwordvalidation != 0 || $fieldsvalidation==false){ 
             return $this->render('@App/user/login.html.twig', array(
               'error_new'  => $error_new  , 'last_username' =>"", ));  }

             else{
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->render('@App/user/login.html.twig', array(
                   'succes'  => "Votre compte a été crée avec succes ! vous pouvez desormais vous connecter."  ,  ));    }
            }else{
               return $this->redirectToRoute('login');}


           }


           /**
           * @Route("/MesNotes", name="my_notes")
           */
           public function notesAction(Request $request)
           {
            if ( $this->getUser() ){

             $em = $this->getDoctrine()->getManager();

             $eleve = $em->getRepository("AppBundle:Etudiant")->findOneBy(array('user' => $this->getUser()->getId()));


             return $this->render('@App/user_etudiant/mes_notes.html.twig', array(
              'eleve' => $eleve,
            ));


           }
           return $this->redirectToRoute('login');
         }

          /**
           * @Route("/MonEnseignant", name="my_prof")
           */
           public function profAction(Request $request)
           {
            if ( $this->getUser() ){

             $em = $this->getDoctrine()->getManager();

             $eleve = $em->getRepository("AppBundle:Etudiant")->findOneBy(array('user' => $this->getUser()->getId()));

             $prof = $em->getRepository('AppBundle:User')->findOneBy(array('id' => 1));

             return $this->render('@App/user_etudiant/mon_enseignant.html.twig', array(
              'eleve' => $eleve,
              'prof' => $prof,
            ));
             
             
           }
           return $this->redirectToRoute('login');
         }


}

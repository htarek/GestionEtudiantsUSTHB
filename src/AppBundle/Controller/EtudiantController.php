<?php

  namespace AppBundle\Controller;

  use AppBundle\Entity\Etudiant;
  use AppBundle\Entity\User;
  use AppBundle\Entity\Groupe;
  use AppBundle\Entity\Section;
  use AppBundle\Entity\Message_prive;
  use AppBundle\Entity\Examen_etudiant;


  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

  /**
   * Etudiant controller.
   *
   * @Route("Etudiant")
   */
  class EtudiantController extends Controller
  { 
      

       /**
       * 
       *
       * @Route("/addEtudiant", name="etudiant_add")
       * @Method("POST")
       */
      public function addAction(Request $request)
      { 
       $em = $this->getDoctrine()->getManager();


       $user = new User(); 
               
             if(strcmp($request->request->get('_passwordbis'), $request->request->get('_passwordvalidate')) != 0){
                $this->addFlash("Erreur", "Les mots de passes entrés ne sont pas identiques");
                  return $this->redirectToRoute('etudiants_index',array('erreurajout' => true));

             }

              $uniquetest=$em->getRepository("AppBundle:User")->findOneBy(array('email' =>$request->request->get('email')));
              if($uniquetest != null){
                  $this->addFlash("Erreur", "Cette adresse email est déjà prise");
                  return $this->redirectToRoute('etudiants_index',array('erreurajout' => true));
              }




              $user->setUsername( $request->request->get('_usernamebis')) ;
              $user->setEmail( $request->request->get('_usernamebis')) ;
              $user->setNom( $request->request->get('nom')) ;
              $user->setPrenom( $request->request->get('prenom')) ;
              $user->setPassword(password_hash($request->request->get('_passwordbis'), PASSWORD_DEFAULT));
              $user->setRoles(array("ROLE_ETUDIANT"));
              $user->setSalt("");
              $user->setEnabled(true);

              $etudiant = new Etudiant();
              $etudiant->setMatricule($request->request->get('matricule'));

              $groupe=$em->getRepository("AppBundle:Groupe")->findOneBy(array('id' => $request->request->get('groupe')));
              $etudiant->setGroupe($groupe);
              $em->persist($user);

              $etudiant->setUser($user);

              $em->persist($etudiant); 
              $em->flush();

               if ($_FILES['photo']['error'] == 0)
              {
                  if ($_FILES['photo']['size'] <= 1048576)
                  { 
                      $infosfichier = pathinfo($_FILES['photo']['name']);
                      $extension_upload = $infosfichier['extension'];
                      $extensions_autorisees = array('jpg', 'jpeg','webp', 'JPG', 'PNG',
                          'png');
                      if (in_array($extension_upload,
                          $extensions_autorisees))
                      {
                          move_uploaded_file($_FILES['photo']['tmp_name'], 'assets/public/users/images/' .
                              basename("user_".$user->getId()).".jpg");
                          $user = $em->getRepository("AppBundle:User")->findOneBy(array('id' => $user->getId()));
                          $user->setImage("user_".$user->getId().".jpg");
                      }else{
                          $em->persist($user);
                          $em->remove($user);
                          $em->flush();
                          $this->addFlash("Erreur", "Extension incorrecte.");
                          return $this->redirectToRoute('users_index', array('erreurajout' => true));

                      }
                  }else{   $this->addFlash("Erreur", "La taille de l'image est trop grande");
                      $em->persist($user);
                      $em->remove($user);
                      $em->flush();

                      return $this->redirectToRoute('users_index', array('erreurajout' => true));
                  }
              }

              $em->flush();


              $this->addFlash("Succès", "L'étudiant a bien été ajouté");
          
              return $this->redirectToRoute('etudiants_index');

              }  



          /**
       * Finds and displays a etudiant entity.
       *
       * @Route("/{id}", name="etudiant_show")
       * @Method("GET")
       */
      public function showAction(Etudiant $etudiant,Request $request)
      {

         if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }

          $em = $this->getDoctrine()->getManager();

          $sections = $em->getRepository('AppBundle:Section')->findAll();
          $prof = $em->getRepository('AppBundle:User')->findOneBy(array('id' => 1));
          if($request->isMethod('GET'))
              {
                  $erreur = $request->query->get('erreurajout');
              }

          return $this->render('@App/etudiant/etudiant_show.html.twig', array(
              'etudiant' => $etudiant,
              'prof' => $prof,
              'sections' => $sections,
              'erreurajout' => $erreur
          ));
      }



      /**
       * Lists all etudiant entities.
       *
       * @Route("/", name="etudiants_index")
       * @Method("GET")
       */
      public function indexAction(Request $request)
      {
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
          $em = $this->getDoctrine()->getManager();

          $etudiants = $em->getRepository('AppBundle:Etudiant')->findAll();
          $sections = $em->getRepository('AppBundle:Section')->findAll();
          if ($request->isMethod('GET'))
              {
                  $erreur = $request->query->get('erreurajout');
              }

          return $this->render('@App/etudiant/etudiants_index.html.twig', array(
              'etudiants' => $etudiants,
              'sections' => $sections,
              'erreurajout' => $erreur
          ));
      }

      /**
       *
       * @Route("/editEtudiant/{id}", name="edit_etudiant")
       * @Method({"GET", "POST"})
       */
      public function editAction(Request $request,$id){

        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }

          if ($request->isMethod('POST')){

              $url = $this->generateUrl(
              'etudiant_show',
              array('id' => $id));


              $em = $this->getDoctrine()->getManager();
              
              $uniquetest=$em->getRepository("AppBundle:User")->findOneBy(array('email' =>$request->request->get('_usernamebis')));
              $uniquetest=$em->getRepository("AppBundle:Etudiant")->findOneBy(array('user' =>$uniquetest->getId()));
              if($uniquetest != null && $uniquetest->getId() != $id){
                  $this->addFlash("Erreur", "Cette adresse email est déjà prise");
              return $this->redirect($url);
              }
              $etudiant=$em->getRepository("AppBundle:Etudiant")->findOneBy(array('id' => $id));
              $etudiant->getUser()->setEmail( $request->request->get('_usernamebis')) ;
              $etudiant->getUser()->setNom( $request->request->get('nom')) ;
              $etudiant->getUser()->setPrenom( $request->request->get('prenom')) ;
              $etudiant->setMatricule($request->request->get('matricule'));
              $groupe=$em->getRepository("AppBundle:Groupe")->findOneBy(array('id' => $request->request->get('groupe')));
              $etudiant->setGroupe($groupe);

               if ($_FILES['photo']['error'] == false)
              {
                  if ($_FILES['photo']['size'] <= 1048576)
                  {
                      $infosfichier = pathinfo($_FILES['photo']['name']);
                      $extension_upload = $infosfichier['extension'];
                      $extensions_autorisees = array('jpg', 'jpeg','webp', 'JPG', 'PNG',
                          'png');
                      if (in_array($extension_upload,
                          $extensions_autorisees))
                      {
                          move_uploaded_file($_FILES['photo']['tmp_name'], 'assets/public/users/images/' .
                              basename("user_".$etudiant->getUser()->getId()).".jpg");
                          $etudiant->getUser()->setImage("user_".$etudiant->getUser()->getId().".jpg");

                      }else{        $this->addFlash("Erreur", "Extension incorrecte");
              return $this->redirect($url);
                      }
                  }else{   $this->addFlash("Erreur", "La taille de l'image est trop grande");
              return $this->redirect($url); }
              }else{
              }

              $em->flush();
              $this->addFlash("Succès", "L'étudiant a bien été modifié");

              
              return $this->redirect($url);

         }
     }

       /**
       *
       * @Route("/deleteEtudiant/{id}", name="supp_etudiant")
       * @Method({"GET", "POST"})
       */
      public function deleteAction($id){

        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
          $em = $this->getDoctrine()->getManager();

          $user=$em->getRepository("AppBundle:Etudiant")->findOneBy(array('id' => $id));
          $em->remove($user);
          $em->flush();
          $this->addFlash("Succès", "L'étudiant a bien été supprimé");
          return $this->redirectToRoute('etudiants_index');
          }



       /**
       *
       * @Route("/message/{id}", name="message")
       * @Method({"GET", "POST"})
       */
      public function messageAction(Request $request,$id){

        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }

      $em = $this->getDoctrine()->getManager();
      $msg = new Message_prive;
      $msg->setContent($request->request->get('content'));
      $msg->setSeen(false);
      
      if($this->getUser()->getId()==1){
      $msg->setReceptor(true);
      $etudiant=$em->getRepository("AppBundle:Etudiant")->findOneBy(array('id' =>$id));
      }else{
      $msg->setReceptor(false);
      $etudiant=$em->getRepository("AppBundle:Etudiant")->findOneBy(array('id' =>$this->getUser()->getId()));
      }
     
      $msg->setEtudiant($etudiant);

      $em->persist($msg);
      $em->flush();

       $url = $this->generateUrl(
              'etudiant_show',
              array('id' => $id));

       $this->addFlash("Succès", "Message envoyé");
              return $this->redirect($url);


      }


       /**
       *
       * @Route("/validate/{id}", name="enable")
       * @Method("GET")
       */
      public function validateAction($id){
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository("AppBundle:User")->findOneBy(array('id' =>$id));
        $user->setEnabled(true);
        $em->flush();
        $this->addFlash("Succès", "Le compte a bien été validé");
              return $this->redirectToroute('etudiants_index');
        }



         /**
       *
       * @Route("/noter/{id}", name="add_notes")
       * @Method({"GET","POST"})
       */
      public function noterAction($id,Request $request){
        if ( $this->getUser() == null ){
              return $this->redirectToRoute('login');
            }
      $em = $this->getDoctrine()->getManager();
      $etudiant=$em->getRepository("AppBundle:Etudiant")->findOneBy(array('id' =>$id));

      foreach($etudiant->getGroupe()->getSection()->getExamens() as $exam){
        $exam_etudiant=$em->getRepository("AppBundle:Examen_etudiant")->findOneBy(array('etudiant' =>$etudiant->getId(),
                                                                                        'examen' =>$exam->getId()));

        if($exam_etudiant == null){
        $exam_etudiant = new Examen_etudiant;}

        $exam_etudiant->setNote($request->request->get('exam-'.$exam->getId()));
        $exam_etudiant->setEtudiant($etudiant);
        $exam_etudiant->setExamen($exam);
        $em->persist($exam_etudiant);

      }

      $em->flush();
      $url = $this->generateUrl(
              'etudiant_show',
              array('id' => $id));

       $this->addFlash("Succès", "Les notes ont bien été ajoutées");
              return $this->redirect($url);


      }








  }


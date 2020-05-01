<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormError;

use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Jubilaciones\DeclaracionesBundle\Form\ImportacionType;
use Jubilaciones\DeclaracionesBundle\Entity\Importacion;
use Jubilaciones\DeclaracionesBundle\Entity\User;


class AdminImportacionController extends Controller {

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $archivos = $em->getRepository('JubilacionesDeclaracionesBundle:Importacion')->findAll();
        //dump($usuarios);die;
        return $this->render('@JubilacionesDeclaraciones/AdminImportacion/listar.html.twig', array(
                    'archivos' => $archivos
        ));
    }

    public function nuevoAction(Request $request) {
        $importacion = new Importacion();
        $form = $this->createForm(ImportacionType::class, $importacion)
        ->add('Guardar', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          // 3) Encode the password (you could also do this via Doctrine listener)
            //$password = $passwordEncoder->encodePassword($importacion, $importacion->getPlainPassword());
            //$importacion->setPassword($password);
            //dump('Password: '.$password);die;
            // 4) save the User!
            //Obtenemos el archivo del formulario y lo subimos al servidor
            $fileImportacion = $form->get('archivo')->getData();
            $contenido = file_get_contents($fileImportacion);
            // Le ponemos un nombre al fichero

            $importacion->setFechaCreacion(new \DateTime('now'));
            $file_name = $importacion->getNombre().".txt";

            $em = $this->getDoctrine()->getManager();
            $em->persist($importacion);
            $em->flush();
            $fileImportacion->move("uploads", $file_name);

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            // Mensaje para notificar al usuario que todo ha salido bien
            AbstractBaseController::addInfoMessage('El Archivo de importacion ' .$importacion->getNombre() .'  sido Creado.');
            return $this->redirectToRoute('admin_importacion_listar');
        }
        return $this->render('@JubilacionesDeclaraciones/AdminImportacion/nuevo.html.twig', array('form' => $form->createView()
        ));
    }


  public function importarAction($id) {
    $em = $this->getDoctrine()->getManager();
    $importacion = $em->getRepository('JubilacionesDeclaracionesBundle:Importacion')->findOneBy(array('id' => $id));
    $fileName = $importacion->getNombre().'.txt';
    $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);

    $lineas = count($archivo);
    //dump($archivo[0]);die;
    $passwordEncoder = $this->get('security.password_encoder');
    //$linea=explode(';',$archivo[0]);
    //dump($linea);die;
    $em = $this->getDoctrine()->getManager();
    for ($i = 0; $i < $lineas; $i++) {
        $usuario = new User();
        $username=explode(';',$archivo[$i])[0];
        $password=explode(';',$archivo[$i])[1];
        //dump($username.'-'.$password);die;
        $usuario->setUsername($username);
        $usuario->setPlainPassword($password);
        $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
        $usuario->setPassword($password);

        //dump('Password: '.$password);die;
        // 4) save the User!
        $em->persist($usuario);
        $em->flush();
    };
    return $this->redirectToRoute('admin_usuario_listar');
  }


  public function borrarAction($id) {
      $em = $this->getDoctrine()->getManager();
      $importacion = $em->getRepository('JubilacionesDeclaracionesBundle:Importacion')->findOneBy(array('id' => $id));
      $fileName = $importacion->getNombre().'.txt';
      // Para borrar el archivo
      $fs = new Filesystem();
      $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);

      $em->remove($importacion);
      $em->flush();
          AbstractBaseController::addWarnMessage('La Importacion de ' .
                  $importacion->getNombre() .
                  ' se ha borrado correctamente.');

      return $this->redirect($this->generateUrl('admin_importacion_listar'));
  }

}

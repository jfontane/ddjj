<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Jubilaciones\DeclaracionesBundle\Form\UserType;
use Jubilaciones\DeclaracionesBundle\Entity\User;

class AdminUsuarioController extends Controller {

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('JubilacionesDeclaracionesBundle:User')->findAll();
        //dump($usuarios);die;
        return $this->render('@JubilacionesDeclaraciones/AdminUsuario/listar.html.twig', array(
                    'usuarios' => $usuarios
        ));
    }

    public function nuevoAction(Request $request) {
        $passwordEncoder = $this->get('security.password_encoder');
        $usuario = new User();
        $form = $this->createForm(UserType::class, $usuario);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
            $usuario->setPassword($password);
            //dump('Password: '.$password);die;
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            // Mensaje para notificar al usuario que todo ha salido bien
            AbstractBaseController::addInfoMessage('El Usuario <b>' .$usuario->getUsername() .' </b> sido Creado.');
            return $this->redirectToRoute('admin_usuario_listar');
        }
        return $this->render('@JubilacionesDeclaraciones/AdminUsuario/nuevo.html.twig', array('form' => $form->createView()
        ));
    }


    public function cargarUsuariosAction(Request $request) {
        $passwordEncoder = $this->get('security.password_encoder');
        $usuario = new User();
        $form = $this->createForm(UserType::class, $usuario);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
            $usuario->setPassword($password);
            //dump('Password: '.$password);die;
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            // Mensaje para notificar al usuario que todo ha salido bien
            AbstractBaseController::addInfoMessage('El Usuario <b>' .$usuario->getUsername() .' </b> sido Creado.');
            return $this->redirectToRoute('login');
        }
        return $this->render('@JubilacionesDeclaraciones/AdminUsuario/nuevo.html.twig', array('form' => $form->createView()
        ));
    }



}

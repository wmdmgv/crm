<?php
namespace MyUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

use MyUser\Entity;

class UserController extends AbstractActionController
{
    private $repository;
    public function indexAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $page = $this->params()->fromRoute('page');
        $max = 10;

        //---Paging with query
        $query = $objectManager->createQuery('SELECT f FROM \MyUser\Entity\User f ORDER by f.id ASC');
        $adapter = new DoctrinePaginator(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
      //  var_dump($page);
        $paginator
            ->setCurrentPageNumber($page)
            ->setItemCountPerPage($max);
       // var_dump($paginator->getPages());
        //---


        // EXAMPLE of list user $users = $objectManager->getRepository('\MyUser\Entity\User')->findBy(array(), array('id' => 'DESC'), 2, 1);
        // var_dump($users);;


        //        WMD OLD VER
        //        if ($this->isAllowed('controller/MyUser\Controller\Users:edit')) {
        //            $users = $objectManager
        //                ->getRepository('\MyUser\Entity\User')
        //                ->findBy(array(), array('id' => 'DESC'));
        //        }
        //        else {
        //            $users = $objectManager
        //                ->getRepository('\MyUser\Entity\User')
        //                ->findBy(array('state' => 1), array('id' => 'DESC'));
        //        }
        //
        //        $users_array = array();
        //        foreach ($users as $user) {
        //            $users_array[] = $user->getArrayCopy();
        //        }

        $pgCntrl = $this->getServiceLocator()->get('viewhelpermanager')->get('paginationcontrol');
       // print_r($pgCntrl);
        //var_dump($pgCntrl($paginator, 'sliding', array('partial/paginator.twig', 'Users'), array('route' => 'users')));
        $view = new ViewModel(array(
            //'users' => $users_array,
            'users' => $paginator,
            'paginator' => $pgCntrl($paginator, 'sliding', array('partial/paginator.twig', 'Users'), array('route' => 'users'))
        ));

        // WMD get example of translate
        $translate = $this->getServiceLocator()->get('viewhelpermanager')->get('translate');
        $translate('deleted');

        return $view;
    }

    public function viewAction()
    {
        // Check if id and blogpost exists.
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('User id doesn\'t set');
            return $this->redirect()->toRoute('users');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $post = $objectManager
            ->getRepository('\MyUser\Entity\User')
            ->findOneBy(array('id' => $id));

        if (!$post) {
            $this->flashMessenger()->addErrorMessage(sprintf('User with id %s doesn\'t exists', $id));
            return $this->redirect()->toRoute('users');
        }

        // Render template.
        $view = new ViewModel(array(
            'user' => $post->getArrayCopy(),
        ));

        return $view;
    }

    public function addAction()
    {
        $form = new \MyBlog\Form\BlogPostForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $blogpost = new \MyBlog\Entity\BlogPost();

                $blogpost->exchangeArray($form->getData());

                $blogpost->setCreated(time());
                $blogpost->setUserId(0);

                $objectManager->persist($blogpost);
                $objectManager->flush();

                $message = 'Blogpost succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('blog');
            }
            else {
                $message = 'Error while saving blogpost';
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        // Check if id set.
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('User id doesn\'t set');
            return $this->redirect()->toRoute('users');
        }

        // Create form.
        $form = new \MyBlog\Form\BlogPostForm();
        $form->get('submit')->setValue('Save');

        $request = $this->getRequest();
        if (!$request->isPost()) {

            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $post = $objectManager
                ->getRepository('\MyUser\Entity\User')
                ->findOneBy(array('id' => $id));

            if (!$post) {
                $this->flashMessenger()->addErrorMessage(sprintf('User with id %s doesn\'t exists', $id));
                return $this->redirect()->toRoute('users');
            }

            // Fill form data.
            $form->bind($post);
            return array('form' => $form, 'id' => $id, 'post' => $post);
        }
        else {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $data = $form->getData();
                $id = $data['id'];
                try {
                    $blogpost = $objectManager->find('\MyBlog\Entity\BlogPost', $id);
                }
                catch (\Exception $ex) {
                    return $this->redirect()->toRoute('blog', array(
                        'action' => 'index'
                    ));
                }

                $blogpost->exchangeArray($form->getData());

                $objectManager->persist($blogpost);
                $objectManager->flush();

                $message = 'Blogpost succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('blog');
            }
            else {
                $message = 'Error while saving blogpost';
                $this->flashMessenger()->addErrorMessage($message);
                return array('form' => $form, 'id' => $id);
            }
        }
    }

    /** Delete user method
     * @return array|\Zend\Http\Response
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('User id doesn\'t set');
            return $this->redirect()->toRoute('users');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                try {
                    /** @var \MyUser\Entity\User $user */
                    $user = $objectManager->find('MyUser\Entity\User', $id);
                    $user->setState(0);
                    $objectManager->persist($user); //remove
                    $objectManager->flush();
                }
                catch (\Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('Error while deleting data');
                    return $this->redirect()->toRoute('users', array(
                        'action' => 'index'
                    ));
                }

                $this->flashMessenger()->addMessage(sprintf('User %d was succesfully deleted', $id));
            }

            return $this->redirect()->toRoute('users');
        }

        return array(
            'id'    => $id,
            'user' => $objectManager->find('MyUser\Entity\User', $id)->getArrayCopy(),
        );
    }

    /** Restore user method
     * @return array|\Zend\Http\Response
     */
    public function restoreAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('User id doesn\'t set');
            return $this->redirect()->toRoute('users');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $res = $request->getPost('res', 'No');

            if ($res == 'Yes') {
                $id = (int) $request->getPost('id');
                try {
                    /** @var \MyUser\Entity\User $user */
                    $user = $objectManager->find('MyUser\Entity\User', $id);
                    $user->setState(1);
                    $objectManager->persist($user); //remove
                    $objectManager->flush();
                }
                catch (\Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('Error while restored data');
                    return $this->redirect()->toRoute('users', array(
                        'action' => 'index'
                    ));
                }

                $this->flashMessenger()->addMessage(sprintf('User %d was succesfully restored', $id));
            }

            return $this->redirect()->toRoute('users');
        }

        return array(
            'id'    => $id,
            'user' => $objectManager->find('MyUser\Entity\User', $id)->getArrayCopy(),
        );
    }
}

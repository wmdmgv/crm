<?php
namespace MyClient\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

use MyClient\Entity;

use Zend\Form\Element;
use Zend\Form\Form;

class ClientController extends AbstractActionController
{
    private $repository;
    public function indexAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $page = $this->params()->fromRoute('page');
        $max = 5;

        //---Paging with query
        $query = $objectManager->createQuery('SELECT f FROM \MyClient\Entity\Client f ORDER by f.state DESC, f.id ASC');
        $adapter = new DoctrinePaginator(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
      //  var_dump($page);
        $paginator
            ->setCurrentPageNumber($page)
            ->setItemCountPerPage($max);

        $pgCntrl = $this->getServiceLocator()->get('viewhelpermanager')->get('paginationcontrol');
       // var_dump($paginator->getItemsByPage($page));
        $view = new ViewModel(array(
            'clients' => $paginator,
            'paginator' => $pgCntrl($paginator, 'sliding', array('partial/paginator.twig', 'Clients'), array('route' => 'clients'))
        ));
        return $view;
    }

    public function viewAction()
    {
        // Check if id and blogpost exists.
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Client id doesn\'t set');
            return $this->redirect()->toRoute('clients');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $client = $objectManager
            ->getRepository('\MyClient\Entity\Client')
            ->findOneBy(array('id' => $id));

        if (!$client) {
            $this->flashMessenger()->addErrorMessage(sprintf('Client with id %s doesn\'t exists', $id));
            return $this->redirect()->toRoute('clients');
        }
        /** @var \MyUser\Entity\User $user */
        $user = $this->zfcUserAuthentication()->getIdentity();
        var_dump($user->getRoles());
        //var_dump($client);
        // Render template.
        $view = new ViewModel(array(
            'client' => $client->getArrayCopy(),
        ));

        return $view;
    }

    public function addAction()
    {
        $form = new \MyClient\Form\ClientForm();
        $form->get('submit')->setValue('Add');

//        $form->add(array(
//            'type' => 'Zend\Form\Element\Select',
//            'name' => 'language',
//            'options' => array(
//                'label' => 'Which is your mother tongue?',
//                'empty_option' => 'Please choose your language',
//                'value_options' => array(
//                    '0' => 'French',
//                    '1' => 'English',
//                    '2' => 'Japanese',
//                    '3' => 'Chinese',
//                ),
//            )
//        ));
//
//        $select = new Element\Select('language');
//        $select->setLabel('Which is your mother tongue?');
//        $select->setValueOptions(array(
//            'european' => array(
//                'label' => 'European languages',
//                'options' => array(
//                    '0' => 'French',
//                    '1' => 'Italian',
//                ),
//            ),
//            'asian' => array(
//                'label' => 'Asian languages',
//                'options' => array(
//                    '2' => 'Japanese',
//                    '3' => 'Chinese',
//                ),
//            ),
//        ));
//
//        $form = new Form('language');
//        $form->add($select);



        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $firm = $objectManager->getRepository('\MyFirm\Entity\Firm')->findOneBy(array('id' => $form->get("firm_id")->getValue()));
                $user = $this->zfcUserAuthentication()->getIdentity();

                $client = new \MyClient\Entity\Client();
                $client->exchangeArray($form->getData());

                $client->setState(1);
                $client->setUser($user);
                $client->setFirm($firm);

                $objectManager->persist($client);
                $objectManager->flush();

                $message = 'Client succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('clients');
            }
            else {
                $message = 'Error while saving client';
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
            $this->flashMessenger()->addErrorMessage('Client id doesn\'t set');
            return $this->redirect()->toRoute('clients');
        }

        // Create form.
        $form = new \MyClient\Form\ClientForm();
        $form->get('submit')->setValue('Save');

        $request = $this->getRequest();
        if (!$request->isPost()) {

            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $client = $objectManager
                ->getRepository('\MyClient\Entity\Client')
                ->findOneBy(array('id' => $id));

            if (!$client) {
                $this->flashMessenger()->addErrorMessage(sprintf('Client with id %s doesn\'t exists', $id));
                return $this->redirect()->toRoute('clients');
            }

            // Fill form data.
            $form->bind($client);
            return array('form' => $form, 'id' => $id, 'client' => $client);
        }
        else {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $data = $form->getData();
                $id = $data['id'];
                try {
                    /** @var \MyClient\Entity\Client $clientP */
                    $clientP = $objectManager->find('\MyClient\Entity\Client', $id);
                }
                catch (\Exception $ex) {
                    return $this->redirect()->toRoute('clients', array(
                        'action' => 'index'
                    ));
                }

                $clientP->exchangeArray($form->getData());

                $clientP->setBalance(str_replace(",",".",$clientP->getBalance()));

                $objectManager->persist($clientP);
                $objectManager->flush();

                $message = 'Client succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('clients');
            }
            else {
                $message = 'Error while saving client';
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
            $this->flashMessenger()->addErrorMessage('Client id doesn\'t set');
            return $this->redirect()->toRoute('clients');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                try {
                    /** @var \MyClient\Entity\Client $user */
                    $user = $objectManager->find('MyClient\Entity\Client', $id);
                    $user->setState(0);
                    $objectManager->persist($user); //remove
                    $objectManager->flush();
                }
                catch (\Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('Error while deleting data');
                    return $this->redirect()->toRoute('clients', array(
                        'action' => 'index'
                    ));
                }

                $this->flashMessenger()->addMessage(sprintf('Client %d was succesfully deleted', $id));
            }

            return $this->redirect()->toRoute('clients');
        }

        return array(
            'id'    => $id,
            'client' => $objectManager->find('MyClient\Entity\Client', $id)->getArrayCopy(),
        );
    }

    /** Restore user method
     * @return array|\Zend\Http\Response
     */
    public function restoreAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Client id doesn\'t set');
            return $this->redirect()->toRoute('clients');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $res = $request->getPost('res', 'No');

            if ($res == 'Yes') {
                $id = (int) $request->getPost('id');
                try {
                    /** @var \MyClient\Entity\Client $user */
                    $user = $objectManager->find('MyClient\Entity\Client', $id);
                    $user->setState(1);
                    $objectManager->persist($user); //remove
                    $objectManager->flush();
                }
                catch (\Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('Error while restored data');
                    return $this->redirect()->toRoute('clients', array(
                        'action' => 'index'
                    ));
                }

                $this->flashMessenger()->addMessage(sprintf('Client %d was succesfully restored', $id));
            }

            return $this->redirect()->toRoute('clients');
        }

        return array(
            'id'    => $id,
            'client' => $objectManager->find('MyUser\Entity\User', $id)->getArrayCopy(),
        );
    }
}

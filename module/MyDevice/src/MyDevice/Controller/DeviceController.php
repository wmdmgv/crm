<?php
namespace MyDevice\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

use MyDevice\Entity;

class DeviceController extends AbstractActionController
{
    private $repository;
    public function indexAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $page = $this->params()->fromRoute('page');
        $max = 15;

        //---Paging with query
        $query = $objectManager->createQuery('SELECT f FROM \MyDevice\Entity\Device f ORDER by f.id ASC');
        $adapter = new DoctrinePaginator(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
      //  var_dump($page);
        $paginator
            ->setCurrentPageNumber($page)
            ->setItemCountPerPage($max);

        $pgCntrl = $this->getServiceLocator()->get('viewhelpermanager')->get('paginationcontrol');
        $view = new ViewModel(array(
            'devices' => $paginator,
            'paginator' => $pgCntrl($paginator, 'sliding', array('partial/paginator.twig', 'Devices'), array('route' => 'devices'))
        ));
        return $view;
    }

    public function viewAction()
    {
        // Check if id and blogpost exists.
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Device id doesn\'t set');
            return $this->redirect()->toRoute('devices');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $post = $objectManager
            ->getRepository('\MyDevice\Entity\Device')
            ->findOneBy(array('id' => $id));

        if (!$post) {
            $this->flashMessenger()->addErrorMessage(sprintf('Device with id %s doesn\'t exists', $id));
            return $this->redirect()->toRoute('devices');
        }

        // Render template.
        $view = new ViewModel(array(
            'device' => $post->getArrayCopy(),
        ));

        return $view;
    }

    public function addAction()
    {
        $form = new \MyDevice\Form\DeviceForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $device = new \MyDevice\Entity\Device();

                $device->exchangeArray($form->getData());

                $device->setState(1);

                $objectManager->persist($device);
                $objectManager->flush();

                $message = 'Device succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('devices');
            }
            else {
                $message = 'Error while saving device';
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
            $this->flashMessenger()->addErrorMessage('Device id doesn\'t set');
            return $this->redirect()->toRoute('devices');
        }

        // Create form.
        $form = new \MyDevice\Form\DeviceForm();
        $form->get('submit')->setValue('Save');

        $request = $this->getRequest();
        if (!$request->isPost()) {

            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $device = $objectManager
                ->getRepository('\MyDevice\Entity\Device')
                ->findOneBy(array('id' => $id));

            if (!$device) {
                $this->flashMessenger()->addErrorMessage(sprintf('Device with id %s doesn\'t exists', $id));
                return $this->redirect()->toRoute('devices');
            }

            // Fill form data.
            $form->bind($device);
            return array('form' => $form, 'id' => $id, 'device' => $device);
        }
        else {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $data = $form->getData();
                $id = $data['id'];
                try {
                    $deviceP = $objectManager->find('\MyDevice\Entity\Device', $id);
                }
                catch (\Exception $ex) {
                    return $this->redirect()->toRoute('devices', array(
                        'action' => 'index'
                    ));
                }

                $deviceP->exchangeArray($form->getData());

                $objectManager->persist($deviceP);
                $objectManager->flush();

                $message = 'Device succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('devices');
            }
            else {
                $message = 'Error while saving device';
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
            $this->flashMessenger()->addErrorMessage('Device id doesn\'t set');
            return $this->redirect()->toRoute('devices');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                try {
                    /** @var \MyDevice\Entity\Device $user */
                    $user = $objectManager->find('MyDevice\Entity\Device', $id);
                    $user->setState(0);
                    $objectManager->persist($user); //remove
                    $objectManager->flush();
                }
                catch (\Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('Error while deleting data');
                    return $this->redirect()->toRoute('devices', array(
                        'action' => 'index'
                    ));
                }

                $this->flashMessenger()->addMessage(sprintf('Device %d was succesfully deleted', $id));
            }

            return $this->redirect()->toRoute('devices');
        }

        return array(
            'id'    => $id,
            'device' => $objectManager->find('MyDevice\Entity\Device', $id)->getArrayCopy(),
        );
    }

    /** Restore user method
     * @return array|\Zend\Http\Response
     */
    public function restoreAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Device id doesn\'t set');
            return $this->redirect()->toRoute('devices');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $res = $request->getPost('res', 'No');

            if ($res == 'Yes') {
                $id = (int) $request->getPost('id');
                try {
                    /** @var \MyDevice\Entity\Device $user */
                    $user = $objectManager->find('MyDevice\Entity\Device', $id);
                    $user->setState(1);
                    $objectManager->persist($user); //remove
                    $objectManager->flush();
                }
                catch (\Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('Error while restored data');
                    return $this->redirect()->toRoute('devices', array(
                        'action' => 'index'
                    ));
                }

                $this->flashMessenger()->addMessage(sprintf('Device %d was succesfully restored', $id));
            }

            return $this->redirect()->toRoute('devices');
        }

        return array(
            'id'    => $id,
            'device' => $objectManager->find('MyUser\Entity\User', $id)->getArrayCopy(),
        );
    }
}

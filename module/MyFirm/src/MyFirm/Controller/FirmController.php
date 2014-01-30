<?php
namespace MyFirm\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

use MyFirm\Entity;

class FirmController extends AbstractActionController
{
    private $repository;
    public function indexAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $page = $this->params()->fromRoute('page');
        $max = 5;

        //---Paging with query
        $query = $objectManager->createQuery('SELECT f FROM \MyFirm\Entity\Firm f ORDER by f.state DESC, f.id ASC');
        $adapter = new DoctrinePaginator(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
      //  var_dump($page);
        $paginator
            ->setCurrentPageNumber($page)
            ->setItemCountPerPage($max);

        $pgCntrl = $this->getServiceLocator()->get('viewhelpermanager')->get('paginationcontrol');
        $view = new ViewModel(array(
            'firms' => $paginator,
            'paginator' => $pgCntrl($paginator, 'sliding', array('partial/paginator.twig', 'Firms'), array('route' => 'firms'))
        ));
        return $view;
    }

    public function viewAction()
    {
        // Check if id and blogpost exists.
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Firm id doesn\'t set');
            return $this->redirect()->toRoute('firms');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $firm = $objectManager
            ->getRepository('\MyFirm\Entity\Firm')
            ->findOneBy(array('id' => $id));

        if (!$firm) {
            $this->flashMessenger()->addErrorMessage(sprintf('Firm with id %s doesn\'t exists', $id));
            return $this->redirect()->toRoute('firms');
        }

        // Render template.
        $view = new ViewModel(array(
            'firm' => $firm->getArrayCopy(),
        ));

        return $view;
    }

    public function addAction()
    {
        $form = new \MyFirm\Form\FirmForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $firm = new \MyFirm\Entity\Firm();

                $firm->exchangeArray($form->getData());

                $firm->setState(1);

                $objectManager->persist($firm);
                $objectManager->flush();

                $message = 'Firm succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('firms');
            }
            else {
                $message = 'Error while saving firm';
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
            $this->flashMessenger()->addErrorMessage('Firm id doesn\'t set');
            return $this->redirect()->toRoute('firms');
        }

        // Create form.
        $form = new \MyFirm\Form\FirmForm();
        $form->get('submit')->setValue('Save');

        $request = $this->getRequest();
        if (!$request->isPost()) {

            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $firm = $objectManager
                ->getRepository('\MyFirm\Entity\Firm')
                ->findOneBy(array('id' => $id));

            if (!$firm) {
                $this->flashMessenger()->addErrorMessage(sprintf('Firm with id %s doesn\'t exists', $id));
                return $this->redirect()->toRoute('firms');
            }

            // Fill form data.
            $form->bind($firm);
            return array('form' => $form, 'id' => $id, 'firm' => $firm);
        }
        else {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $data = $form->getData();
                $id = $data['id'];
                try {
                    $firmP = $objectManager->find('\MyFirm\Entity\Firm', $id);
                }
                catch (\Exception $ex) {
                    return $this->redirect()->toRoute('firms', array(
                        'action' => 'index'
                    ));
                }

                $firmP->exchangeArray($form->getData());

                $objectManager->persist($firmP);
                $objectManager->flush();

                $message = 'Firm succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('firms');
            }
            else {
                $message = 'Error while saving firm';
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
            $this->flashMessenger()->addErrorMessage('Firm id doesn\'t set');
            return $this->redirect()->toRoute('firms');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                try {
                    /** @var \MyFirm\Entity\Firm $user */
                    $user = $objectManager->find('MyFirm\Entity\Firm', $id);
                    $user->setState(0);
                    $objectManager->persist($user); //remove
                    $objectManager->flush();
                }
                catch (\Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('Error while deleting data');
                    return $this->redirect()->toRoute('firms', array(
                        'action' => 'index'
                    ));
                }

                $this->flashMessenger()->addMessage(sprintf('Firm %d was succesfully deleted', $id));
            }

            return $this->redirect()->toRoute('firms');
        }

        return array(
            'id'    => $id,
            'firm' => $objectManager->find('MyFirm\Entity\Firm', $id)->getArrayCopy(),
        );
    }

    /** Restore user method
     * @return array|\Zend\Http\Response
     */
    public function restoreAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Firm id doesn\'t set');
            return $this->redirect()->toRoute('firms');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $res = $request->getPost('res', 'No');

            if ($res == 'Yes') {
                $id = (int) $request->getPost('id');
                try {
                    /** @var \MyFirm\Entity\Firm $user */
                    $user = $objectManager->find('MyFirm\Entity\Firm', $id);
                    $user->setState(1);
                    $objectManager->persist($user); //remove
                    $objectManager->flush();
                }
                catch (\Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('Error while restored data');
                    return $this->redirect()->toRoute('firms', array(
                        'action' => 'index'
                    ));
                }

                $this->flashMessenger()->addMessage(sprintf('Firm %d was succesfully restored', $id));
            }

            return $this->redirect()->toRoute('firms');
        }

        return array(
            'id'    => $id,
            'firm' => $objectManager->find('MyUser\Entity\User', $id)->getArrayCopy(),
        );
    }
}

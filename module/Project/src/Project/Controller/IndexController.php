<?php

namespace Project\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Project\Model\ProjectContributorEntity;
use Project\Model\ProjectEntity;
use Project\Model\ProjectSpendingRequestEntity;
use Project\Model\ProjectSpendingRequestVoteEntity;

use Gumlet\ImageResize;

class IndexController extends AbstractActionController
{
  public function getProjectMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('ProjectMapper');
  }

  public function getProjectContributorMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('ProjectContributorMapper');
  }

  public function getProjectSpendingRequestMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('ProjectSpendingRequestMapper');
  }

  public function getProjectSpendingRequestVoteMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('ProjectSpendingRequestVoteMapper');
  }

  public function indexAction()
  {
    return new ViewModel([
    ]);
  }

  public function createAction()
  {
    $config = $this->getServiceLocator()->get('Config');

    $form = $this->getServiceLocator()->get('ProjectForm');
    $user = new ProjectEntity();
    if($this->getRequest()->isPost()) {
      $data = $this->params()->fromPost();
      $form->setData($data);
      if($form->isValid()) {
        $isError = false;

        $data = $form->getData();

        $name = $data['name'];
        $description = $data['description'];
        $minimum_contribution = $data['minimum_contribution'];

        if(!isset($_FILES['photo'])){
          $isError = true;
	        $form->get('photo')->setMessages(array('Required field photo.'));
		    }else{
	        $allowed =  array('jpg');
	        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
	        if(!in_array($ext, $allowed) ) {
            $isError = true;
            $form->get('photo')->setMessages(array("File type not allowed. Only " . implode(',', $allowed)));
	        }
	        switch ($_FILES['photo']['error']){
            case 1:
              $isError = true;
              $form->get('photo')->setMessages(array('The file is bigger than this PHP installation allows.'));
              break;
            case 2:
              $isError = true;
              $form->get('photo')->setMessages(array('The file is bigger than this form allows.'));
              break;
            case 3:
              $isError = true;
              $form->get('photo')->setMessages(array('Only part of the file was uploaded.'));
              break;
            case 4:
              $isError = true;
              $form->get('photo')->setMessages(array('No file was uploaded.'));
              break;
            default:
	        }
		    }

        if(!$isError){
          $project = new ProjectEntity;
          $project->setName($data['name']);
          $project->setDescription($data['description']);
          $project->setEosPublicAddress('thegigamike1');
          $project->setMinimumContribution($data['minimum_contribution']);
          $this->getProjectMapper()->save($project);

          $directory = $config['pathProjectPhoto']['absolutePath'] . $project->getId();
          if(!file_exists($directory)){
            mkdir($directory, 0755);
          }

          $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
          $destination = $directory . "/photo-orig." . $ext;
          if(!file_exists($destination)){
             move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
          }
          $destination2 = $directory . "/photo-480x320." . $ext;
          if(file_exists($destination2)){
             unlink($destination2);
          }
          $image = new ImageResize($destination);
          $image->resize(480, 320);
          $image->save($destination2);

          $command = 'sudo cleos push action peos3 create \'["peos"]\' -p bob@active';
          exec($command, $output);

          $this->flashMessenger()->setNamespace('success')->addMessage('Project added successfully.');
          return $this->redirect()->toRoute('project', array('action' => 'view', 'id' => $project->getId(),));
        }
      }
    }

    return new ViewModel([
      'form' => $form,
    ]);
  }

  public function viewAction()
  {
    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('home');
    }
    $project = $this->getProjectMapper()->getProject($id);
    if(!$project){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }

    if($_POST){
      $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
      if(!empty($amount) && is_numeric($amount)){
        $projectContributor = new ProjectContributorEntity();
        $projectContributor->setProjectId($project->getId());
        $projectContributor->setEosPublicAddress('EOS85kJTsjfgTDzuPyhYCLx4ZSR6wWfpfK1A3bEJNFhnp6eR5mkYn');
        $projectContributor->setAmount($amount);
        $this->getProjectContributorMapper()->save($projectContributor);

        $command = 'sudo cleos push action peos3 contribute \'["peos"]\' -p bob@active';
        exec($command, $output);

        $this->flashMessenger()->setNamespace('success')->addMessage('Thankyou for backing up this project. You can now vote!');
        return $this->redirect()->toRoute('project', array('action' => 'view', 'id' => $project->getId(),));
      }
    }

    $filter = array(
      'project_id' => $project->getId(),
      'is_finalized' => 'N',
    );
    $countProjectSpendingRequest = $this->getProjectSpendingRequestMapper()->getCountProjectSpendingRequest($filter);
    $countProjectSpendingRequest = is_null($countProjectSpendingRequest['count_id']) ? 0 : $countProjectSpendingRequest['count_id'];

    $filter = array(
      'project_id' => $project->getId(),
    );
    $countProjectContributor = $this->getProjectContributorMapper()->getCountProjectContributor($filter);
    $countProjectContributor = is_null($countProjectContributor['count_id']) ? 0 : $countProjectContributor['count_id'];

    $sumProjectContributorAmount = $this->getProjectContributorMapper()->getSumProjectContributorAmount($filter);
    $sumProjectContributorAmount = is_null($sumProjectContributorAmount['sum_amount']) ? 0 : $sumProjectContributorAmount['sum_amount'];

    return new ViewModel([
      'project' => $project,
      'countProjectSpendingRequest' => $countProjectSpendingRequest,
      'countProjectContributor' => $countProjectContributor,
      'sumProjectContributorAmount' => $sumProjectContributorAmount,
    ]);
  }

  public function requestAction()
  {
    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('home');
    }
    $project = $this->getProjectMapper()->getProject($id);
    if(!$project){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }

    $filter = array(
      'project_id' => $project->getId(),
    );
    $order=array();
    $projectSpendingRequests = $this->getProjectSpendingRequestMapper()->getProjectSpendingRequests(false, $filter, $order);

    return new ViewModel([
      'project' => $project,
      'projectSpendingRequests' => $projectSpendingRequests,
    ]);
  }

  public function addRequestAction()
  {
    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('home');
    }
    $project = $this->getProjectMapper()->getProject($id);
    if(!$project){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }

    $config = $this->getServiceLocator()->get('Config');

    $form = $this->getServiceLocator()->get('ProjectSpendingRequestForm');
    $user = new ProjectEntity();
    if($this->getRequest()->isPost()) {
      $data = $this->params()->fromPost();
      $form->setData($data);
      if($form->isValid()) {
        $isError = false;

        $data = $form->getData();

        $description = $data['description'];
        $amount = $data['amount'];
        $supplier_id = $data['supplier_id'];

        $projectSpendingRequest = new ProjectSpendingRequestEntity;
        $projectSpendingRequest->setProjectId($project->getId());
        $projectSpendingRequest->setDescription($data['description']);
        $projectSpendingRequest->setAmount($data['amount']);
        $projectSpendingRequest->setSupplierId($data['supplier_id']);
        $projectSpendingRequest->setIsFinalized('N');
        $this->getProjectSpendingRequestMapper()->save($projectSpendingRequest);

        $command = 'sudo cleos push action peos3 request \'["peos"]\' -p bob@active';
        exec($command, $output);

        $this->flashMessenger()->setNamespace('success')->addMessage('Spending request added successfully.');
        return $this->redirect()->toRoute('project', array('action' => 'request', 'id' => $project->getId(),));
      }
    }

    return new ViewModel([
      'project' => $project,
      'form' => $form,
    ]);
  }

  public function voteYesAction()
  {
    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('home');
    }
    $projectSpendingRequest = $this->getProjectSpendingRequestMapper()->getProjectSpendingRequest($id);
    if(!$projectSpendingRequest){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }
    $project = $this->getProjectMapper()->getProject($projectSpendingRequest->getProjectId());
    if(!$project){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }

    $projectSpendingRequestVote = new ProjectSpendingRequestVoteEntity();
    $projectSpendingRequestVote->setProjectSpendingRequestId($projectSpendingRequest->getId());
    $projectSpendingRequestVote->setEosPublicAddress('EOS85kJTsjfgTDzuPyhYCLx4ZSR6wWfpfK1A3bEJNFhnp6eR5mkYn');
    $projectSpendingRequestVote->setIsApproved('Y');
    $this->getProjectSpendingRequestVoteMapper()->save($projectSpendingRequestVote);

    $command = 'sudo cleos push action peos3 voteyes \'["peos"]\' -p bob@active';
    exec($command, $output);

    $this->flashMessenger()->setNamespace('success')->addMessage('Vote added successfully.');
    return $this->redirect()->toRoute('project', array('action' => 'request', 'id' => $project->getId(),));
  }

  public function voteNoAction()
  {
    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('home');
    }
    $projectSpendingRequest = $this->getProjectSpendingRequestMapper()->getProjectSpendingRequest($id);
    if(!$projectSpendingRequest){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }
    $project = $this->getProjectMapper()->getProject($projectSpendingRequest->getProjectId());
    if(!$project){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }

    $projectSpendingRequestVote = new ProjectSpendingRequestVoteEntity();
    $projectSpendingRequestVote->setProjectSpendingRequestId($projectSpendingRequest->getId());
    $projectSpendingRequestVote->setEosPublicAddress('EOS85kJTsjfgTDzuPyhYCLx4ZSR6wWfpfK1A3bEJNFhnp6eR5mkYn');
    $projectSpendingRequestVote->setIsApproved('N');
    $this->getProjectSpendingRequestVoteMapper()->save($projectSpendingRequestVote);

    $command = 'sudo cleos push action peos3 voteno \'["peos"]\' -p bob@active';
    exec($command, $output);

    $this->flashMessenger()->setNamespace('success')->addMessage('Vote added successfully.');
    return $this->redirect()->toRoute('project', array('action' => 'request', 'id' => $project->getId(),));
  }

  public function finalizeAction()
  {
    $id = (int)$this->params('id');
    if (!$id) {
      return $this->redirect()->toRoute('home');
    }
    $projectSpendingRequest = $this->getProjectSpendingRequestMapper()->getProjectSpendingRequest($id);
    if(!$projectSpendingRequest){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }
    $project = $this->getProjectMapper()->getProject($projectSpendingRequest->getProjectId());
    if(!$project){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Project.');
      return $this->redirect()->toRoute('home');
    }

    $projectSpendingRequest->setIsFinalized('Y');
    $this->getProjectSpendingRequestMapper()->save($projectSpendingRequest);

    $command = 'sudo cleos push action peos3 finalize \'["peos"]\' -p bob@active';
    exec($command, $output);

    $this->flashMessenger()->setNamespace('success')->addMessage('Spending request finalize. Payment send to supplier.');
    return $this->redirect()->toRoute('project', array('action' => 'request', 'id' => $project->getId(),));
  }
}

<?php
class BackendFormsMetaController extends Controller{

    function init(){
        //
    }
    function getSubcontrollerName($base_name){
        return "Backend{$base_name}";
    }

    function indexAction(){
        global $site; 
        $request = $site->getRequest();
        $response = $site->getResponse();
        $dbh = $site->getDatabase();
        $search = $request->param('search', '');
        $show = $request->param('show', 30);
        $page = $request->param('page', 1);
        $search_s = $dbh->quote("%{$search}%");
        $conditions = '1';
        $conditions .= $search ? "AND (name LIKE {$search_s} OR value LIKE {$search_s})" : '';
        $params = [];
        $params['show'] = $show;
        $params['page'] = $page;
		$params['conditions'] = $conditions;
		$items = FormsMeta::all($params);
		$total = FormsMeta::count($conditions);
		
		$data = [];
		$data['items'] = $items;
		$data['total'] = $total;
		$data['search'] = $search;
        $data['show'] = $show;

        $site->render('backend/formsmeta/page-index', $data);
        return $response->respond(); 
    }

    function newAction(){
        global $site;
        $request = $site->getRequest();
        $response = $site->getResponse();
        
        switch ($request->type) {
            case 'get':
                $site->render('backend/formsmeta/page-new');
                break;
            case 'post':
            $id_form = $request->post('id_form');
            $name = $request->post('name');
            $value = $request->post('value');
            //$value = $request->post();

            $formeta = new FormMeta();
            $formeta->id_form = $id_form; 
            $formeta->name = $name; 
            $formeta->value = $value; 
            $formeta->save();
            //print_a($formeta);exit;
            $site->redirectTo($site->urlTo('/backend/formsmeta?msg=220'));
            
            break;
        }
        return $response->respond();
    }
    function editAction($id){
        global $site; 
        $request = $site->getRequest();
        $response = $site->getResponse();

        $form = FormsMeta::getById($id);
        if(! $form){
            $site->redirectTo($site->urlTo('/backend/formsmeta'));
        }
        switch ($request->type) {
            case 'get':
                $data = array();
                $data['item'] = $form; 
                $site->render('backend/formsmeta/page-edit', $data);
            break;
            case 'post':
                $id_form = $request->post('id_form');
                $name = $request->post('name');
                $value = $request->post('value');

                $formeta = new FormMeta();
                $formeta->id_form = $id_form;
                $formeta->name = $name; 
                $formeta->value = $value; 
                $formeta->save();

                $site->redirectTo($site->urlTo('/backend/formsmeta?msg=220'));
            break;
        }
        return $response->respond();
    }

    function deleteAction($id){
        global $site; 
        $request = $site->getRequest();
        $response = $site->getResponse();
        $form = FormsMeta::getById($id);
        if(!$form){
            $site->redirectTo($site->urlTo('/backend/formsmeta/'));
        }

        switch ($request->type) {
            case 'get':
                $data = array();
                $data['item'] = $form;
                $site->render('backend/formsmeta/page-delete', $data);
            break;
            case 'post':
                $form->delete();
                $site->redirectTo($site->urlTo('/backend/formsmeta?msg=230'));              
            break;
        }
        return $response->respond();
    }  
}
?>
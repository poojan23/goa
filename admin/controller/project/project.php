<?php

class ControllerProjectProject extends PT_Controller {

    private $error = array();

    public function index() {
        $this->load->language('project/project');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('project/project');

        $this->getList();
    }

    public function add() {
        $this->load->language('project/project');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('project/project');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_project_project->addProject($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('project/project', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('project/project');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('project/project');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_project_project->editProject($this->request->get['project_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('project/project', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('project/project');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('project/project');

        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $project_id) {
                $this->model_project_project->deleteProject($project_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('project/project', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getList();
    }

    protected function getList() {
        $this->document->addStyle("view/dist/plugins/DataTables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css");
        $this->document->addStyle("view/dist/plugins/DataTables/Buttons-1.5.6/css/buttons.bootstrap4.min.css");
        $this->document->addStyle("view/dist/plugins/DataTables/FixedHeader-3.1.4/css/fixedHeader.bootstrap4.min.css");
        $this->document->addStyle("view/dist/plugins/DataTables/Responsive-2.2.2/css/responsive.bootstrap4.min.css");
        $this->document->addScript("view/dist/plugins/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/Buttons-1.5.6/js/buttons.bootstrap4.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/JSZip-2.5.0/jszip.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/pdfmake-0.1.36/pdfmake.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/pdfmake-0.1.36/vfs_fonts.js");
        $this->document->addScript("view/dist/plugins/DataTables/Buttons-1.5.6/js/buttons.html5.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/Buttons-1.5.6/js/buttons.print.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/Buttons-1.5.6/js/buttons.colVis.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/FixedHeader-3.1.4/js/dataTables.fixedHeader.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/FixedHeader-3.1.4/js/fixedHeader.bootstrap4.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/Responsive-2.2.2/js/dataTables.responsive.min.js");
        $this->document->addScript("view/dist/plugins/DataTables/Responsive-2.2.2/js/responsive.bootstrap4.min.js");

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('project/project', 'member_token=' . $this->session->data['member_token'])
        );

        $data['add'] = $this->url->link('project/project/add', 'member_token=' . $this->session->data['member_token']);
        $data['delete'] = $this->url->link('project/project/delete', 'member_token=' . $this->session->data['member_token']);

        $data['projects'] = array();

        $results = $this->model_project_project->getProjects();
        foreach ($results as $result) {
            $data['projects'][] = array(
                'project_id' => $result['project_id'],
                'name' => $result['name'],
                'sort_order' => $result['sort_order'],
                'edit' => $this->url->link('project/project/edit', 'member_token=' . $this->session->data['member_token'] . '&project_id=' . $result['project_id']),
                'delete' => $this->url->link('project/project/delete', 'member_token=' . $this->session->data['member_token'] . '&project_id=' . $result['project_id'])
            );
        }

        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('project/project_list', $data));
    }

    protected function getForm() {
        $this->document->addStyle("view/dist/plugins/iCheck/all.css");
        $this->document->addScript("view/dist/plugins/ckeditor/ckeditor.js");
        $this->document->addScript("view/dist/plugins/ckeditor/adapters/jquery.js");
        $this->document->addScript("view/dist/plugins/iCheck/icheck.min.js");

        $data['text_form'] = !isset($this->request->get['project_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if (isset($this->error['project_name'])) {
            $data['name_err'] = $this->error['project_name'];
        } else {
            $data['name_err'] = array();
        }

        if (isset($this->error['description'])) {
            $data['description_err'] = $this->error['description'];
        } else {
            $data['description_err'] = array();
        }
        
        if (isset($this->error['keyword'])) {
            $data['keyword_err'] = $this->error['keyword'];
        } else {
            $data['keyword_err'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('project/project', 'member_token=' . $this->session->data['member_token'])
        );

        if (!isset($this->request->get['project_id'])) {
            $data['action'] = $this->url->link('project/project/add', 'member_token=' . $this->session->data['member_token']);
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_add'),
                'href' => $this->url->link('project/project/add', 'member_token=' . $this->session->data['member_token'])
            );
        } else {
            $data['action'] = $this->url->link('project/project/edit', 'member_token=' . $this->session->data['member_token'] . '&project_id=' . $this->request->get['project_id']);
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('project/project/edit', 'member_token=' . $this->session->data['member_token'] . '&project_id=' . $this->request->get['project_id'])
            );
        }

        $data['cancel'] = $this->url->link('project/project', 'member_token=' . $this->session->data['member_token']);

        if (isset($this->request->get['project_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $project_info = $this->model_project_project->getProject($this->request->get['project_id']);
        }
        
        $data['member_token'] = $this->session->data['member_token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();
       
        if (isset($this->request->post['date'])) {
            $data['date'] = $this->request->post['date'];
        } elseif (!empty($project_info)) {
            $data['date'] = $project_info['date'];
        } else {
            $data['date'] = '';
        }
        
        if (isset($this->request->post['project_name'])) {
            $data['project_name'] = $this->request->post['project_name'];
        } elseif (!empty($project_info)) {
            $data['project_name'] = $project_info['name'];
        } else {
            $data['project_name'] = '';
        }
                
        if (isset($this->request->post['project_url'])) {
            $data['project_url'] = $this->request->post['project_url'];
        } elseif (!empty($project_info)) {
            $data['project_url'] = $project_info['project_url'];
        } else {
            $data['project_url'] = '';
        }
        if (isset($this->request->post['description'])) {
            $data['description'] = $this->request->post['description'];
        } elseif (!empty($project_info)) {
            $data['description'] = $project_info['description'];
        } else {
            $data['description'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($project_info)) {
            $data['image'] = $project_info['project_image'];
        } else {
            $data['image'] = '';
        }
       
        $this->load->model('tool/image');

        $data['placeholder'] = $this->model_tool_image->resize('no-image.png', 100, 100);

        if (is_file(DIR_IMAGE . html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))) {
            $data['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'), 100, 100);
        } else {
            $data['thumb'] = $data['placeholder'];
        }
        
        // Images
        if (isset($this->request->post['project_image'])) {
            $project_images = $this->request->post['project_image'];
        } elseif (!empty($project_info)) {
            $project_images = $this->model_project_project->getProjectImages($this->request->get['project_id']);
        } else {
            $project_images = array();
        }

        $data['project_images'] = array();

        foreach ($project_images as $project_image) {
            if (is_file(DIR_IMAGE . html_entity_decode($project_image['image'], ENT_QUOTES, 'UTF-8'))) {
                $image = $project_image['image'];
                $thumb = $project_image['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }

            $data['project_images'][] = array(
                'image' => $image,
                'thumb' => $this->model_tool_image->resize(html_entity_decode($thumb, ENT_QUOTES, 'UTF-8'), 100, 100),
                'sort_order' => $project_image['sort_order']
            );
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($project_info)) {
            $data['sort_order'] = $project_info['e_order'];
        } else {
            $data['sort_order'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($project_info)) {
            $data['status'] = $project_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['project_seo_url'])) {
            $data['project_seo_url'] = $this->request->post['project_seo_url'];
        } elseif (!empty($project_info)) {
            $data['project_seo_url'] = $this->model_project_project->getProjectSeoUrls($this->request->get['project_id']);
        } else {
            $data['project_seo_url'] = array();
        }

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('project/project_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'project/project')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('delete', 'project/project')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function icons() {
        $json = array();

        if (!$json) {
            $pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
            $subject = file_get_contents('view/dist/plugins/font-awesome/css/font-awesome.css');

            preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                $json[] = array(
                    'icon' => $match[1],
                    'css' => implode(array(str_replace('\\', '&#x', $match[2]), ';'))
                );
            }

            // $json = var_export($json, TRUE);
            // $json = stripslashes($json);
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value;
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('project/project');

            $results = $this->model_project_project->getProjects();
          
            foreach ($results as $result) {
                $json[] = array(
                    'project_id' => $result['project_id'],
                    'title' => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value;
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}

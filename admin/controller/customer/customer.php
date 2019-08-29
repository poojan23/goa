<?php

class ControllerCustomerCustomer extends PT_Controller {

    private $error = array();

    public function index() {
        $this->load->language('customer/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('customer/customer');

        $this->getList();
    }

    public function add() {
        $this->load->language('customer/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('customer/customer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
//            print_r($this->request->post);exit;
            $this->model_customer_customer->addCustomer($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('customer/customer', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('customer/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('customer/customer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_customer_customer->editCustomer($this->request->get['customer_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('customer/customer', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('customer/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('customer/customer');

        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $customer_id) {
                $this->model_customer_customer->deleteCustomer($customer_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('customer/customer', 'member_token=' . $this->session->data['member_token']));
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
            'href' => $this->url->link('customer/customer', 'member_token=' . $this->session->data['member_token'])
        );

        $data['add'] = $this->url->link('customer/customer/add', 'member_token=' . $this->session->data['member_token']);
        $data['delete'] = $this->url->link('customer/customer/delete', 'member_token=' . $this->session->data['member_token']);

        $data['customers'] = array();

        $results = $this->model_customer_customer->getCustomers();
        foreach ($results as $result) {
            $data['customers'][] = array(
                'customer_id' => $result['customer_id'],
                'name' => $result['name'],
                'sort_order' => $result['sort_order'],
                'edit' => $this->url->link('customer/customer/edit', 'member_token=' . $this->session->data['member_token'] . '&customer_id=' . $result['customer_id']),
                'delete' => $this->url->link('customer/customer/delete', 'member_token=' . $this->session->data['member_token'] . '&customer_id=' . $result['customer_id'])
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

        $this->response->setOutput($this->load->view('customer/customer_list', $data));
    }

    protected function getForm() {
        $this->document->addStyle("view/dist/plugins/iCheck/all.css");
        $this->document->addScript("view/dist/plugins/ckeditor/ckeditor.js");
        $this->document->addScript("view/dist/plugins/ckeditor/adapters/jquery.js");
        $this->document->addScript("view/dist/plugins/iCheck/icheck.min.js");

        $data['text_form'] = !isset($this->request->get['customer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if (isset($this->error['customer_name'])) {
            $data['name_err'] = $this->error['customer_name'];
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
            'href' => $this->url->link('customer/customer', 'member_token=' . $this->session->data['member_token'])
        );

        if (!isset($this->request->get['customer_id'])) {
            $data['action'] = $this->url->link('customer/customer/add', 'member_token=' . $this->session->data['member_token']);
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_add'),
                'href' => $this->url->link('customer/customer/add', 'member_token=' . $this->session->data['member_token'])
            );
        } else {
            $data['action'] = $this->url->link('customer/customer/edit', 'member_token=' . $this->session->data['member_token'] . '&customer_id=' . $this->request->get['customer_id']);
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('customer/customer/edit', 'member_token=' . $this->session->data['member_token'] . '&customer_id=' . $this->request->get['customer_id'])
            );
        }

        $data['cancel'] = $this->url->link('customer/customer', 'member_token=' . $this->session->data['member_token']);

        if (isset($this->request->get['customer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $customer_info = $this->model_customer_customer->getCustomer($this->request->get['customer_id']);
        }
//        print_r($customer_info);exit;
        $data['member_token'] = $this->session->data['member_token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();
       
        if (isset($this->request->post['date'])) {
            $data['date'] = $this->request->post['date'];
        } elseif (!empty($customer_info)) {
            $data['date'] = $customer_info['dob'];
        } else {
            $data['date'] = '';
        }
        
        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($customer_info)) {
            $data['name'] = $customer_info['name'];
        } else {
            $data['name'] = '';
        }
        
        if (isset($this->request->post['relation'])) {
            $data['relation'] = $this->request->post['relation'];
        } elseif (!empty($customer_info)) {
            $data['relation'] = $customer_info['relation'];
        } else {
            $data['relation'] = '';
        }
        
        if (isset($this->request->post['profession'])) {
            $data['profession'] = $this->request->post['profession'];
        } elseif (!empty($customer_info)) {
            $data['profession'] = $customer_info['profession'];
        } else {
            $data['profession'] = '';
        }
        
        if (isset($this->request->post['fee'])) {
            $data['fee'] = $this->request->post['fee'];
        } elseif (!empty($customer_info)) {
            $data['fee'] = $customer_info['fee'];
        } else {
            $data['fee'] = '';
        }
        
        if (isset($this->request->post['phone'])) {
            $data['phone'] = $this->request->post['phone'];
        } elseif (!empty($customer_info)) {
            $data['phone'] = $customer_info['phone'];
        } else {
            $data['phone'] = '';
        }
        
        
        if (isset($this->request->post['mobile'])) {
            $data['mobile'] = $this->request->post['mobile'];
        } elseif (!empty($customer_info)) {
            $data['mobile'] = $customer_info['mobile'];
        } else {
            $data['mobile'] = '';
        }
        
        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($customer_info)) {
            $data['email'] = $customer_info['email'];
        } else {
            $data['email'] = '';
        }
        
        if (isset($this->request->post['pin'])) {
            $data['pin'] = $this->request->post['pin'];
        } elseif (!empty($customer_info)) {
            $data['pin'] = $customer_info['pincode'];
        } else {
            $data['pin'] = '';
        }
        
        if (isset($this->request->post['address'])) {
            $data['address'] = $this->request->post['address'];
        } elseif (!empty($customer_info)) {
            $data['address'] = $customer_info['address'];
        } else {
            $data['address'] = '';
        }

        if (isset($this->request->post['place'])) {
            $data['place'] = $this->request->post['place'];
        } elseif (!empty($customer_info)) {
            $data['place'] = $customer_info['place'];
        } else {
            $data['place'] = '';
        }
        
        if (isset($this->request->post['customer_group_id'])) {
            $data['customer_group_id'] = $this->request->post['customer_group_id'];
        } elseif (!empty($customer_info)) {
            $data['customer_group_id'] = $customer_info['customer_group_id'];
        } else {
            $data['customer_group_id'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($customer_info)) {
            $data['image'] = $customer_info['image'];
        } else {
            $data['image'] = '';
        }
        
        if (isset($this->request->post['customer_member_id'])) {
            $data['customer_member_id'] = $this->request->post['customer_member_id'];
        } elseif (!empty($customer_info)) {
            $data['customer_member_id'] = $customer_info['customer_member_id'];
        } else {
            $data['customer_member_id'] = '';
        }
        
        if (isset($this->request->post['customer_name'])) {
            $data['customer_name'] = $this->request->post['customer_name'];
        } elseif (!empty($customer_info)) {
            $data['customer_name'] = $customer_info['customer_name'];
        } else {
            $data['customer_name'] = '';
        }
        
        if (isset($this->request->post['customer_relation'])) {
            $data['customer_relation'] = $this->request->post['customer_relation'];
        } elseif (!empty($customer_info)) {
            $data['customer_relation'] = $customer_info['customer_relation'];
        } else {
            $data['customer_relation'] = '';
        }

        if (isset($this->request->post['customer_dob'])) {
            $data['customer_dob'] = $this->request->post['customer_dob'];
        } elseif (!empty($customer_info)) {
            $data['customer_dob'] = $customer_info['customer_dob'];
        } else {
            $data['customer_dob'] = '';
        }
        
        if (isset($this->request->post['customer_profession'])) {
            $data['customer_profession'] = $this->request->post['customer_profession'];
        } elseif (!empty($customer_info)) {
            $data['customer_profession'] = $customer_info['customer_profession'];
        } else {
            $data['customer_profession'] = '';
        }

        if (isset($this->request->post['customer_fee'])) {
            $data['customer_fee'] = $this->request->post['customer_fee'];
        } elseif (!empty($customer_info)) {
            $data['customer_fee'] = $customer_info['customer_fee'];
        } else {
            $data['customer_fee'] = '';
        }
        
        if (isset($this->request->post['customer_phone'])) {
            $data['customer_phone'] = $this->request->post['customer_phone'];
        } elseif (!empty($customer_info)) {
            $data['customer_phone'] = $customer_info['customer_phone'];
        } else {
            $data['customer_phone'] = '';
        }

        if (isset($this->request->post['customer_mobile'])) {
            $data['customer_mobile'] = $this->request->post['customer_mobile'];
        } elseif (!empty($customer_info)) {
            $data['customer_mobile'] = $customer_info['customer_mobile'];
        } else {
            $data['customer_mobile'] = '';
        }
        
        if (isset($this->request->post['customer_email'])) {
            $data['customer_email'] = $this->request->post['customer_email'];
        } elseif (!empty($customer_info)) {
            $data['customer_email'] = $customer_info['customer_email'];
        } else {
            $data['customer_email'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($customer_info)) {
            $data['image'] = $customer_info['image'];
        } else {
            $data['image'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($customer_info)) {
            $data['sort_order'] = $customer_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }
       
        $this->load->model('customer/customer_group');

        $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
        
        $this->load->model('tool/image');

        $data['placeholder'] = $this->model_tool_image->resize('no-image.png', 100, 100);

        if (is_file(DIR_IMAGE . html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))) {
            $data['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'), 100, 100);
        } else {
            $data['thumb'] = $data['placeholder'];
        }
        
        // Images
        if (isset($this->request->post['customer_image'])) {
            $customer_images = $this->request->post['customer_image'];
        } elseif (!empty($customer_info)) {
            $customer_images = $this->model_customer_customer->getCustomerFamily($this->request->get['customer_id']);
        } else {
            $customer_images = array();
        }

        $data['customer_images'] = array();

        foreach ($customer_images as $customer_image) {
            if (is_file(DIR_IMAGE . html_entity_decode($customer_image['image'], ENT_QUOTES, 'UTF-8'))) {
                $image = $customer_image['image'];
                $thumb = $customer_image['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }

            $data['customer_images'][] = array(
                'image' => $image,
                'thumb' => $this->model_tool_image->resize(html_entity_decode($thumb, ENT_QUOTES, 'UTF-8'), 100, 100),
                'customer_member_id' => $customer_image['customer_member_id'],
                'customer_id' => $customer_image['customer_id'],
                'customer_group_id' => $customer_image['customer_group_id'],
                'customer_name' => $customer_image['customer_name'],
                'customer_relation' => $customer_image['customer_relation'],
                'customer_dob' => $customer_image['customer_dob'],
                'customer_fee' => $customer_image['customer_fee'],
                'customer_phone' => $customer_image['customer_phone'],
                'customer_mobile' => $customer_image['customer_mobile'],
                'customer_email' => $customer_image['customer_email'],
                'customer_profession' => $customer_image['customer_profession'],
                'sort_order' => $customer_image['sort_order']
            );
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($customer_info)) {
            $data['sort_order'] = $customer_info['sort_order'];
        } else {
            $data['sort_order'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($customer_info)) {
            $data['status'] = $customer_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['customer_seo_url'])) {
            $data['customer_seo_url'] = $this->request->post['customer_seo_url'];
        } elseif (!empty($customer_info)) {
            $data['customer_seo_url'] = $this->model_customer_customer->getCustomerSeoUrls($this->request->get['customer_id']);
        } else {
            $data['customer_seo_url'] = array();
        }

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('customer/customer_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'customer/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('delete', 'customer/customer')) {
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
            $this->load->model('customer/customer');

            $results = $this->model_customer_customer->getCustomers();
          
            foreach ($results as $result) {
                $json[] = array(
                    'customer_id' => $result['customer_id'],
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

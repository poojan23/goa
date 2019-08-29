<?php

class ControllerCommonNav extends PT_Controller
{
    public function index()
    {
        if (isset($this->request->get['member_token']) && isset($this->session->data['member_token']) && ((string) $this->request->get['member_token'] == $this->session->data['member_token'])) {
            $this->load->language('common/nav');

            # Create a 3 level menu array
            # Level 2 can not have children

            # Menu
            $data['menus'][] = array(
                'id'        => 'menu-dashboard',
                'icon'      => 'fa-tachometer',
                'name'      => $this->language->get('text_dashboard'),
                'href'      => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token']),
                'children'  => array()
            );

            # Catalog
            $catalog = array();

            if ($this->user->hasPermission('access', 'catalog/information')) {
                $catalog[] = array(
                    'name'      => $this->language->get('text_information'),
                    'href'      => $this->url->link('catalog/information', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($catalog) {
                $data['menus'][] = array(
                    'id'        => 'menu-catalog',
                    'icon'      => 'fa-tags',
                    'name'      => $this->language->get('text_catalog'),
                    'href'      => '',
                    'children'  => $catalog
                );
            }

            # Design
            $design = array();

            if ($this->user->hasPermission('access', 'design/banner')) {
                $design[] = array(
                    'name'      => $this->language->get('text_banner'),
                    'href'      => $this->url->link('design/banner', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($this->user->hasPermission('access', 'design/translation')) {
                $design[] = array(
                    'name'      => $this->language->get('text_language_editor'),
                    'href'      => $this->url->link('design/translation', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }
            
             # Event
            $event = array();
            
            if ($this->user->hasPermission('access', 'event/event')) {
                $event[] = array(
                    'name'      => $this->language->get('text_event'),
                    'href'      => $this->url->link('event/event', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }
   
            if ($event) {
                $data['menus'][] = array(
                    'id'        => 'menu-design',
                    'icon'      => 'fa-calendar',
                    'name'      => $this->language->get('text_event'),
                    'href'      => '',
                    'children'  => $event
                );
            }
            
             # Member
            $customer = array();
            
            if ($this->user->hasPermission('access', 'customer/customer')) {
                $customer[] = array(
                    'name'      => $this->language->get('text_customer'),
                    'href'      => $this->url->link('customer/customer', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }
            
            if ($this->user->hasPermission('access', 'customer/customer_group')) {
                $customer[] = array(
                    'name'      => $this->language->get('text_customer_group'),
                    'href'      => $this->url->link('customer/customer_group', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }
   
            if ($customer) {
                $data['menus'][] = array(
                    'id'        => 'menu-design',
                    'icon'      => 'fa-user',
                    'name'      => $this->language->get('text_customer'),
                    'href'      => '',
                    'children'  => $customer
                );
            }
            
            # Project
            $project = array();
            
            if ($this->user->hasPermission('access', 'project/project')) {
                $project[] = array(
                    'name'      => $this->language->get('text_project'),
                    'href'      => $this->url->link('project/project', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }
   
            if ($project) {
                $data['menus'][] = array(
                    'id'        => 'menu-design',
                    'icon'      => 'fa-tasks',
                    'name'      => $this->language->get('text_project'),
                    'href'      => '',
                    'children'  => $project
                );
            }

            # SEO
            $seo = array();

            if ($this->user->hasPermission('access', 'design/seo_regex')) {
                $seo[] = array(
                    'name'      => $this->language->get('text_seo_regex'),
                    'href'      => $this->url->link('design/seo_regex', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($this->user->hasPermission('access', 'design/seo_url')) {
                $seo[] = array(
                    'name'      => $this->language->get('text_seo_url'),
                    'href'      => $this->url->link('design/seo_url', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($seo) {
                $design[] = array(
                    'name'      => $this->language->get('text_seo'),
                    'href'      => '',
                    'children'  => $seo
                );
            }

            if ($design) {
                $data['menus'][] = array(
                    'id'        => 'menu-design',
                    'icon'      => 'fa-flag',
                    'name'      => $this->language->get('text_design'),
                    'href'      => '',
                    'children'  => $design
                );
            }

            # Enquiry
//            if ($this->user->hasPermission('access', 'catalog/enquiry')) {
//                $data['menus'][] = array(
//                    'id'        => 'menu-enquiry',
//                    'icon'      => 'fa-envelope',
//                    'name'      => $this->language->get('text_enquiry'),
//                    'href'      => $this->url->link('catalog/enquiry', 'member_token=' . $this->session->data['member_token']),
//                    'children'  => array()
//                );
//            }
//
//            # Testimonial
//            if ($this->user->hasPermission('access', 'catalog/testimonial')) {
//                $data['menus'][] = array(
//                    'id'        => 'menu-testimonial',
//                    'icon'      => 'fa-pencil',
//                    'name'      => $this->language->get('text_testimonial'),
//                    'href'      => $this->url->link('catalog/testimonial', 'member_token=' . $this->session->data['member_token']),
//                    'children'  => array()
//                );
//            }

            # Settings
            # System
            $system = array();

            # Users
            $user = array();

            if ($this->user->hasPermission('access', 'user/user')) {
                $user[] = array(
                    'name'      => $this->language->get('text_user'),
                    'href'      => $this->url->link('user/user', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($this->user->hasPermission('access', 'user/user_permission')) {
                $user[] = array(
                    'name'      => $this->language->get('text_user_group'),
                    'href'      => $this->url->link('user/user_permission', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($user) {
                $system[] = array(
                    'name'      => $this->language->get('text_user'),
                    'href'      => '',
                    'children'  => $user
                );
            }

            # Localisation
            $localisation = array();

            if ($this->user->hasPermission('access', 'localisation/language')) {
                $localisation[] = array(
                    'name'      => $this->language->get('text_language'),
                    'href'      => $this->url->link('localisation/language', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($this->user->hasPermission('access', 'localisation/country')) {
                $localisation[] = array(
                    'name'      => $this->language->get('text_country'),
                    'href'      => $this->url->link('localisation/country', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($this->user->hasPermission('access', 'localisation/zone')) {
                $localisation[] = array(
                    'name'      => $this->language->get('text_zone'),
                    'href'      => $this->url->link('localisation/zone', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($localisation) {
                $system[] = array(
                    'name'      => $this->language->get('text_localisation'),
                    'href'      => '',
                    'children'  => $localisation
                );
            }

            # Tools
            $maintenance = array();

            if ($this->user->hasPermission('access', 'tool/upgrade')) {
                $maintenance[] = array(
                    'name'      => $this->language->get('text_upgrade'),
                    'href'      => $this->url->link('tool/upgrade', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($this->user->hasPermission('access', 'tool/backup')) {
                $maintenance[] = array(
                    'name'      => $this->language->get('text_backup'),
                    'href'      => $this->url->link('tool/backup', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($this->user->hasPermission('access', 'tool/upload')) {
                $maintenance[] = array(
                    'name'      => $this->language->get('text_upload'),
                    'href'      => $this->url->link('tool/upload', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($this->user->hasPermission('access', 'tool/log')) {
                $maintenance[] = array(
                    'name'      => $this->language->get('text_log'),
                    'href'      => $this->url->link('tool/log', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($maintenance) {
                $system[] = array(
                    'name'      => $this->language->get('text_maintenance'),
                    'href'      => '',
                    'children'  => $maintenance
                );
            }

            if ($system) {
                $data['menus'][] = array(
                    'id'        => 'menu-system',
                    'icon'      => 'fa-cog',
                    'name'      => $this->language->get('text_system'),
                    'href'      => '',
                    'children'  => $system
                );
            }

            # Reports
            $report = array();

            if ($this->user->hasPermission('access', 'report/report')) {
                $report[] = array(
                    'name'      => $this->language->get('text_reports'),
                    'href'      => $this->url->link('report/report', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($this->user->hasPermission('access', 'report/online')) {
                $report[] = array(
                    'name'      => $this->language->get('text_online'),
                    'href'      => $this->url->link('report/online', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($this->user->hasPermission('access', 'report/statistics')) {
                $report[] = array(
                    'name'      => $this->language->get('text_statistics'),
                    'href'      => $this->url->link('report/statistics', 'member_token=' . $this->session->data['member_token']),
                    'children'  => array()
                );
            }

            if ($report) {
                $data['menus'][] = array(
                    'id'        => 'menu-reports',
                    'icon'      => 'fa-chart-bar',
                    'name'      => $this->language->get('text_reports'),
                    'href'      => '',
                    'children'  => $report
                );
            }

            $data['menus'][] = array(
                'id'        => 'menu-logout',
                'icon'      => 'fa-sign-out',
                'name'      => $this->language->get('text_logout'),
                'href'      => $this->url->link('user/logout', 'member_token=' . $this->session->data['member_token']),
                'children'  => array()
            );

            $data['home'] = $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token']);

            $data['service'] = $this->url->link('catalog/service', 'member_token=' . $this->session->data['member_token']);
            $data['information'] = $this->url->link('catalog/information', 'member_token=' . $this->session->data['member_token']);
            $data['user'] = $this->url->link('user/user', 'member_token=' . $this->session->data['member_token']);
            $data['setting'] = $this->url->link('setting/store', 'member_token=' . $this->session->data['member_token']);

            return $this->load->view('common/nav', $data);
        }
    }
}

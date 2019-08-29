<?php

class ModelCustomerCustomerGroup extends PT_Model
{
    public function addCustomerGroup($data)
    {
        $query = $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET  name = '" . $this->db->escape((string)$data['group_name']) . "', description = '" . $this->db->escape((string)$data['description']) . "',approval = '0',sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW(), date_added = NOW()");
        $customer_group_id = $this->db->lastInsertId();
        # SEO URL
        if (isset($data['customer_seo_url'])) {
           
            $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET  query = 'customer_group_id=" . (int)$customer_group_id . "', keyword = '" . $this->db->escape((string)$data['customer_seo_url']) . "', push = '" . $this->db->escape('url=customer/customer_group&customer_group_id=' . (int)$customer_group_id) . "'");
              
        }
        return $query;
    }

    public function editCustomerGroup($customer_group_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "customer_group SET name = '" . $this->db->escape((string)$data['group_name']) . "', description = '" . $this->db->escape((string)$data['description']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE customer_group_id = '" . (int)$customer_group_id . "'");
    
        # SEO URL
        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'customer_group_id=" . (int)$customer_group_id . "'");
        if (isset($data['customer_seo_url'])) {
           
            $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET  query = 'customer_group_id=" . (int)$customer_group_id . "', keyword = '" . $this->db->escape((string)$data['customer_seo_url']) . "', push = '" . $this->db->escape('url=customer/customer_group&customer_group_id=' . (int)$customer_group_id) . "'");
              
        }
    }

    public function deleteCustomerGroup($customer_group_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");

        $this->cache->delete('customer_group');
    }

    public function getCustomerGroup($customer_group_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");

        return $query->row;
    }
    public function getCustomerGroups()
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group");

        return $query->rows;
    }
        public function getCustomerGroupSeoUrls($customer_group_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'customer_group_id=" . (int)$customer_group_id . "'");

        return $query->row;
    }
}

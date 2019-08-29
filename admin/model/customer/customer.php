<?php

class ModelCustomerCustomer extends PT_Model {

    public function addCustomer($data) {
      
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET  dob = '" . $this->db->escape((string) $data['date']) . "',name = '" . $this->db->escape((string) $data['name']) . "',relation = '" . $this->db->escape((string) $data['relation']) . "',"
                . "profession = '" . $this->db->escape((string) $data['profession']) . "',fee = '" . $this->db->escape((string) $data['fee']) . "',"
                . "phone = '" . $this->db->escape((string) $data['phone']) . "',mobile = '" . $this->db->escape((string) $data['mobile']) . "',"
                . "email = '" . $this->db->escape((string) $data['email']) . "',place = '" . $this->db->escape((string) $data['place']) . "',"
                . "pincode = '" . $this->db->escape((string) $data['pin']) . "',address = '" . $this->db->escape((string) $data['address']) . "',customer_group_id = '" . (int) $data['customer_group_id'] . "',sort_order = '" . (int) $data['sort_order'] . "', status = '" . (isset($data['status']) ? (int) $data['status'] : 0) . "', date_modified = NOW(), date_added = NOW()");

        $customer_id = $this->db->lastInsertId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET image = '" . $this->db->escape((string) $data['image']) . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        if (isset($data['customer_image'])) {
      
            foreach ($data['customer_image'] as $customer_image) {
//                echo "INSERT INTO " . DB_PREFIX . "customer_family SET customer_id = '" . (int) $customer_id . "', customer_name = '" . $this->db->escape((string) $customer_image['customer_name']) . "',customer_group_id = '" . (int) $data['customer_group_id'] . "',image = '" . $this->db->escape($customer_image['image']) . "', customer_relation = '" . $this->db->escape($customer_image['customer_relation']) . "', customer_dob = '" . $this->db->escape($customer_image['customer_dob']) . "', customer_profession = '" . $this->db->escape($customer_image['customer_profession']) . "', "
//                        . " customer_fee = '" . $this->db->escape($customer_image['customer_fee']) . "', customer_phone = '" . $this->db->escape($customer_image['customer_phone']) . "', customer_mobile = '" . $this->db->escape($customer_image['customer_mobile']) . "', customer_email = '" . $this->db->escape($customer_image['customer_email']) . "', sort_order = '" . (int) $customer_image['sort_order'] . "'";exit;
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_family SET customer_id = '" . (int) $customer_id . "', customer_name = '" . $this->db->escape((string) $customer_image['customer_name']) . "',customer_group_id = '" . (int) $data['customer_group_id'] . "',image = '" . $this->db->escape($customer_image['image']) . "', customer_relation = '" . $this->db->escape($customer_image['customer_relation']) . "', customer_dob = '" . $this->db->escape($customer_image['customer_dob']) . "', customer_profession = '" . $this->db->escape($customer_image['customer_profession']) . "', "
                        . " customer_fee = '" . $this->db->escape($customer_image['customer_fee']) . "', customer_phone = '" . $this->db->escape($customer_image['customer_phone']) . "', customer_mobile = '" . $this->db->escape($customer_image['customer_mobile']) . "', customer_email = '" . $this->db->escape($customer_image['customer_email']) . "', sort_order = '" . (int) $customer_image['sort_order'] . "'");
            }
        }

        # SEO URL
        if (isset($data['customer_seo_url'])) {
            foreach ($data['customer_seo_url'] as $language_id => $keyword) {
                if ($keyword) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET language_id = '" . (int) $language_id . "', query = 'customer_id=" . (int) $customer_id . "', keyword = '" . $this->db->escape($keyword) . "', push = '" . $this->db->escape('url=customer/customer&customer_id=' . (int) $customer_id) . "'");
                }
            }
        }

        $this->cache->delete('customer');

        return $customer_id;
    }

    public function editCustomer($customer_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET  dob = '" . $this->db->escape((string) $data['date']) . "',name = '" . $this->db->escape((string) $data['name']) . "',relation = '" . $this->db->escape((string) $data['relation']) . "',"
                . "profession = '" . $this->db->escape((string) $data['profession']) . "',fee = '" . $this->db->escape((string) $data['fee']) . "',"
                . "phone = '" . $this->db->escape((string) $data['phone']) . "',mobile = '" . $this->db->escape((string) $data['mobile']) . "',"
                . "email = '" . $this->db->escape((string) $data['email']) . "',place = '" . $this->db->escape((string) $data['place']) . "',"
                . "pincode = '" . $this->db->escape((string) $data['pin']) . "',address = '" . $this->db->escape((string) $data['address']) . "',customer_group_id = '" . (int) $data['customer_group_id'] . "',sort_order = '" . (int) $data['sort_order'] . "', status = '" . (isset($data['status']) ? (int) $data['status'] : 0) . "', date_modified = NOW(), date_added = NOW()");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET image = '" . $this->db->escape((string) $data['image']) . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_family WHERE customer_id = '" . (int) $customer_id . "'");

        if (isset($data['customer_image'])) {
      
            foreach ($data['customer_image'] as $customer_image) {
//                echo "INSERT INTO " . DB_PREFIX . "customer_family SET customer_id = '" . (int) $customer_id . "', customer_name = '" . $this->db->escape((string) $customer_image['customer_name']) . "',customer_group_id = '" . (int) $data['customer_group_id'] . "',image = '" . $this->db->escape($customer_image['image']) . "', customer_relation = '" . $this->db->escape($customer_image['customer_relation']) . "', customer_dob = '" . $this->db->escape($customer_image['customer_dob']) . "', customer_profession = '" . $this->db->escape($customer_image['customer_profession']) . "', "
//                        . " customer_fee = '" . $this->db->escape($customer_image['customer_fee']) . "', customer_phone = '" . $this->db->escape($customer_image['customer_phone']) . "', customer_mobile = '" . $this->db->escape($customer_image['customer_mobile']) . "', customer_email = '" . $this->db->escape($customer_image['customer_email']) . "', sort_order = '" . (int) $customer_image['sort_order'] . "'";exit;
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_family SET customer_id = '" . (int) $customer_id . "', customer_name = '" . $this->db->escape((string) $customer_image['customer_name']) . "',customer_group_id = '" . (int) $data['customer_group_id'] . "',image = '" . $this->db->escape($customer_image['image']) . "', customer_relation = '" . $this->db->escape($customer_image['customer_relation']) . "', customer_dob = '" . $this->db->escape($customer_image['customer_dob']) . "', customer_profession = '" . $this->db->escape($customer_image['customer_profession']) . "', "
                        . " customer_fee = '" . $this->db->escape($customer_image['customer_fee']) . "', customer_phone = '" . $this->db->escape($customer_image['customer_phone']) . "', customer_mobile = '" . $this->db->escape($customer_image['customer_mobile']) . "', customer_email = '" . $this->db->escape($customer_image['customer_email']) . "', sort_order = '" . (int) $customer_image['sort_order'] . "'");
            }
        }
        #SEO URL
        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'customer_id=" . (int) $customer_id . "'");

        if (isset($data['customer_seo_url'])) {
            foreach ($data['customer_seo_url'] as $language_id => $keyword) {
                if ($keyword) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET language_id = '" . (int) $language_id . "', query = 'customer_id=" . (int) $customer_id . "', keyword = '" . $this->db->escape($keyword) . "', push = '" . $this->db->escape('url=customer/customer&customer_id=' . (int) $customer_id) . "'");
                }
            }
        }

        $this->cache->delete('customer');
    }

    public function deleteCustomer($customer_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_description WHERE customer_id = '" . (int) $customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'customer_id=" . (int) $customer_id . "'");

        $this->cache->delete('customer');
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query("SELECT c.*,cf.* FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_family cf ON (c.customer_id = cf.customer_id) WHERE c.customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function getCustomers() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer  WHERE status = 1");

        return $query->rows;
    }

    public function getCustomerFamily($customer_id) {
        $customer_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_family WHERE customer_id = '" . (int) $customer_id . "'");

        foreach ($query->rows as $result) {
            $customer_description_data[] = array(
                'customer_member_id' => $result['customer_member_id'],
                'customer_id' => $result['customer_id'],
                'customer_group_id' => $result['customer_group_id'],
                'customer_name' => $result['customer_name'],
                'customer_relation' => $result['customer_relation'],
                'customer_dob' => $result['customer_dob'],
                'customer_fee' => $result['customer_fee'],
                'customer_phone' => $result['customer_phone'],
                'customer_mobile' => $result['customer_mobile'],
                'customer_email' => $result['customer_email'],
                'customer_profession' => $result['customer_profession'],
                'image' => $result['image'],
                'sort_order' => $result['sort_order']
            );
        }

        return $customer_description_data;
    }

    public function getCustomerSeoUrls($customer_id) {
        $customer_seo_url_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'customer_id=" . (int) $customer_id . "'");

        foreach ($query->rows as $result) {
            $customer_seo_url_data[$result['language_id']] = $result['keyword'];
        }

        return $customer_seo_url_data;
    }

    public function getTotalCustomers() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer");

        return $query->row['total'];
    }

    public function getCustomerImages($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_image WHERE customer_id = '" . (int) $customer_id . "' ORDER BY sort_order ASC");

        return $query->rows;
    }

}

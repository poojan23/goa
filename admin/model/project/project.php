<?php

class ModelProjectProject extends PT_Model {

    public function addProject($data) {
      
        $this->db->query("INSERT INTO " . DB_PREFIX . "projects SET  date = '" . $this->db->escape((string) $data['date']) . "',name = '" . $this->db->escape((string) $data['project_name']) . "',project_url = '" . $this->db->escape((string) $data['project_url']) . "',description = '" . $this->db->escape((string) $data['description']) . "',sort_order = '" . (int) $data['sort_order'] . "', status = '" . (isset($data['status']) ? (int) $data['status'] : 0) . "', date_modified = NOW(), date_added = NOW()");

        $project_id = $this->db->lastInsertId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "projects SET image = '" . $this->db->escape((string) $data['image']) . "' WHERE project_id = '" . (int) $project_id . "'");
        }

        if (isset($data['project_image'])) {
            foreach ($data['project_image'] as $project_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "project_image SET project_id = '" . (int) $project_id . "', image = '" . $this->db->escape($project_image['image']) . "', sort_order = '" . (int) $project_image['sort_order'] . "'");
            }
        }

        # SEO URL
        if (isset($data['project_seo_url'])) {
            foreach ($data['project_seo_url'] as $language_id => $keyword) {
                if ($keyword) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET language_id = '" . (int) $language_id . "', query = 'project_id=" . (int) $project_id . "', keyword = '" . $this->db->escape($keyword) . "', push = '" . $this->db->escape('url=project/project&project_id=' . (int) $project_id) . "'");
                }
            }
        }

        $this->cache->delete('project');

        return $project_id;
    }

    public function editProject($project_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "projects SET date = '" . $this->db->escape((string) $data['date']) . "',name = '" . $this->db->escape((string) $data['project_name']) . "',project_url = '" . $this->db->escape((string) $data['project_url']) . "',description = '" . $this->db->escape((string) $data['description']) . "',sort_order = '" . (int) $data['sort_order'] . "', status = '" . (isset($data['status']) ? (int) $data['status'] : 0) . "', date_modified = NOW() WHERE project_id = '" . (int) $project_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "projects SET image = '" . $this->db->escape((string) $data['image']) . "' WHERE project_id = '" . (int) $project_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "project_image WHERE project_id = '" . (int) $project_id . "'");

        if (isset($data['project_image'])) {
            foreach ($data['project_image'] as $project_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "project_image SET project_id = '" . (int) $project_id . "', image = '" . $this->db->escape($project_image['image']) . "', sort_order = '" . (int) $project_image['sort_order'] . "'");
            }
        }
        #SEO URL
        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'project_id=" . (int) $project_id . "'");

        if (isset($data['project_seo_url'])) {
            foreach ($data['project_seo_url'] as $language_id => $keyword) {
                if ($keyword) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET language_id = '" . (int) $language_id . "', query = 'project_id=" . (int) $project_id . "', keyword = '" . $this->db->escape($keyword) . "', push = '" . $this->db->escape('url=project/project&project_id=' . (int) $project_id) . "'");
                }
            }
        }

        $this->cache->delete('project');
    }

    public function deleteProject($project_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "projects WHERE project_id = '" . (int) $project_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "project_description WHERE project_id = '" . (int) $project_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'project_id=" . (int) $project_id . "'");

        $this->cache->delete('project');
    }

    public function getProject($project_id) {
        $query = $this->db->query("SELECT e.date,e.name,e.project_url,e.description,e.sort_order as e_order,e.status,e.image as project_image,ei.* FROM " . DB_PREFIX . "projects e LEFT JOIN " . DB_PREFIX . "project_image ei ON (e.project_id = ei.project_id) WHERE e.project_id = '" . (int) $project_id . "'");

        return $query->row;
    }

    public function getProjects() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "projects  WHERE status = 1");

        return $query->rows;
    }

    public function getProjectDescriptions($project_id) {
        $project_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "project_description WHERE project_id = '" . (int) $project_id . "'");

        foreach ($query->rows as $result) {
            $project_description_data[$result['language_id']] = array(
                'title' => $result['title'],
                'description' => $result['description'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword']
            );
        }

        return $project_description_data;
    }

    public function getProjectSeoUrls($project_id) {
        $project_seo_url_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'project_id=" . (int) $project_id . "'");

        foreach ($query->rows as $result) {
            $project_seo_url_data[$result['language_id']] = $result['keyword'];
        }

        return $project_seo_url_data;
    }

    public function getTotalProjects() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "project");

        return $query->row['total'];
    }

    public function getProjectImages($project_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "project_image WHERE project_id = '" . (int) $project_id . "' ORDER BY sort_order ASC");

        return $query->rows;
    }

}

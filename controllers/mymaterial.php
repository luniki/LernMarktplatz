<?php

require_once 'app/controllers/plugin_controller.php';

class MymaterialController extends PluginController {

    function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);
        PageLayout::setTitle(_("Lernmaterialien"));
    }

    public function overview_action() {
        Navigation::activateItem("/lernmarktplatz/mymaterial");
        $this->materialien = LernmarktplatzMaterial::findMine();
    }

    public function details_action($material_id)
    {
        Navigation::activateItem("/lernmarktplatz/overview");
        $this->material = new LernmarktplatzMaterial($material_id);
    }


    public function edit_action($material_id = null) {
        $this->material = new LernmarktplatzMaterial($material_id);
        Pagelayout::setTitle($this->material->isNew() ? _("Neues Material hochladen") : _("Material bearbeiten"));
        if ($this->material['user_id'] && $this->material['user_id'] !== $GLOBALS['user']->id) {
            throw new AccessDeniedException();
        }
        if (Request::submitted("delete") && Request::isPost()) {
            $this->material->pushDataToIndexServers("delete");
            $this->material->delete();
            PageLayout::postMessage(MessageBox::success(_("Ihr Material wurde gel�scht.")));
            $this->redirect("market/overview");
        } elseif (Request::isPost()) {
            $was_new = $this->material->setData(Request::getArray("data"));
            $this->material['user_id'] = $GLOBALS['user']->id;
            $this->material['host_id'] = null;
            $this->material['license'] = "CC BY 4.0";
            if ($_FILES['file']['tmp_name']) {
                $this->material['content_type'] = $_FILES['file']['type'];
                if (in_array($this->material['content_type'], array("application/x-zip-compressed", "application/zip", "application/x-zip"))) {
                    $tmp_folder = $GLOBALS['TMP_PATH']."/temp_folder_".md5(uniqid());
                    mkdir($tmp_folder);
                    unzip_file($_FILES['file']['tmp_name'], $tmp_folder);
                    $this->material['structure'] = $this->getFolderStructure($tmp_folder);
                    rmdirr($tmp_folder);
                } else {
                    $this->material['structure'] = null;
                }
                $this->material['filename'] = $_FILES['file']['name'];
                move_uploaded_file($_FILES['file']['tmp_name'], $this->material->getFilePath());
            }
            if ($_FILES['image']['tmp_name']) {
                $this->material['front_image_content_type'] = $_FILES['image']['type'];
                move_uploaded_file($_FILES['image']['tmp_name'], $this->material->getFrontImageFilePath());
            }
            if (Request::get("delete_front_image")) {
                $this->material['front_image_content_type'] = null;
            }
            $this->material->store();

            //Topics:
            $topics = Request::getArray("tags");
            foreach ($topics as $key => $topic) {
                if (!trim($topic)) {
                    unset($topics[$key]);
                }
            }
            $this->material->setTopics($topics);

            $this->material->pushDataToIndexServers();

            PageLayout::postMessage(MessageBox::success(_("Lernmaterial erfolgreich gespeichert.")));
            $this->redirect("market/details/".$this->material->getId());
        }
    }

    protected function getFolderStructure($folder) {
        $structure = array();
        foreach (scandir($folder) as $file) {
            if (!in_array($file, array(".", ".."))) {
                $attributes = array(
                    'is_folder' => is_dir($folder."/".$file) ? 1 : 0
                );
                if (is_dir($folder."/".$file)) {
                    $attributes['structure'] = $this->getFolderStructure($folder."/".$file);
                } else {
                    $attributes['size'] = filesize($folder."/".$file);
                }
                $structure[$file] = $attributes;
            }
        }
        return $structure;
    }

    public function add_tag_action()
    {
        if (!Request::isPost()) {
            throw new AccessDeniedException();
        }
        $this->material = new LernmarktplatzMaterial(Request::option("material_id"));
        $this->render_nothing();
    }

}
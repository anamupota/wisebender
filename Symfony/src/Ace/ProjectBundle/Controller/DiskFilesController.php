<?php
// src/Ace/ProjectBundle/Controller/DiskFilesController.php

namespace Ace\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Ace\ProjectBundle\Helper\ProjectErrorsHelper;

class DiskFilesController extends FilesController
{
    protected $dir;
    protected $type;
    protected $sc;
    protected $wiselib_src_dir;


    public function createAction()
    {

        $projects = scandir($this->dir);
        $current_user = $this->sc->getToken()->getUser();
        $name = $current_user->getUsername();
        do
        {
            $id = $name."/".uniqid($more_entropy=true);
        } while(in_array($id, $projects));
        if(!is_dir($this->dir.$this->type))
        {
            mkdir($this->dir.$this->type);
        }
        if(!is_dir($this->dir.$this->type."/".$name))
        {
            mkdir($this->dir.$this->type."/".$name);
        }
        if(!is_dir($this->getDir($id)))
        {
            mkdir($this->getDir($id));
        }
        return ProjectErrorsHelper::success(ProjectErrorsHelper::SUCC_CREATE_PROJ_MSG, array("id" => $id));
    }

    public function deleteAction($id)
    {
        $dir = $this->getDir($id);
        if($this->deleteDirectory($dir))
            return ProjectErrorsHelper::success(ProjectErrorsHelper::SUCC_DELETE_PROJ_MSG);
        else
            return ProjectErrorsHelper::fail(ProjectErrorsHelper::FAIL_DELETE_PROJ_MSG, array("error" => "No projectfiles found with id: ".$id));
    }

    public function listFilesAction($id)
    {
        $list = $this->listFiles($id);
        return json_encode(array("success" => true, "list" => $list));
    }

    public function getFileCode($f)
    {
        $filename = $this->wiselib_src_dir.  DIRECTORY_SEPARATOR  . $f;
        $list = array();
        if (file_exists($filename)){
            $file["filename"] = basename($f);
            $file["code"] = file_get_contents($filename);
            $list[] = $file;
        }else{
            var_dump("file " . $f . " does not exist!");
        }
        return json_encode(array("success" => true, "list" => $list));
    }


    public function createFileAction($id, $filename, $code)
    {
        $canCreateFile = json_decode($this->canCreateFile($id, $filename), true);
        if(!$canCreateFile["success"])
            return json_encode($canCreateFile);
        $dir = $this->getDir($id);
        file_put_contents($dir."/".$filename,$code);

        return ProjectErrorsHelper::success(ProjectErrorsHelper::SUCC_CREATE_FILE_MSG);
    }

    public function getFileAction($id, $filename)
    {
        $response = array("success" => false);
        $list = $this->listFiles($id);
        foreach($list as $file)
        {
            if($file["filename"] == $filename)
                $response=array("success" => true, "code" => $file["code"]);
        }
        return json_encode($response);
    }

    public function setFileAction($id, $filename, $code)
    {
        $dir = $this->getDir($id);
        if($this->fileExists($id,$dir.$filename))
        {
            file_put_contents($dir.$filename,$code);
            return ProjectErrorsHelper::success(ProjectErrorsHelper::SUCC_SAVE_MSG);
        }
        return ProjectErrorsHelper::fail(ProjectErrorsHelper::FAIL_SAVE_MSG, array("id" => $id, "filename" => $filename));
    }

    public function setWiselibFileAction($id, $filename, $code)
    {
        $id = preg_replace("/\//si", "/", $id);
        if(file_exists($id))
        {
            file_put_contents($id,$code);
            return ProjectErrorsHelper::success(ProjectErrorsHelper::SUCC_SAVE_MSG);
        }
        return ProjectErrorsHelper::fail(ProjectErrorsHelper::FAIL_SAVE_MSG, array("id" => "NIL - " .$id , "filename" => $filename));
    }

    public function deleteFileAction($id, $filename)
    {
        $fileExists = json_decode($this->fileExists($id, $filename), true);
        if(!$fileExists["success"])
            return json_encode($fileExists);
        $dir = $this->getDir($id);
        unlink($dir.$filename);
        return ProjectErrorsHelper::success(ProjectErrorsHelper::SUCC_DELETE_FILE_MSG);
    }

    public function renameFileAction($id, $filename, $new_filename)
    {
        $fileExists = json_decode($this->fileExists($id, $filename), true);
        if(!$fileExists["success"])
            return json_encode($fileExists);

        $canCreateFile = json_decode($this->canCreateFile($id, $new_filename), true);
        if($canCreateFile["success"])
        {
            $dir = $this->getDir($id);
            rename($dir.$filename, $dir.$new_filename);
            return ProjectErrorsHelper::success(ProjectErrorsHelper::SUCC_RENAME_FILE_MSG);
        }
        return ProjectErrorsHelper::fail(ProjectErrorsHelper::FAIL_RENAME_FILE_MSG, array("id" => $id, "filename" => $new_filename, "error" => "This file already exists.", "old_filename" => $filename));
    }


    protected function listFiles($id)
    {
        $dir = $this->getDir($id);
        $list = array();
        $objects = scandir($dir);
        foreach ($objects as $object)
        {
            if(!is_dir($dir.$object))
            {
                $file["filename"] = $object;
                $file["code"] = file_get_contents($dir.$object);
                $list[] = $file;
            }
        }
        return $list;
    }

    private function deleteDirectory($dir)
    {
        if (is_dir($dir))
        {
            $objects = scandir($dir);
            foreach ($objects as $object)
            {
                if ($object != "." && $object != "..")
                {
                    if (filetype($dir."/".$object) == "dir") $this->deleteDirectory($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
            return true;
        }
        else return false;
    }


    private function getDir($id)
    {
        return $this->dir.$this->type."/".$id."/";
    }

    public function __construct($directory, $type, SecurityContext $sc, $wsd)
    {
        if(!(substr_compare($directory, '/', 1, 1) === 0))
            $directory = $directory.'/';
        $this->dir = $directory;
        $this->type = $type;
        $this->sc = $sc;
        $this->wiselib_src_dir=$wsd;
    }
}


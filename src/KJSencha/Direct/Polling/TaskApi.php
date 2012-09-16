<?php

namespace KJSencha\Direct\Polling;

use InvalidArgumentException;
use KJSencha\Annotation\Interval;
use Zend\Code;
use Zend\Code\Annotation\AnnotationManager;

/**
 * Task API
 *
 * The API which gathers classes that will be run
 * by the Task Runner
 */
class TaskApi implements TaskApiInterface
{
    /**
     * @var AnnotationManager 
     */
    protected $annotationManager;
    protected $tasks = array();
    protected $directories = array();

    /**
     * @param AnnotationManager $annotationManager
     */
    public function setAnnotationManager($annotationManager)
    {
        $this->annotationManager = $annotationManager;
    }

    /**
     * Annotation manager
     *
     * @return AnnotationManager
     */
    public function getAnnotationManager()
    {
        return $this->annotationManager;
    }

    /**
     * Set the directories in which the API will look
     * for polling tasks
     *
     * @param array $dirs
     */
    public function setDirectories(array $dirs)
    {
        $this->tasks = null;
        $this->directories = array();

        foreach ($dirs as $dir) {
            $this->addDirectory($dir);
        }
    }

    /**
     * Add a directory
     *
     * @param string $path Path which contains classes
     */
    public function addDirectory($path)
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException(
                'Invalid directory given: ' . $path
            );
        }

        $this->directories[] = $path;
    }

    /**
     * @return array
     */
    public function getDirectories()
    {
        return $this->directories;
    }

    /**
     * Retrieve tasks from the current given directories
     *
     * @return array
     */
    public function getTasks()
    {
        if (null == $this->tasks) {
            foreach ($this->getDirectories() as $path) {
                $directoryScanner = new Code\Scanner\DirectoryScanner($path);

                /* @var $class DerivedClassScanner */
                foreach ($directoryScanner->getClasses(TRUE) as $class) {
                    foreach ($class->getMethods(TRUE) as $classMethod) {
                        // Only public callable methods are allowed
                        if (!$classMethod->isPublic()) {
                            continue;
                        }

                        $task = new Task;
                        $task->setClassname($class->getName());
                        $task->setMethod($classMethod->getName());

                        foreach ($classMethod->getAnnotations($this->getAnnotationManager()) as $annotation) {
                            if ($annotation instanceof Interval) {
                                $task->addInterval($annotation->getSeconds());
                            }
                        }

                        $this->tasks[] = $task;
                    }
                }
            }
        }

        return $this->tasks;
    }

}

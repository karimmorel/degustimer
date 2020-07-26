<?php

namespace App\Interfaces;

interface TaskSpanRepositoryInterface {
    public function getSummary();
    public function getNewTaskInView($runningTask);
    public function getTodaysWorkingTime();
    public function stopRunningTaskSpan();
    public function getRunningTaskSpan();
}
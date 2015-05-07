<?php

class TestCommand extends Command {

    public function __invoke()
    {
$this->xxx;
        $this->stdio->outln('<<green>>This is TestCommand class<<reset>>');
    }

}

<?php

class WrongCommand extends Command {

    public function __invoke()
    {
        $this->stdio->outln('<<green>>This is WrongCommand class<<reset>>');
    }

}

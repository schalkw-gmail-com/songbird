<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Media extends Bundle
{
    // use a child bundle
    public function getParent()
    {
        return 'CoopTilleulsCKEditorSonataMediaBundle';
    }

}

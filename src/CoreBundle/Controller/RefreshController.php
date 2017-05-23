<?php

namespace Dwl\Exim\CoreBundle\Controller;

# COMMON
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Console\Application;

# NUKE
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand;
use Symfony\Component\Console\Output\NullOutput;

# SONATA
use Sonata\PageBundle\Command\CreateSnapshotsCommand;
use Sonata\PageBundle\Command\UpdateCoreRoutesCommand;

class RefreshController extends Controller
{

    private function getApplicationAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        return $application;
    }

    private function setResponseAction($application, $input)
    {

        // You can use NullOutput() if you don't need the output
        // $output = new NullOutput();
        $output = new BufferedOutput(
            // OutputInterface::VERBOSITY_NORMAL,
            // true // true for decorated
        );

        // The @ is kinda bad, but there always was a "Warning: ini_set(): A session is active."
        // Which caused the response to be a 500.
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        // $converter = new AnsiToHtmlConverter();
        $content = $output->fetch();

        // return new Response(""), if you used NullOutput()
        // return $converter->convert($content);
        return $content;


    }

    public function nukeCacheClearAction()
    {

        $application = $this->getApplicationAction();

        $input = new ArrayInput(
            array(
                'command' => 'cache:clear',
                '--env' => 'dev',
            )
        );

        $response = $this->setResponseAction($application, $input);

        return new Response('{"cache":"cleared"}');

    }

    public function sonataPageCreateSnapshotsAction()
    {
        $application = $this->getApplicationAction();

        $input = new ArrayInput(
            array(
                'command' => 'sonata:page:create-snapshots',
                '--site' => array('all'),
                '--keep-snapshots' => 5,
            )
        );

        $response = $this->setResponseAction($application, $input);

        return new Response('{"snapshots":"created"}');

    }

    public function sonataPageUpdateCoreRoutesAction()
    {
        $application = $this->getApplicationAction();

        $input = new ArrayInput(
            array(
                'command' => 'sonata:page:update-core-routes',
                '--site' => array('all'),
            )
        );


        $response = $this->setResponseAction($application, $input);

        return new Response('{"routes":"updated"}');
    }

}

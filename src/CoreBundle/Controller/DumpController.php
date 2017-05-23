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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Output\NullOutput;

class DumpController extends Controller
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

    /*
     * error while trying to access container with different port expose and bind
     */
    public function mysqldumpAction()
    {

        $application = $this->getApplicationAction();

        $database_host = $this->getParameter('database_host');
        $database_port = $this->getParameter('database_port');
        $database_port_ssh = 65034;
        $database_name = $this->getParameter('database_name');
        $database_user = $this->getParameter('database_user');
        $database_password = $this->getParameter('database_password');
        $backup_dir = $this->getParameter('exim.path.backup');

        /*
         * activate ssh-keygen access to mysql server
         * ssh-keygen -t rsa -b 2048
         * ssh -p '$database_port_ssh' '$database_user@$database_host'
         */

        /*
            ssh \
                -p $database_port_ssh \
                $database_user@$database_host \
            mysqldump \
                -h localhost \
                -u $database_user \
                -p$database_password \
                --add-drop-table $database_name \
            > $backup_dir/mysql/$database_name.sql
        */
        $connection = ssh2_connect($database_host, $database_port_ssh);
        ssh2_auth_password($connection, $database_user, $database_password);

        $stream = ssh2_exec($connection, "mysqldump -h localhost -u $database_user -p$database_password --add-drop-table $database_name > /home/$database_user/exports/mysql/$database_name-".date("Y-m-d").".sql");
        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);

        stream_set_blocking($errorStream, true);
        stream_set_blocking($stream, true);


        $output = stream_get_contents($stream);
        $error = stream_get_contents($errorStream);

        var_dump("Output: " . $output, "Error: " . $error);

        fclose($errorStream);
        fclose($stream);

        // $response = $this->setResponseAction($application, $input);

        // return new Response('{"mysql":"backed up"}');
        return new Response($output);

    }

}

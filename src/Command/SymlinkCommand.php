<?php

namespace Stfalcon\Bundle\TinymceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * Class SymlinkCommand.
 *
 * @package Stfalcon\Bundle\TinymceBundle\Command
 */
class SymlinkCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('stfalcon:tinymce:symlink')
            ->setDescription('Installs TinyMCE web assets under a public web directory');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $localPath = sprintf('%s/../Resources/public/vendor/tinymce', __DIR__);
        $this->getFilesystem()->remove($localPath);

        $tinymcePath = sprintf('%s/../vendor/tinymce/tinymce', $this->getContainer()->getParameter('kernel.root_dir'));

        $this->getFilesystem()->symlink($tinymcePath, $localPath, true);
        if (!file_exists($localPath)) {
            throw new IOException('Symbolic link is broken');
        }
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    private function getFilesystem()
    {
        return $this->getContainer()->get('filesystem');
    }
}

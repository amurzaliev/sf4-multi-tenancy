<?php

namespace App\Listener\Command;


use App\Doctrine\DBAL\TenantConnection;
use App\Entity\Main\Tenant;
use App\Repository\Main\TenantRepository;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputOption;

class CommandListener
{
    /** @var  array|string[] */
    private $allowedCommands;

    /** @var TenantConnection */
    private $tenantConnection;

    /** @var TenantRepository */
    private $tenantRepository;

    /**
     * ClubConnectionCommandListener constructor.
     *
     * @param TenantConnection $tenantConnection
     * @param TenantRepository $tenantRepository
     * @param array $allowedCommands
     */
    public function __construct(
        TenantConnection $tenantConnection,
        TenantRepository $tenantRepository,
        $allowedCommands = []
    )
    {
        $this->tenantConnection = $tenantConnection;
        $this->tenantRepository = $tenantRepository;
        $this->allowedCommands = $allowedCommands;
    }

    /**
     * @param ConsoleCommandEvent $event
     * @throws \Exception
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        $input = $event->getInput();

        if (!$this->isProperCommand($command)) {
            return;
        }

        $command->getDefinition()->addOption(
            new InputOption('tenant', null, InputOption::VALUE_OPTIONAL, 'Tenant database name', null)
        );

        if (!$command->getDefinition()->hasOption('em')) {
            $command->getDefinition()->addOption(
                new InputOption('em', null, InputOption::VALUE_OPTIONAL, 'The entity manager to use for this command')
            );
        }

        $input->bind($command->getDefinition());

        if (is_null($input->getOption('tenant'))) {
            return;
        }

        $tenantName = $input->getOption('tenant');
        $input->setOption('em', 'tenant');

        $command->getDefinition()->getOption('em')->setDefault('tenant');

        /** @var Tenant $tenant */
        $tenant = $this->tenantRepository->findOneBy(['dbName' => $tenantName]);

        if ($tenant === null) {
            throw new Exception(sprintf('Tenant identified as %s does not exists', $tenantName));
        }

        $this->tenantConnection->changeParams($tenant->getDbName(), $tenant->getDbUser(), $tenant->getDbPassword());
    }

    /**
     * @param Command $command
     * @return bool
     */
    private function isProperCommand(Command $command)
    {
        return in_array($command->getName(), $this->allowedCommands);
    }
}
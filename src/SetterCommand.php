<?php

namespace ADT\TracySystemInfo;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'tracy-system-info:add', description: 'Add custom information to Tracy.')]
class SetterCommand extends Command
{
	protected static $defaultName = 'tracy-system-info:add';

	/** @var Storage */
	private $storage;

	const AUTO_VALUES = [
		'timestamp',
	];

	public function __construct(Storage $hostnameStorage)
	{
		parent::__construct();
		$this->storage = $hostnameStorage;
	}

	public function configure(): void
	{
		$this->addArgument('key', InputArgument::OPTIONAL);
		$this->addArgument('value', InputArgument::OPTIONAL);

		foreach (static::AUTO_VALUES as $autoValueName) {
			$this->addOption($autoValueName, null, InputOption::VALUE_NONE);
		}

	}

	public function execute(InputInterface $input, OutputInterface $output): int
	{
		if ($input->getArgument('key')) {
			$this->storage->add($input->getArgument('key'), $input->getArgument('value'));
		}

		foreach (static::AUTO_VALUES as $autoValueName) {
			if (!$input->getOption($autoValueName)) {
				continue;
			}

			$camelCaseName = str_replace('-', '', ucwords($autoValueName, '-'));  // dashesToCamelCase: https://stackoverflow.com/a/2792045/4837606
			$this->storage->add($autoValueName, $this->{'value' . $camelCaseName}());
		}

		return 0;
	}

	protected function valueTimestamp() {
		return (new \DateTime())->format('Y-m-d H:i:s');
	}

}

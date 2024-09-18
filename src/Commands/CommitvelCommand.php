<?php

namespace CodingWisely\Commitvel\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

class CommitvelCommand extends Command
{
    protected $signature = 'cw:commitvel';

    protected $description = 'Kind of pre-commit hook for running Pint, PHPPest, and managing Git operations.';

    public function handle(): void
    {
        if (! $this->hasChanges()) {
            $this->tellJokeAndExit();
        }

        $this->ensurePintInstalled();
        $this->ensurePestIsInstalled();
        $this->runPint();

        $this->runTests();

        $this->stageFixedFiles();
        $this->commitChanges();
    }

    protected function hasChanges(): bool
    {
        $changes = shell_exec('git status --porcelain');
        $this->info("Initial git status changes: \n".$changes);

        return ! empty(trim($changes));
    }

    protected function tellJokeAndExit(): void
    {
        $jokes = [
            'Trying to commit with no changes? 🤔 Even the best magicians can’t pull code out of thin air! 🧙‍♂️',
            "No changes detected. 🚫 It looks like you’re trying to commit fresh air. 🌬️ Unfortunately, our repository doesn't support invisible code! 👻",
            'It seems you’re trying to commit nothing. 🤷 Even Sherlock Holmes couldn’t investigate an empty commit! 🕵️‍♂️',
            'Attempting to commit air? 🌬️ Sadly, our repository isn’t well-ventilated for that! 🚪',
            'Commits need changes, not empty promises! 📜 Maybe the dog really did eat your code this time? 🐕',
            "Trying to commit nothing? That's like sending an empty gift box! 🎁📦",
            'No changes? Did you just try to send an imaginary friend to the repo? 🧙‍♂️🦄',
            'Looks like you’re committing to commitment issues! 💍❌',
            'Did you know? Even black holes have more substance than your commit! 🌌🕳️',
            "Trying to commit empty-handed? That's like bringing a fork to a soup-eating contest! 🍴🍲",
            'No code changes? Are you sure you’re not just practicing your keystrokes? 🎹⌨️',
            "Committing nothing? ❌ That's like sending a blank postcard! ✉️📬",
            'You’re so good, you’re committing pure potential! 🚀✨',
            'Trying to commit zero? That’s like trying to toast invisible bread! 🍞🔍',
            'No changes to commit? You just invented the stealth commit! 🕵️‍♀️✨',
            'Trying to commit with zero changes? That’s like writing a book with invisible ink! 📖✒️',
            'No changes to commit? It’s like trying to paint with an empty brush! 🎨🖌️',
            "Committing without changes? That's like showing up to a potluck with an empty bowl! 🍲🔍",
            'Zero changes in your commit? That’s like trying to sail with no wind! ⛵🌬️',
            'Attempting an empty commit? It’s as productive as a screen door on a submarine! 🛳️🚪',
            'Committing no changes? That’s like trying to tune a guitar with no strings! 🎸❌',
            'No changes? That’s like sending a love letter to an empty mailbox! 💌📪',
            "Trying to commit nothing? That's as useful as a waterproof teabag! ☕🚫",
            'No changes to commit? Even ghosts leave more trace! 👻🕵️‍♂️',
            'Attempting an empty commit? That’s like cooking with imaginary ingredients! 🍳🥄',
            'No changes in your commit? That’s like racing in a stationary car! 🚗🛑',
            "Committing air? That's like showing up to a concert with earplugs in! 🎤👂",
            'Trying to commit with zero changes? That’s like playing soccer with an invisible ball! ⚽🕵️‍♀️',
        ];

        $randomJoke = $jokes[array_rand($jokes)];
        $this->info($randomJoke);
        exit(1);
    }

    protected function ensurePintInstalled(): void
    {
        $this->ensureToolInstalled('vendor/bin/pint', 'Laravel Pint is not installed. Would you like to install it?', 'composer require laravel/pint --dev', 'Laravel Pint installed successfully.');
    }

    protected function ensurePestIsInstalled(): void
    {
        $this->ensureToolInstalled('vendor/bin/pest', 'PHP Pest is not installed. Would you like to install it?', 'composer require pestphp/pest --dev --with-all-dependencies', 'PHP Pest installed successfully.', 'composer remove phpunit/phpunit', 'Removing PHP Unit...');
    }

    private function ensureToolInstalled(string $path, string $confirmMessage, string $installCommand, string $successMessage, string $preCommand = '', string $preMessage = ''): void
    {
        if (! file_exists(base_path($path))) {
            if (confirm($confirmMessage, true)) {
                if ($preCommand) {
                    $this->info($preMessage);
                    shell_exec($preCommand);
                }
                shell_exec($installCommand);
                $this->info($successMessage);
            } else {
                $this->error("$confirmMessage is required. Aborting.");
                exit(1);
            }
        }
    }

    protected function runPint(): void
    {
        $outputFiles = $this->runTool('Laravel Pint', './vendor/bin/pint --dirty', '/^\s+✓ (\S+)/m');

        foreach ($outputFiles as $file) {
            // Check file permissions
            if (! is_writable($file)) {
                $this->warn("File $file is not writable.");

                continue;
            }

            // Stage the file
            shell_exec('git add '.escapeshellarg($file));
            $stagedStatus = shell_exec('git status --porcelain '.escapeshellarg($file));
            $this->info("Staging status for $file: \n".$stagedStatus);
        }
    }

    protected function runTests(): void
    {
        $this->runTool('Pest Tests', './vendor/bin/pest', '', 'Fail', 'Error');
    }

    private function runTool(string $toolName, string $command, string $regexPattern = '', string $errorKeyword1 = 'FAIL', string $errorKeyword2 = 'ERROR'): array
    {
        $outputFiles = [];
        if (confirm("Would you like to run $toolName?", true)) {
            spin(
                function () use ($command, &$outputFiles, $regexPattern, $errorKeyword1, $errorKeyword2, $toolName) {
                    $output = shell_exec($command);
                    $this->info($output);

                    if ($regexPattern) {
                        preg_match_all($regexPattern, $output, $matches);
                        $outputFiles = $matches[1] ?? [];
                    }

                    if (str_contains($output, $errorKeyword1) || str_contains($output, $errorKeyword2)) {
                        $this->error("$toolName found errors. Please fix them before proceeding.");
                        exit(1);
                    }
                },
                "Running $toolName..."
            );
        } else {
            $this->info("Skipping $toolName.");
        }

        return $outputFiles;
    }

    protected function stageFixedFiles(): void
    {
        $this->info('Running git add -u to stage any fixed files.');
        shell_exec('git add -u');
        $stagedFiles = shell_exec('git status --porcelain --untracked-files=no');
        $this->info("Files after git add -u: \n".$stagedFiles);

        if (empty(trim($stagedFiles))) {
            $this->info('No changes staged for commit.');
        }
    }

    protected function commitChanges(): void
    {
        $stagedFiles = shell_exec('git status --porcelain --untracked-files=no');
        $this->info("Staged files for commit: \n".$stagedFiles);

        if (empty(trim($stagedFiles))) {
            $this->info('No changes staged for commit.');

            return;
        }

        $commitMessage = text('Enter the commit message');
        if (! $commitMessage) {
            $this->error('Commit message cannot be empty.');
            exit(1);
        }

        shell_exec('git commit -m '.escapeshellarg($commitMessage));
        $this->info('Changes committed.');

        $currentBranch = $this->getCurrentBranch();
        $branch = $this->ask('Pushing code to', $currentBranch);

        while (! $branch) {
            $this->error('Branch name cannot be empty.');
            $branch = $this->ask('Pushing code to', $currentBranch);
        }

        $this->info("Pushing code to branch $branch...");

        // Spin while pushing the code
        spin(fn () => $this->pushToBranch($branch), "Pushing code to $branch...");

        $commitHash = trim(shell_exec('git log -1 --format="%H"'));
        $gitUserName = trim(shell_exec('git config user.name'));
        $this->info("Commit hash: $commitHash");
        $this->info("Pushed by: $gitUserName");
    }

    private function getCurrentBranch(): string
    {
        $process = new Process(['git', 'rev-parse', '--abbrev-ref', 'HEAD']);
        $process->run();

        return trim($process->getOutput());
    }

    private function pushToBranch(string $branch): void
    {
        $process = new Process(['git', 'push', 'origin', $branch]);
        $process->run();

        if ($process->isSuccessful()) {
            $this->info('Code pushed successfully.');
        } else {
            $this->error('Failed to push code.');
            $this->info($process->getErrorOutput());
        }
    }
}
